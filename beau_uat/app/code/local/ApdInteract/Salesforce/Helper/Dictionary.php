<?php
class ApdInteract_Salesforce_Helper_Dictionary
extends Mage_Core_Helper_Abstract {
	
	/**
	 * 
	 * @var ApdInteract_Salesforce_Model_Dictionary
	 */
	protected $model;
	
	/**
	 * 
	 * @var ApdInteract_Salesforce_Model_Resource_Dictionary_Collection
	 */
	protected $collection;
	
	
	/**
	 * 
	 * @var Mage_Core_Model_Date
	 */
	protected $date_model;
	
	public function __construct() {
		$this->model = Mage::getModel("apdinteract_salesforce/dictionary");	
		$this->collection = $this->model->getCollection();
		$this->date_model = Mage::getModel('core/date');
	}
	
	public function loadDictionary($model) {
		return $this;
	}
		
	/**
	 * @return ApdInteract_Salesforce_Model_Dictionary
	 */
	public function getModel() {
		return $this->model;
	}
	
	/**
	 * get class name of a model
	 * 
	 * @param Mage_Core_Model_Abstract $model
	 * @return string
	 */
	public function getEntityName($model) {
		return get_class($model);		
	}
	
}