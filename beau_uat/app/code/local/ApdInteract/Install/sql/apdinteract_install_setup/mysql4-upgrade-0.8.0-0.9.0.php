<?php
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();		

$setup->addAttribute('catalog_product', 'segment', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'label'             => 'Segment',
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
						'optionone' => array('Budget'),
						'optiontwo' => array('Premium'),
						'optionthree' => array('Retread'),
				)
		),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));

$setup->addAttribute('catalog_product', 'cust_case', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'label'             => 'Cust Case or *A Grade',
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
						'optionone' => array(''),
						'optiontwo' => array('*A Grade'),
						'optionthree' => array('CUST CASE'),
				)
		),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));

$setup->addAttribute('catalog_product', 'position', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'label'             => 'Position',
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
						'optionone' => array('N/A'),
						'optiontwo' => array('All Position'),
						'optionthree' => array('Drive'),
						'optionfour' => array('Trailer'),
						'optionfive' => array('Steer'),
				)
		),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));


$installer->endSetup();