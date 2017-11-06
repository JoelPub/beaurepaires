<?php

class ApdInteract_Salesforce_Helper_Reader_Customer_Vehicle 
extends ApdInteract_Salesforce_Helper_Core_Reader_Abstract {
	
	public function __construct() {
		$this->model_name = "apdinteract_vehicle/vehicle";
		parent::__construct();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ApdInteract_Salesforce_Helper_Core_Reader_Abstract::__init()
	 */
	public function __init() {
		parent::__init();
		$this->id_name = "vehicle_id";
		$this->collection->addFieldToFilter("customer_id", array("in"=>$this->getAllSyncedCustomerIds()));
		return $this;
	}
	
	/**
	 * 
	 * @return NULL[]
	 */
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
	
}