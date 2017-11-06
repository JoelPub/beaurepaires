<?php
$installer = $this;
Mage::app()->getStore()->setId(Mage_Core_Model_App::ADMIN_STORE_ID);
$installer->startSetup();

$collection = Mage::getModel('catalog/category')->load(41)
->getProductCollection()
->addAttributeToSelect('*')
->addAttributeToFilter('status', 1)
->addAttributeToFilter('type_id', 'configurable')
;

// Delete existing custom options Wheel Alignment and Suspension before adding Road Hazard Warranty (per tyre).
foreach ($collection as $product) {
// $categorization = $product->getAttributeText('consumer_categorization');
// $prod = Mage::getModel('catalog/product')->load($product->getId());
// $categoryId = $product->getCategoryIds();
// $customOptions = $prod->getOptions();


$changed = false;
foreach ($product->getProductOptionsCollection() as $option) {

if (($option->getTitle() == '4WD Premium Wheel Alignment')) {
$option->delete();
$changed = true;
}
}
if ($changed) {
$product->save();
}
}

$installer->endSetup();