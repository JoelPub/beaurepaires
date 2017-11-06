<?php
/**
 * ApdInteract_Salesforce_Helper_Core_Mapper_Abstract
 * 
 * Abstract class
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Core_Mapper_Abstract
extends Mage_Core_Helper_Abstract {
	
	protected $protocol;
	
	public function __construct() {
		$this->protocol = array();
	}
	
	public function map(Mage_Core_Model_Abstract $input) {
		$result 		= array();
		$magento_fields = array_keys($this->protocol);
		foreach ($magento_fields as $field) {
			$sffield 			= $this->protocol[$field];
			$value 				= $input->getData($field);
			$result[$sffield] 	= $value;			
		}
		return $result;
	}
	
}