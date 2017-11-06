<?php

class ApdInteract_Salesforce_Model_Sao_Vircommercial 
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {
	
	public function __construct() {
		$this->_sobjectName = "VIR_Commercial_Report__c";
		parent::__construct();
	}
	
}