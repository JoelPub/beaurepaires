<?php

class ApdInteract_Salesforce_Model_Sao_Healthcheck 
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {
	
	public function __construct() {
		$this->_sobjectName = "VIR_Consumer_10_Point_Health_Check__c";
		parent::__construct();
	}
	
}