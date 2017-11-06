<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Customer
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Customer
extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {
	
	/**
	 * 
	 */
	public function __construct() {
		$this->protocol = array(
			"entity_id" 	=> "magento_customer_id__c",
			"website_id" 	=> "magento_website__c",
			"email" 		=> "email__c",
			"name" 			=> "Name",
			"gender" 		=> "gender__c",
			"taxvat" 		=> "Tax_Vat__c",
			"costar_customer_id" => "costar_customer_id__c",
			"dormant_flag" 	=> "Dormant_Flag__c",
			"group_id"		=> "magento_customer_group__c"
		);
	}
	
	/**
	 * 
	 * 
	 * @param Mage_Customer_Model_Customer $input
	 */
	public function map($input){
		$result = parent::map($input);
		$result["Name"] = $input->getData("firstname")." ".$input->getData("lastname");
		$result["Dormant_Flag__c"] = $input->getData("dormant_flag") == 0 ? false : true;
		$result["gender__c"] = $input->getData("gender") == 2 ? "Female" : "Male";
		$result["magento_website__c"] = $this->getWebsiteType($input->getData("website_id"));
		$result["magento_customer_group__c"] = $this->getGroup($input->getData("group_id"));
		$this->getAddressFields($input->getDefaultBillingAddress(), $result);
		$this->getAddressFields($input->getDefaultShippingAddress(), $result, "Shipping");
		return $result;
	}
	
	/**
	 * 
	 * @param Mage_Customer_Model_Address $input
	 * @param unknown $type
	 */
	protected function getAddressFields($input, &$result, $type = "Billing") {
		if($input) {
			$result[$type."Street"] = $input->getData("street");
			$result[$type."City"] = $input->getData("city");
			$result[$type."PostalCode"] = $input->getData("postcode");
			$result[$type."State"] = $input->getData("region");
			$result[$type."Country"] = $input->getData("country_id");
		}
	}
	
	/**
	 * 
	 * @param object $input
	 */
	protected function getWebsiteType($input) {
		$result = "Beaurepaires";
		return $result;
	}
	
	protected function getGroup($input) {
		$result = "General";
		switch($input) {
			case 1:
				$result = "General";
				break;
			case 2:
				$result = "Wholesale";
				break;
			case 4:
				$result = "Mates Rates Member";
				break;
			case 5:
				$result = "Mates Rates Member";
				break;
			case 6:
				$result = "Uber Driver";
				break;
			case 7:
				$result = "Mates Rates Member";
				break;
			default:
				break;
		}
		return $result;
	}
	
}