<?php
class ApdInteract_Rel_Model_Convert_Adapter_Url extends Mage_Dataflow_Model_Convert_Adapter_Abstract
{
	protected $_canonical;
	
	public function load() {
		// you have to create this method, enforced by Mage_Dataflow_Model_Convert_Adapter_Interface
	}
	
	public function save() {
		// you have to create this method, enforced by Mage_Dataflow_Model_Convert_Adapter_Interface
	}
	
    public function saveRow(array $importData)
    {
    	$canonical = $this->_getCanonicalModel();
    
    	if (empty($importData['url'])) {
    		$message = Mage::helper('catalog')->__('Skip import row, required field "%s" not defined', 'url');
    		Mage::throwException($message);
    	}

    	$canonical->setUrlId($importData['url_id']);
    	$canonical->setUrl($importData['url']);
    	$canonical->setNewCanonicalUrl($importData['new_canonical_url']);
    	$canonical->save();
    
    	return true;
    
    }
    
    private function _getCanonicalModel()
    {
    	if (!isset($this->_canonical)) {
    		$canonicalModel = Mage::getModel('apdinteract_rel/canonical');
    		$this->_canonical = Mage::objects()->save($canonicalModel);
    	}
    	return Mage::objects()->load($this->_canonical);
    }
    

}

