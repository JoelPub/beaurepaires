<?php
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();						

$setup->addAttribute('catalog_product', 'battery_categorization', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'label'             => 'Battery',
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
						'optionone' => array('Passenger Car'),
						'optiontwo' => array('Mowing/Mobility'),
						'optionthree' => array('4WD/SUV/Light Commercial'),
						'optionfour' => array('Security System/Backup Power'),
						'optionfive' => array('Marine & Jetski'),
						'optionsix' => array('Camping/Caravan'),
						'optionseven' => array('Industrial'),
						'optioneigth' => array('Motorcycle'),
				)
		),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));

// for commercial attribute
$commercial = 'commercial';
$arg_value = 'Light Truck';

$attr_model = Mage::getModel('catalog/resource_eav_attribute');
$attr = $attr_model->loadByCode('catalog_product', $commercial);
$attr_id = $attr->getAttributeId();

$option['attribute_id'] = $attr_id;
$option['value']['lighttruck'][0] = $arg_value;

$setup->addAttributeOption($option);


// for consumer attribute
$consumer = 'consumer';
$newOptions = array('4x4','Light Truck','Sports','Pass HP');

$attr_model = Mage::getModel('catalog/resource_eav_attribute');
$attr = $attr_model->loadByCode('catalog_product', $consumer);
$attr_id = $attr->getAttributeId();

$options['attribute_id'] = $attr_id;
for($iCount=0;$iCount<sizeof($newOptions);$iCount++){
	$options['value']['option'.$iCount][0] = $newOptions[$iCount];
}
$setup->addAttributeOption($options);


$installer->endSetup();