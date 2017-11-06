<?php
require '../app/Mage.php';

Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

// Need to init session in command line scripts to avoid errors if sessions are hit later.
$session = Mage::getModel('core/session');

$products = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('id')->addAttributeToSelect('name')->addAttributeToSelect('brand')->addAttributeToSelect('image')->addAttributeToFilter('type_id', 'configurable');
$existing_keys = array();


foreach ($products as $product) {
    if ($product->getImage() == 'no_selection' || $product->getImage() == '') {
        echo 'Product: ' . $product->getName() . "\xA";
        Mage::dispatchEvent('catalog_product_save_after', array(
            'product' => $product
        ));
    }
}