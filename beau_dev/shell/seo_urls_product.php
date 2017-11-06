<?php

require '../app/Mage.php';
Mage::app();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$products = Mage::getModel('catalog/product')
        ->getCollection()
        ->addAttributeToSelect('id')
        ->addAttributeToSelect('name')
        ->addAttributeToSelect('brand')
        ->addAttributeToSelect('brand_value')
        ->addAttributeToSelect('url_key')
        ->addAttributeToSelect('sku')
        ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
        ->addAttributeToSort('type_id', 'ASC')
        ;

$existing_keys = array();

foreach ($products as $product) {
    echo 'Product: ' . $product->getId() . ' - ' . $product->getName() . "\xA";

    if (Mage::app()->isSingleStoreMode()) {
        $product->setWebsiteIds(array(Mage::app()->getStore(true)->getWebsite()->getId()));
    }

    $url = $product->getName();
    
    if (!in_array($url, $existing_keys)) {
        $existing_keys[$url] = $url;
        try {
            $product->save();
        } catch (exception $e) {
            echo $e->getMessage() . "\xA"; 
        }
    }
}
