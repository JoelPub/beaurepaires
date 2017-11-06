<?php

/**
 * - Dropdown for slow_wear attribute - See BCC-313
 *
 * Modified by Jagdeep :: BFT-2236 Fixed conflict after merged ecom into bcc.
 *
 */
$installer = $this;
$setup = new Mage_Catalog_Model_Resource_Setup('core_setup');
$installer->startSetup();

$attributeCode = 'slow_wear';
$attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', $attributeCode);

if($attribute->getId()) {
	$attribute->delete()->save();
}

$setup->addAttribute('catalog_product', 'slow_wear', array(
		'group' => 'Wheel and Tyres Attributes',
		'label' => 'Slow Wear',
		'type' => 'varchar',
		'input' => 'select',
		'backend' => 'eav/entity_attribute_backend_array',
		'frontend' => '',
		'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		'visible' => true,
		'required' => false,
		'user_defined' => true,
		'searchable' => false,
		'filterable' => true,
		'comparable' => false,
		'is_configurable' => false,
		'visible_on_front' => true,
		'used_in_product_listing' => true,
		'option' => array('value' =>
			array(
				'optionzero' => array('0'),
				'optionone' => array('1'),
				'optiontwo' => array('2'),
				'optionthree' => array('3'),
				'optionfour' => array('4'),
				'optionfive' => array('5'),
				'optionsix' => array('6'),
			)
		),
		'visible_in_advanced_search' => false,
		'unique' => false
));


$installer->endSetup();
