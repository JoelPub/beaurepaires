<?php

/**
 * - Add new dropdown option - "Coming Soon" - See BFT-2036
 */
$installer = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

$arg_attribute = 'overlay';
$arg_value = 'Coming Soon';

$attr_model = Mage::getModel('catalog/resource_eav_attribute');
$attr = $attr_model->loadByCode('catalog_product', $arg_attribute);
$attr_id = $attr->getAttributeId();

$option['attribute_id'] = $attr_id;
$option['value']['coming_soon'][0] = $arg_value;

$installer->addAttributeOption($option);

$installer->endSetup();