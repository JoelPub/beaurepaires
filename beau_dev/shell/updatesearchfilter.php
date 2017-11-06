<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../app/Mage.php';

Mage::app();

$products = Mage::getModel('catalog/category')->load(41)
 ->getProductCollection()
 ->addAttributeToSelect('*') // add all attributes - optional
 ->addAttributeToFilter('status', 1) // enabled
 ->addAttributeToFilter('visibility', 4); //visibility in catalog,search
$i = 0;
foreach($products as $product):
    $i++;
    $childProducts = Mage::getModel('catalog/product_type_configurable')                        
                ->getUsedProductCollection($product)
                ->addAttributeToFilter('status', 1) // enabled
                ->addAttributeToSelect('*');
    $searchFilter = "";            
    foreach($childProducts as $cproduct): 
        echo $product->getSku().'/'.$product->getName().'='.$cproduct->getSku().'='.$cproduct->getAttributeText('size')."\n";
        $searchFilter .=$cproduct->getAttributeText('size').",";    
    endforeach;
        echo $i.'.)'.$product->getName().'=='.$searchFilter ."\n";
        $product->setSearchfilter($searchFilter);
        $product->save();
endforeach;


//Wheels
$WheelProducts = Mage::getModel('catalog/category')->load(42)
    ->getProductCollection()
    ->addAttributeToSelect('*') // add all attributes - optional
    ->addAttributeToFilter('status', 1) // enabled
    ->addAttributeToFilter('visibility', 4); //visibility in catalog,search
$i = 0;
$logList = array();
foreach($WheelProducts as $product):

    $i++;
    $rimDiameterList = array();
    $rimDiameterData = "";

    $childProducts = Mage::getModel('catalog/product_type_configurable')
        ->getUsedProductCollection($product)
        ->addAttributeToFilter('status', 1) // enabled
        ->addAttributeToSelect('*');

    foreach($childProducts as $cProduct):
        $rimDiameterList[] = $cProduct->getAttributeText('rim_diameter_configurable');
    endforeach;

    $rimDiameterData = implode(',', $rimDiameterList);
    $logList[] = $product->getSku() . ' = ' . $rimDiameterData;

    $product->setSearchfilter($rimDiameterData);
    $product->save();

endforeach;

Mage::log($logList, null, 'searchFilter.log'); 