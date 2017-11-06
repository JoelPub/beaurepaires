<?php

class ApdInteract_Salesforce_Model_Sao_Sales_Invoice_Invoice
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {

	public function __construct() {
		$this->_sobjectName = "Invoice__c";
		parent::__construct();
	}

}