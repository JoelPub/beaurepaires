<?php

class ApdInteract_Salesforce_Model_Sao_Virconsumer 
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {
	
	public function __construct() {
		$this->_sobjectName = "VIR_Consumer_Repport__c";
		parent::__construct();
	}
	
}