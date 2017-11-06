<?php

class ApdInteract_Salesforce_Model_Sao_Review 
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {
	
	public function __construct() {
		$this->_sobjectName = "Product_Review__c";
		parent::__construct();
	}
	
}