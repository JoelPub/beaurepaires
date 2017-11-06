<?php
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();
$setup->addAttributeGroup('catalog_product', 'Default', 'Batteries Attributes', 1001);
$installer->addAttribute('catalog_product', 'battery_features',array(
		'attribute_set'		=> 'Default', 
		'group'				=> 'Batteries Attributes',
		'label'				=> 'Battery Features',
		'type'              => 'varchar',
		'input'             => 'multiselect',
		'backend'           => 'eav/entity_attribute_backend_array',
		'frontend'          => '',
		'source'            => '',
		'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
		'visible'           => true,
		'required'          => false,
		'is_user_defined'   => true,
		'searchable'        => false,
		'filterable'        => false,
		'comparable'        => false,
		'option'            => array (
				'value' => array('optionone' => array('Durability'),
								'optiontwo' => array('Mileage'),
								'optionthree' => array('Performance'),
								'optionfour' => array('Cycling'),
						)
		),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));

$installer->endSetup();