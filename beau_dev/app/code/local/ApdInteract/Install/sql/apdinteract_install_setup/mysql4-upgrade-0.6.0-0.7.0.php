<?php
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();		

$setup->addAttribute('catalog_product', 'model', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Model',
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

$setup->addAttribute('catalog_product', 'finish', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Finish',
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

$setup->addAttribute('catalog_product', 'superior_braking_grip', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'label'             => 'Superior Braking & Grip',
		'type'              => 'varchar',
		'input'             => 'select',
		'backend'           => 'eav/entity_attribute_backend_array',
		'frontend'          => '',
		'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		'visible'           => true,
		'required'          => false,
		'user_defined'      => true,
		'searchable'        => false,
		'filterable'        => true,
		'comparable'        => false,
		'option'            => array ('value' =>
				array(
						'optionone' => array('0'),
						'optiontwo' => array('1'),
						'optionthree' => array('2'),
						'optionfour' => array('3'),
				)
		),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));

$setup->addAttribute('catalog_product', 'sports_performance_handling', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'label'             => 'Sports Performance & Handling',
		'type'              => 'varchar',
		'input'             => 'select',
		'backend'           => 'eav/entity_attribute_backend_array',
		'frontend'          => '',
		'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		'visible'           => true,
		'required'          => false,
		'user_defined'      => true,
		'searchable'        => false,
		'filterable'        => true,
		'comparable'        => false,
		'option'            => array ('value' =>
				array(
						'optionone' => array('0'),
						'optiontwo' => array('1'),
						'optionthree' => array('2'),
						'optionfour' => array('3'),
				)
		),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));

$setup->addAttribute('catalog_product', 'quiet_comfort', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'label'             => 'Quiet & Comfort',
		'type'              => 'varchar',
		'input'             => 'select',
		'backend'           => 'eav/entity_attribute_backend_array',
		'frontend'          => '',
		'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		'visible'           => true,
		'required'          => false,
		'user_defined'      => true,
		'searchable'        => false,
		'filterable'        => true,
		'comparable'        => false,
		'option'            => array ('value' =>
				array(
						'optionone' => array('0'),
						'optiontwo' => array('1'),
						'optionthree' => array('2'),
						'optionfour' => array('3'),
				)
		),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));

$setup->addAttribute('catalog_product', 'slow_ware', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Slow Ware',
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

$setup->addAttribute('catalog_product', 'value', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Value',
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


$setup->addAttribute('catalog_product', 'performance', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Performance',
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


$setup->addAttribute('catalog_product', 'cycling', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Cycling',
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

$setup->addAttribute('catalog_product', 'speed_load_rating', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Speed Load Rating',
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

$setup->addAttribute('catalog_product', 'rim_diameter', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Rim Diameter',
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

$setup->addAttribute('catalog_product', 'rim_width', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Rim Width',
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

$setup->addAttribute('catalog_product', 'studs', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Studs',
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

$setup->addAttribute('catalog_product', 'studs_alternate', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Studs Alternate',
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

$setup->addAttribute('catalog_product', 'pcd', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'PCD',
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

$setup->addAttribute('catalog_product', 'pcd_alternate', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'PCD Alternate',
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

$setup->addAttribute('catalog_product', 'offset', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Offset',
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

$setup->addAttribute('catalog_product', 'hub_diameter', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Hub Diameter',
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

$setup->addAttribute('catalog_product', 'taper', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Taper',
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

$setup->addAttribute('catalog_product', 'caliper_profile', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Caliper Profile',
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

$setup->addAttribute('catalog_product', 'wheel_construction', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Wheel Construction',
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

$setup->addAttribute('catalog_product', 'mwl', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'MWL',
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

$setup->addAttribute('catalog_product', 'comment', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Comment',
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

$setup->addAttribute('catalog_product', 'run_flat', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'select',
		'type'          => 'int',
		'label'         => 'Run Flat',
		'default'           => 0,
		'source' => 'eav/entity_attribute_source_boolean',
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

$setup->addAttribute('catalog_product', 'categorization', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'label'             => 'Categorization',
		'type'              => 'varchar',
		'input'             => 'select',
		'backend'           => 'eav/entity_attribute_backend_array',
		'frontend'          => '',
		'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		'visible'           => true,
		'required'          => true,
		'user_defined'      => true,
		'searchable'        => false,
		'filterable'        => true,
		'comparable'        => false,
		'option'            => array ('value' =>
				array(
						'optionone' => array('PASSENGER'),
						'optiontwo' => array('4x4'),
						'optionthree' => array('LIGHT TRUCK'),
						'optionfour' => array('SPORTS'),
						'optionfive' => array('PASS HP'),
				)
		),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));

$setup->addAttribute('catalog_product', 'style', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Style',
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