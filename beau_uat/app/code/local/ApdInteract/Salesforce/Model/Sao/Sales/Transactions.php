<?php

class ApdInteract_Salesforce_Model_Sao_Sales_Transactions
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {

	public function __construct() {
		$this->_sobjectName = "Payment_Transaction__c";
		parent::__construct();
	}

}