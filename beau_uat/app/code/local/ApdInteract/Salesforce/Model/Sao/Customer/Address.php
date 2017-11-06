<?php

class ApdInteract_Salesforce_Model_Sao_Customer_Address
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {

	public function __construct() {
		$this->_sobjectName = "Customer_Address__c";
		parent::__construct();
	}

}