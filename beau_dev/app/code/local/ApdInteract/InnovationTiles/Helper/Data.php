<?php
class ApdInteract_InnovationTiles_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	public function getFirstBlockId(){
		return Mage::getStoreConfig('innovationtiles/innovationtiles_first/innovationtiles_input_first',Mage::app()->getStore());
	}
	
	public function getSecondBlockId(){
		return Mage::getStoreConfig('innovationtiles/innovationtiles_second/innovationtiles_input_second',Mage::app()->getStore());
	}
	
	public function getThirdBlockId(){
		return Mage::getStoreConfig('innovationtiles/innovationtiles_third/innovationtiles_input_third',Mage::app()->getStore());
	}
}
