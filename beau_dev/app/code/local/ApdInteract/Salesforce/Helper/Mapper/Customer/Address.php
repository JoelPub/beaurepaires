<?php
class ApdInteract_Salesforce_Helper_Mapper_Customer_Address
extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {
		
	public function __construct() {
		$this->protocol = array(
			"entity_id" => "Magento_Customer_Address_ID__c",
			"firstname" => "First_Name__c",
			"lastname"=> "Last_Name__c",
			"city"=> "City__c",
			"region"=> "Region__c",
			"postcode"=> "Postcode__c",
			"country_id"=> "Country__c",
			"telephone"=> "Telephone__c",
			"business_phone"=> "Business_Phone__c",
			"mobile"=> "Mobile__c",
			"prefix"=> "Prefix__c",
			"middlename"=> "Middle_Name__c",
			"suffix"=> "Suffix__c",
			"company"=> "Company__c",
			"fax"=> "Fax__c",
			"street"=> "Street__c",
			"is_active"=> "Is_Active__c",
			"is_default_billing" => "Is_Default_Billing__c",
			"is_default_shipping" => "Is_Default_Shipping__c",
			"parent_id"=> "Customer__c"
		);
	}
	
	/**
	 * map from
	 *
	 * {@inheritDoc}
	 * @see ApdInteract_Salesforce_Helper_Core_Mapper_Abstract::map()
	 */
	public function map(Mage_Customer_Model_Address $model){
		$result = parent::map($model);
		$result["Is_Active__c"] = $this->convertBoolean($model->getData("is_active"));
		$result["Is_Default_Billing__c"] = $this->convertBoolean($model->getData("is_default_billing"));
		$result["Is_Default_Shipping__c"] = $this->convertBoolean($model->getData("is_default_shipping"));
		$result["Customer__c"] = $this->getSalesforceId($model);
		return $result;
	}
	
	public function convertBoolean($input){
		return $input == 1;
	}
	
	public function getSalesforceId(Mage_Customer_Model_Address $input) {
		$model = $input->getCustomer();
		$dictionary = Mage::getSingleton("apdinteract_salesforce/dictionary")
							->getCollection()
							->getDictionaryByModel($model); 
		return $dictionary->getData("salesforce_id");
	}
	
}