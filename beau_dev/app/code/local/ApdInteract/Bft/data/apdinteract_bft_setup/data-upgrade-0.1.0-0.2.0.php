<?php

Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);

$installer = $this;
$installer->startSetup();

$wheelsCategory = Mage::getModel('catalog/category')->load(42);

$wheels = $wheelsCategory->getProductCollection();

foreach($wheels as $wheel) {
	$changed = false;
	foreach($wheel->getProductOptionsCollection() as $option) {
		if(trim(strtolower($option->getTitle())) == 'premium wheel alignment') {
			$option->delete();
			$changed = true;
		}
	}

	if($changed) {
		$wheel->save();
	}
}


$installer->endSetup();