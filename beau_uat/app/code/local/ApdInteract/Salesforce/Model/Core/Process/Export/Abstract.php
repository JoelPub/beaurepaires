<?php
class ApdInteract_Salesforce_Model_Core_Process_Export_Abstract
extends ApdInteract_Salesforce_Model_Core_Process_Abstract {
	/**
	 *
	 * @var ApdInteract_Salesforce_Helper_Reader_Customer
	 */
	protected $reader;
	
	/**
	 *
	 * @var ApdInteract_Salesforce_Model_Sao_Customer
	 */
	protected $sao;
	
	/**
	 *
	 * @var ApdInteract_Salesforce_Model_Resource_Dictionary_Collection
	 */
	protected $ditionary_collection;
	
	/**
	 *
	 * @var ApdInteract_Salesforce_Helper_Mapper_Customer
	 */
	protected $mapper;
	
	/**
	 *
	 * @var array
	 */
	protected $available_dictionaries;
	
	/**
	 *
	 * @var array
	 */
	protected $entities_to_update;
	
	/**
	 *
	 * @var array
	 */
	protected $entities_to_create;
        
        protected $last_updated;
	
	/**
	 * 
	 * @param array $params
	 */
	public function __construct($params = array()) {
		parent::__construct($params);
		echo "loading dictionaries...\n";
		$this->loadAvaDictionaries();
		echo "loading data into memory...\n";
		$this->loadEntitiesToCreate();
		$this->loadEntitiesToUpdate();
		echo "finish loading\n";
	}
	
	/**
	 * 
	 * @return ApdInteract_Salesforce_Model_Core_Process_Export_Abstract
	 */
	public function process(){
		$toCreate = $this->entities_to_create;
		$toUpdate = $this->entities_to_update;
		echo count($toCreate)." rows to create. ".count($toUpdate)." rows to update\n";		
		$this->bulkCreateEntities($toCreate);
		echo "\n";
		$this->bulkUpdateEntities($toUpdate);
		return $this;
	}
	
	/**
	 * 
	 * @return ApdInteract_Salesforce_Model_Core_Process_Export_Abstract
	 */
	protected function loadAvaDictionaries() {
                $helper = Mage::Helper('apdinteract_salesforce');
		$class = $this->reader->getModelClassName();
                $class = ($helper->getEquivalent($class)!='') ? $helper->getEquivalent($class) : $class;
		$dc = $this->ditionary_collection->addFieldToFilter("entity_type", $class)->load();
		$this->available_dictionaries = array();
		foreach ($dc as $dic) {
			$eid = $dic->getData("entity_id");
			$this->available_dictionaries[$eid] = $dic;
		}
		return $this;
	}
	
	/**
	 * 
	 * @return ApdInteract_Salesforce_Model_Core_Process_Export_Abstract
	 */
	protected function loadEntitiesToUpdate() {
		$this->entities_to_update = array();
		$collection = $this->reader->addToUpdateFilter($this->available_dictionaries)->load();
		foreach ($collection as $entity) {
			$eid = $entity->getId();
			$dictionary = $this->available_dictionaries[$eid];
			$source = $entity;
			$sid = $dictionary->getData("salesforce_id");
			$data = $this->mapper->map($source);
			$this->entities_to_update[] = array("sid"=>$sid, "data"=>$data,"source"=>$source,"dictionary"=>$dictionary);
		}
		return $this;
	}
	
	/**
	 * 
	 * @return ApdInteract_Salesforce_Model_Core_Process_Export_Abstract
	 */
	protected function loadEntitiesToCreate(){
		$this->entities_to_create = array();
		$collection = $this->reader->addToCreateFilter($this->available_dictionaries)->load();
		foreach ($collection as $entity) {
			$eid = $entity->getId();
			$source = $entity;
			$data = $this->mapper->map($source);
			$this->entities_to_create[] = array("data"=>$data, "source"=>$source);
		}
		return $this;
	}
	
	/**
	 * 
	 * @param unknown $input
	 * @param number $batchSize
	 * @return ApdInteract_Salesforce_Model_Core_Process_Export_Abstract
	 */
	protected function bulkCreateEntities($input, $batchSize = 25) {
		$size = sizeof($input);
		$count = 0;
		$batch = array();
		foreach ($input as $row) {
				
			$sobject = $row["data"];
			$source  = $row["source"];
				
			$batch[] = $sobject;
			$sourceBatch[] = $source;
			$count++;
				
			if ($count % $batchSize == 0 || $count == $size) {
				$all_results = $this->sao->bulkCreate($batch)->getResult();
				$i = 0;
				foreach ($all_results[0]->results as $result) {					
					if (property_exists($result->result, "id")) {
						$dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
						$temp_source = $sourceBatch[$i];
						$dictionary->saveDictionary($temp_source, $result->result->id);
					} else {
						$temp_source= $sourceBatch[$i];
						echo "failed to create for [".get_class($temp_source)."] ".$temp_source->getId()." due to: ".$result->result[0]->message."\n";
					}
					$i++;
				}
				$batch = array();
				$sourceBatch = array();
			}
			echo "creating $count / $size reocrds\r";
		}
		return $this;
	}
	
	/**
	 * 
	 * @param unknown $input
	 * @param number $batchSize
	 * @return ApdInteract_Salesforce_Model_Core_Process_Export_Abstract
	 */
	protected function bulkUpdateEntities($input, $batchSize = 25) {
		$size = sizeof($input);
		$count = 0;
		$batch = array();
		foreach ($input as $row) {
			$sid  = $row["sid"];
			$dictionary = $row["dictionary"];
			$sobject = $row["data"];
			$source  = $row["source"];
	
			$batch[$sid] = $sobject;
			$dicBatch[] = $dictionary;
			$sourceBatch[] = $source;
	
			$count++;
				
			if ($count % $batchSize == 0 || $count == $size ) {
				$results = $this->sao->bulkUpdate($batch)->getResult();
				for($i = 0; $i< sizeof($dicBatch); $i++) {
					$dicBatch[$i]->saveDictionary($sourceBatch[$i]);
				}
				$batch = array();
				$dicBatch = array();
				$sourceBatch = array();
			}
			
			echo "updating $count / $size reocrds\r";
		}
		return $this;
	}
}