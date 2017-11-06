<?php

class ApdInteract_Salesforce_Helper_Reader_Customer_Address 
extends ApdInteract_Salesforce_Helper_Core_Reader_Abstract {	
	
	public function __construct() {
		$this->model_name = "customer/address";
		parent::__construct();
	}
	
	public function getAllSyncedCustomerIds() {
		$class = get_class(Mage::getModel("customer/customer"));
		$dc = Mage::getModel("apdinteract_salesforce/dictionary")
					->getCollection()
					->addFieldToFilter("entity_type", $class)
					->load();
		$result = array();
		foreach ($dc as $d) {
			$result[] = $d->getData("entity_id");
		}
		return $result;		
	}
	
	public function __init() {
		parent::__init();
		$this->collection->addAttributeToSelect('*');
		$this->collection->addFieldToFilter("parent_id", array("in"=>$this->getAllSyncedCustomerIds()));
		return $this;
	}
	
}