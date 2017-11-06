<?php

class ApdInteract_Salesforce_Model_Sao_Sales_Creditmemo_Comment
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {

	public function __construct() {
		$this->_sobjectName = "Credit_Memo_Comment__c";
		parent::__construct();
	}

}