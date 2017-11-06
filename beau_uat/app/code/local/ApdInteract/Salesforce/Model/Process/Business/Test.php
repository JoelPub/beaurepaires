<?php
class ApdInteract_Salesforce_Model_Process_Business_Test 
extends ApdInteract_Salesforce_Model_Core_Process_Abstract {
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ApdInteract_Salesforce_Model_Core_Process_Abstract::process()
	 */
	public function process() {
		//$model = Mage::getModel("customer/customer")->load(20658);
		//var_dump($model->getAddresses());
		//$this->getSchema("customer");
		$this->getSchema("customer_address");
		//$this->getSchema("customer_vehicle");
		return $this;
	}
	
	protected function getSchema($type) {
		$entity = Mage::helper("apdinteract_salesforce/reader_$type")->load()->getFirstItem();
		var_dump($entity->getData());
	}
	
	protected function testReader($type){
		$entities = Mage::helper("apdinteract_salesforce/reader_$type")->load();
		foreach($entities as $entity) {
			var_dump($entity->getId());
		}
	}
	
	protected function testDictionarySave() {
		$sid = "00128000012iC0z";
		$dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
		$customer = Mage::getModel("customer/customer")->load(20678);
		$collection = $dictionary->getCollection();		
		$instance = $collection->getDictionaryByModel($customer);
		$instance->saveDictionary($customer, $sid);
		var_dump($instance->getData());
	}
}