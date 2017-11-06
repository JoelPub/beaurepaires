<?php

class ApdInteract_Salesforce_Helper_Reader_Customer 
extends ApdInteract_Salesforce_Helper_Core_Reader_Abstract {
	
	public function __construct() {
		$this->model_name = "customer/customer";
		parent::__construct();		
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ApdInteract_Salesforce_Helper_Core_Reader_Abstract::__init()
	 */
	public function __init(){
		parent::__init();
		$this->collection->addAttributeToSelect('*');
		$this->collection->addFieldToFilter("firstname", array("neq"=>"TEST"));//trim apd test
		$this->collection->addFieldToFilter("lastname", array("neq"=>"TEST"));
	}
	
}