<?php
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
try {
    $product = Mage::getModel('catalog/product');
    $product->setSku("tyresfrom399-campaign");
    $product->setName("Tyres From 399 Campaign");
    $product->setDescription("Tyres From 399 Campaign");
    $product->setShortDescription("Tyres From 399 Campaign");
    $product->setPrice(0.00);
    $product->setTypeId('simple');
    $product->setAttributeSetId(4); //attribute set id
    $product->setCategoryIds(array(44)); // category id
    $product->setWeight(1.0);
    $product->setTaxClassId(0); // taxable goods
    $product->setVisibility(3); // catalog, search
    $product->setStatus(1); // enabled
    // assign product to the default website
    $product->setWebsiteIds(array(1));
    $product->setStoreId(0);
    $product->save();
    
    $new_id = $product->getId();
    
    Mage::getConfig()->saveConfig('booking_date/date/tyresfrom399', $new_id, 'default', 0);
    
}catch(Exception $e) {
    Mage::log($e->getMessage());   
}
