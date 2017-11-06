<?php

class ApdInteract_Salesforce_Model_Sao_Sales 
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {
	
	public function __construct() {
		$this->_sobjectName = "Order";
		parent::__construct();
	}
	
}