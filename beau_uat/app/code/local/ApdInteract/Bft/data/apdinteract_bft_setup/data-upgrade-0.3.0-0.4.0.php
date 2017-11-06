<?php

$installer = $this;
Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
$installer->startSetup();

//Add simple products
$_skus = array(
    array('AS_6639996', 'Wheel Alignment', '85.00'),
    array('AS_6540008', 'Premium Wheel Alignment (SUV/4WD) ', '95.00')
);

foreach ($_skus as $sku):
    $road_hazard = Mage::getModel('catalog/product');
    $_productId = $road_hazard->getIdBySku($_sku);
    if (empty($_productId)):

        // Create Wheel Alignment as Simple product   
        $road_hazard
                ->setStoreId(1)
                ->setWebsiteIds(array(1))
                ->setAttributeSetId(4) //ID of a attribute set named 'default'
                ->setTypeId('simple')
                ->setCreatedAt(strtotime('now'))
                ->setUpdatedAt(strtotime('now'))
                ->setSku($sku[0])
                ->setName($sku[1])
                ->setStatus(1)
                ->setTaxClassId(0) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
                ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE) //not visible
                ->setPrice($sku[2])
                ->setStockData(array(
                    'use_config_manage_stock' => 0, //'Use config settings' checkbox
                    'manage_stock' => 0, //manage stock
                    'min_sale_qty' => 1, //Minimum Qty Allowed in Shopping Cart
                    'max_sale_qty' => 1000, //Maximum Qty Allowed in Shopping Cart
                    'is_in_stock' => 1, //Stock Availability
                    'qty' => 0 //qty
                        )
        );

        $road_hazard->save();
    endif;
endforeach;


$installer->endSetup();