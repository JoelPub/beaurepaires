<?php

$installer = $this;
$installer->startSetup();

$attributes = array(
	'feature_6' => 'Feature 6',
);

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
