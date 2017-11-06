<?php
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();		

$setup->addAttribute('catalog_product', 'service_description', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'textarea',
		'type'          => 'text',
		'label'         => 'Service Description',
		'backend'       => '',
		'visible'       => 1,
		'required'		=> 0,
		'user_defined' => 1,
		'searchable' => 1,
		'filterable' => 0,
		'comparable'	=> 1,
		'visible_on_front' => 1,
		'visible_in_advanced_search'  => 0,
		'is_html_allowed_on_front' => 0,
		'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$setup->addAttribute('catalog_product', 'mac', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Mac',
		'backend'       => '',
		'visible'       => 1,
		'required'		=> 0,
		'user_defined' => 1,
		'searchable' => 1,
		'filterable' => 0,
		'comparable'	=> 1,
		'visible_on_front' => 1,
		'visible_in_advanced_search'  => 0,
		'is_html_allowed_on_front' => 0,
		'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$setup->addAttribute('catalog_product', 'predecessor_mac', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Predecessor Mac',
		'backend'       => '',
		'visible'       => 1,
		'required'		=> 0,
		'user_defined' => 1,
		'searchable' => 1,
		'filterable' => 0,
		'comparable'	=> 1,
		'visible_on_front' => 1,
		'visible_in_advanced_search'  => 0,
		'is_html_allowed_on_front' => 0,
		'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$installer->endSetup();