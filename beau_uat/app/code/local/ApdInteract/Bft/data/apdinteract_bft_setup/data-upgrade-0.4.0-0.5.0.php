<?php

Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);

$installer = $this;
$installer->startSetup();

$wheelsCategory = Mage::getModel('catalog/category')->load(41);

$wheels = $wheelsCategory->getProductCollection();

foreach($wheels as $wheel) {
	$changed = false;
	foreach($wheel->getProductOptionsCollection() as $option) {
                $option_name = trim(strtolower($option->getTitle()));
		if( strpos($option_name, 'road hazard') !== false) {
			$option->delete();
			$changed = true;
		}
	}

	if($changed) {
		$wheel->save();
	}
}


$installer->endSetup();