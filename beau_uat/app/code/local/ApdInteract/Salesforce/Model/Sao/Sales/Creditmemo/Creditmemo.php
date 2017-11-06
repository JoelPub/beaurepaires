<?php

class ApdInteract_Salesforce_Model_Sao_Sales_Creditmemo_Creditmemo
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {

	public function __construct() {
		$this->_sobjectName = "Credit_Memo__c";
		parent::__construct();
	}

}