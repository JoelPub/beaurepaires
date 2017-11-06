<?php
class ApdInteract_Salesforce_Model_Sao_Customer_Vehicle
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {

	public function __construct() {
		$this->_sobjectName = "Customer_Vehicle__c";
		parent::__construct();
	}

}