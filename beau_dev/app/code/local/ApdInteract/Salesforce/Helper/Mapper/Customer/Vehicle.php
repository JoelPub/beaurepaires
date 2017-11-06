<?php
class ApdInteract_Salesforce_Helper_Mapper_Customer_Vehicle 
extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {
	
	public function __construct() {
		$this->protocol = array(
			"customer_id" => "Vehicle_Owner__c",
			"vehicle_id" => "Magento_Customer_Vehicle_ID__c",
			"make" => "Make__c",
			"manufacture_year" => "Manufacture_Year__c",
			"model" => "Model__c",
			"series" => "Series__c",
			"registration" => "Registration__c",
			"details" => "Details__c",
			"url" => "Url__c"
		);
	}
	
	/**
	 * map from
	 * 
	 * {@inheritDoc}
	 * @see ApdInteract_Salesforce_Helper_Core_Mapper_Abstract::map()
	 */
	public function map(ApdInteract_Vehicle_Model_Vehicle $model){
		$result = parent::map($model);
		$result["Vehicle_Owner__c"] = $this->getSalesforceId($model);
		return $result;
	}
	
	public function getSalesforceId(ApdInteract_Vehicle_Model_Vehicle $input) {
		$cid = $input->getData("customer_id");
		$customer = Mage::getModel("customer/customer")->load($cid);
		$dictionary = Mage::getSingleton("apdinteract_salesforce/dictionary")
		->getCollection()
		->getDictionaryByModel($customer);
		return $dictionary->getData("salesforce_id");
	}
	
}