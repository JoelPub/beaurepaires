<?php
/**
 * ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract
 * 
 * abstract salesforce access object
 * 
 * @author Haihao
 *
 */
class ApdInteract_Salesforce_Model_Core_Salesforce_Sao_Abstract extends Mage_Core_Model_Abstract {
	
	/**
	 * 
	 * @var string
	 */
	protected $_sobjectName;
	
	/**
	 *
	 * @var ApdInteract_Salesforce_Model_Core_Salesforce_Connector_EntityConnector
	 */
	protected $_entityConnector;
	
	/**
	 * 
	 * @var object
	 */
	protected $_result;
	
	/**
	 * 
	 */
	public function __construct() {
		parent::__construct ();
		$this->_entityConnector = Mage::getModel ( "apdinteract_salesforce/core_salesforce_connector_entityConnector", array (
				"entity" => $this->_sobjectName 
		));
	}
	
	/**
	 * query salesforce
	 * 
	 * @param string $query
	 * @return array | object
	 */
	public function query($query) {
		$this->_entityConnector->authorize();
		$this->_result =  $this->_entityConnector->query($query)->getResult();
		return $this;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see Mage_Core_Model_Abstract::save()
	 */
	public function save($data, $sid = "") {
		$this->_entityConnector->authorize();
		if ($sid) {
			$this->_entityConnector->update($sid, $data);
		} else {
			$this->_entityConnector->create($data);
		}
		$this->_result = $this->_entityConnector->getResult();
		return $this;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see Mage_Core_Model_Abstract::delete()
	 */
	public function delete($sid = "") {
		$this->_entityConnector->authorize();
		if($sid) {
			$this->_entityConnector->delete($sid);
		}
		return $this;
	}
	
	/**
	 * create more multipule data in the same time
	 * 
	 * @param array $data
	 */
	public function bulkCreate($data) {
		$this->_entityConnector->authorize();
		$this->_result = $this->_entityConnector->bulkCreate($data)->getResult();
		return $this;
	}
	
	/**
	 * get result
	 * 
	 * @param array $data
	 */
	public function bulkUpdate($data) {
		$this->_entityConnector->authorize();
		$this->_result = $this->_entityConnector->bulkUpdate($data)->getResult();
		return $this;
	}
	
	/**
	 *
	 */
	public function getResult() {
		return $this->_entityConnector->getResult();
	}
	
	/**
	 * 
	 */
	public function getStatus() {
		return $this->_entityConnector->getStatus();
	}
	
	/**
	 * 
	 */
	public function getSalesforceId() {
		return property_exists($this->_result, "id") ? $this->_result->id : null;
	}
}