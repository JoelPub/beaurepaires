<?php

/*
 * BCC-202 Run Database Mass Update to clear our all $0 Tyre products
 * 
 * SD 18/05/2016
 */
$installer = $this;
Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
$installer->startSetup();	

$products = Mage::getResourceModel('catalog/product_collection')
->joinField('category_id','catalog/category_product','category_id','product_id=entity_id',null,'left')
->addAttributeToFilter('category_id', array('in' => 41))
->addAttributeToSelect('price')
->addAttributeToSelect('special_price')
->addAttributeToFilter(
            array(
                array('attribute'=> 'price','eq' => 0),
                array('attribute'=> 'special_price','eq' => 0),
             )
        )
->load();

foreach($products as $product){
	
	if (($product->getPrice() == 0) && ($product->getPrice() != null)){
		$product->setPrice('');
		$product->save();
	}
	
	if (($product->getSpecialPrice() == 0) && ($product->getSpecialPrice() != null)){
		$product->setSpecialPrice('');
		$product->save();
	}
	
}
	
$installer->endSetup();

