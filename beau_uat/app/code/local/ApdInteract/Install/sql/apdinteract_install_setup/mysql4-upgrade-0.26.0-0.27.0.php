<?php

/*
 * This script will delete existing custom options: Wheel Alignment and Suspension for tyres only
 * And will create a Wheel Alignment simple product
 * Refer BFT-1360 in JIRA for more details
 *
 * SD 11/17/2015
 */
$installer = $this;
Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
$installer->startSetup();

$collection = Mage::getModel('catalog/category')->load(41)
    ->getProductCollection()
    ->addAttributeToSelect('*')
    ->addAttributeToFilter('status', 1)
    ->addAttributeToFilter('type_id', 'configurable')
    ->addAttributeToFilter('consumer_categorization', array('in' => array('1325', '1324', '1336', '1696', '1337'))); // car/tyres/4x4/passenger/light truck

// Delete existing custom options Wheel Alignment and Suspension before adding Road Hazard Warranty (per tyre).
foreach ($collection as $product) {
    // $categorization = $product->getAttributeText('consumer_categorization');
    // $prod = Mage::getModel('catalog/product')->load($product->getId());
    // $categoryId = $product->getCategoryIds();
    // $customOptions = $prod->getOptions();

    
    $changed = false;
    foreach ($product->getProductOptionsCollection() as $option) {

        if (($option->getTitle() == 'Wheel Alignment') || ($option->getTitle() == 'Suspension')) {
            $option->delete();
            $changed = true;
        }
    }
    if ($changed) {
        $product->save();
    }
}

$_sku = 'wheel-alignment';
$wheelAlignment = Mage::getModel('catalog/product');
$_productId = $wheelAlignment->getIdBySku($_sku);
if (empty($_productId)) {

    // Create Wheel Alignment as Simple product
    // $wheelAlignment = Mage::getModel('catalog/product');

    // cannot use $wheelAlignment->getId() for checking for some reason, 1 time run anyway
    $wheelAlignment
            ->setStoreId(1)
            ->setWebsiteIds(array(1))
            ->setAttributeSetId(4) //ID of a attribute set named 'default'
            ->setTypeId('simple')
            ->setCreatedAt(strtotime('now'))
            ->setUpdatedAt(strtotime('now'))
            ->setSku($_sku)
            ->setName('Wheel Alignment')
            ->setStatus(1)
            ->setTaxClassId(0) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
            ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE) //not visible
            ->setPrice(85.00)
            ->setStockData(array(
                'use_config_manage_stock' => 0, //'Use config settings' checkbox
                'manage_stock' => 0, //manage stock
                'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
                'max_sale_qty' => 1000, //Maximum Qty Allowed in Shopping Cart
                'is_in_stock' => 1, //Stock Availability
                'qty' => 0 //qty
                    )
    );

    $wheelAlignment->save();
}

$installer->endSetup();

