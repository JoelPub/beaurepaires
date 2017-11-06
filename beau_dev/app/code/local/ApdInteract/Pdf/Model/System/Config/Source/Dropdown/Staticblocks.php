<?php

class ApdInteract_Pdf_Model_System_Config_Source_Dropdown_Staticblocks extends Mage_Core_Model_Abstract
{
	
	public function toOptionArray()
	{

		$blockList[] = array("value" => "", "label" => " ");
		$collection = Mage::getModel('cms/block')->getCollection()->addStoreFilter(1);

		foreach ($collection as $block){
			if(!empty($block->getIdentifier())){
				$blockList[] = array(
					'value' => $block->getIdentifier(),
					'label' => $block->getTitle(),
				);
			}

		}

		return $blockList;
	}
	
}