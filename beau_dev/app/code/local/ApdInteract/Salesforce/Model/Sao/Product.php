<?php

class ApdInteract_Salesforce_Model_Sao_Product 
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {
	
	public function __construct() {
		$this->_sobjectName = "Product2";
		parent::__construct();
	}
	
}