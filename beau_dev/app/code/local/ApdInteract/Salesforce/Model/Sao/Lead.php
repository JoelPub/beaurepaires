<?php

class ApdInteract_Salesforce_Model_Sao_Lead 
extends ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract {
	
	public function __construct() {
		$this->_sobjectName = "Lead";
		parent::__construct();
	}
	
}