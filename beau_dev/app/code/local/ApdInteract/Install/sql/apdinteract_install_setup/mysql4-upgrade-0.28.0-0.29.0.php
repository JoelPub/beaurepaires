<?php

/*
 * deleted custom options with title Wheel Alignment (per wheel)
 * 
 * SD 11/27/2015
 */
$installer = $this;
Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
$installer->startSetup();	

$products = Mage::getModel('catalog/product')
->getCollection()
->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
->addAttributeToSelect('*')
->addAttributeToFilter('category_id', array('eq' => 41));
foreach($products as $product){
	$prod = Mage::getModel('catalog/product')->load($product->getId());
	$customOptions = $prod->getOptions();

	foreach ($customOptions as $option) {

		if(($option->getTitle() == 'Wheel Alignment (per wheel)')){
			$option->delete();
		}
	}
	$prod->save();
}
	
$installer->endSetup();

