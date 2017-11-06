<?php

/**
 * Delete slow_ware attribute - See BCC-328
 *
 * Modified by Jagdeep :: BFT-2236 Fixed conflict after merged ecom into bcc.
 *
 */
$installer = $this;
$setup = new Mage_Catalog_Model_Resource_Setup('core_setup');
$installer->startSetup();

$attributeCode = 'slow_ware';
$attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', $attributeCode);

if($attribute->getId()) {
	$attribute->delete()->save();
}
$installer->endSetup();
