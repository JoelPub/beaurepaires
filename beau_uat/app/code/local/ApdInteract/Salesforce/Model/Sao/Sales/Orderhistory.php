<?php

class ApdInteract_Salesforce_Model_Sao_Sales_Orderhistory
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {

	public function __construct() {
		$this->_sobjectName = "Order_History__c";
		parent::__construct();
	}

}