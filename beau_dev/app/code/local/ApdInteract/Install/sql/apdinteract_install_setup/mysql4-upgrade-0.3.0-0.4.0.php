<?php
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();						

$setup->addAttribute('catalog_product', 'overlay', array(
			 'group'     	=> 'Wheel and Tyres Attributes',
			 'label'             => 'Overlay',
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
									 'optionone' => array('New Arrival'),
									 'optiontwo' => array('On Sale'),
									 'optionthree' => array('Best Seller'),
								)
							),
			 'visible_on_front'  => false,
			 'visible_in_advanced_search' => false,
			 'unique'            => false
));


$installer->endSetup();