<?php
class ApdInteract_InnovationTiles_Model_Validate extends Mage_Core_Model_Config_Data {
	
	public function save(){
		
		$blockValue = $this->getValue();
		
		$block = Mage::getModel('cms/block')->getCollection()
			->addFieldToSelect('*')
			->addFieldToFilter('identifier', $blockValue)
			->getFirstItem();
		if(!$block->getId()){
			Mage::throwException($blockValue .  ' Identifier does not exist');
		}
		
		return parent::save();
	}
	
}