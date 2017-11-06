<?php
/*
 * Additional 2 option values for Commercial Batteries: Heavy Commercial and Industrial
 */ 

$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

$arg_attribute = 'commercial_categorization';
$batteryCommercial = array('Heavy Commercial','Industrial');

$attr_model = Mage::getModel('catalog/resource_eav_attribute');
$attr = $attr_model->loadByCode('catalog_product', $arg_attribute);
$attr_id = $attr->getAttributeId();

$option['attribute_id'] = $attr_id;
for($iCount=0;$iCount<sizeof($batteryCommercial);$iCount++){
	$option['value']['option'.$iCount][0] = $batteryCommercial[$iCount];
}
$setup->addAttributeOption($option);


$installer->endSetup();