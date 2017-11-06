<?php

class ApdInteract_Salesforce_Model_Sao_Sales_Invoice_Comment
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {

	public function __construct() {
		$this->_sobjectName = "Invoice_Comment__c";
		parent::__construct();
	}

}