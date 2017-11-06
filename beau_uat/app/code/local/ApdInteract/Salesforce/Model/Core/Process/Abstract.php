<?php
/**
 * Abstract class for all process
 * 
 * 
 * @category    ApdInteract
 * @package		ApdInteract_Salesforce
 * @author		Haihao Yan
 */
class ApdInteract_Salesforce_Model_Core_Process_Abstract 
extends Mage_Core_Model_Abstract
{	
	/**
	 * 
	 * @var array
	 */
	protected $result;
	
	/**
	 * 
	 * @var array
	 */
	protected $input;
	
	public function __construct($params = array()) {
		$this->input = $params;
		$this->result = array();
	}
	
	/**
	 * 
	 * @return ApdInteract_Salesforce_Model_Core_Process_Abstract
	 */
	public function process() {
		return $this;
	}

	/**
	 * 
	 * @return array
	 */
	public function getResult(){
		return $this->result;
	}
}