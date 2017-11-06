<?php

$installer = $this;
$installer->startSetup();

$attributes = array(
	'feature_1' => 'Feature 1',
	'feature_2' => 'Feature 2',
	'feature_3' => 'Feature 3',
	'feature_4' => 'Feature 4',
	'feature_5' => 'Feature 5'
);

$installer->addAttributeGroup('catalog_product', 'Default', 'Features & Benefits', 1010);	

foreach($attributes as $code => $attribute) {
	$installer->addAttribute('catalog_product', $code, array(
		'type'              => 'text',
		'label'             => $attribute,
		'input'             => 'textarea',
		'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
		'visible'           => true,
		'visible_on_front' => true,
		'required'          => false,
		'user_defined'      => true,
		'default'           => '',
		'searchable'        => false,
		'filterable'        => false,
		'comparable'        => false,
		'unique'            => false,
		'wysiwyg_enabled' => true,
		'is_html_allowed_on_front' => true,
		'group'             => 'Features & Benefits'
	));
}

$installer->endSetup();
