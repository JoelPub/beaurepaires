<?php
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();						


$setup->addAttribute('catalog_product', 'rim_diameter_configurable', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'label'             => 'Rim Diameter Configurable',
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
									 'optionone' => array('13'),
									 'optiontwo' => array('14'),
									 'optionthree' => array('15'),
									 'optionfour' => array('16'),
									 'optionfive' => array('17'),
								     'optionsix' => array('18'),
									 'optionseven' => array('19'),
									 'optioneight' => array('20'),
									 'optionnine' => array('21'),
									 'optionten' => array('22'),
									 'optioneleven' => array('23'),
									 'optiontwelve' => array('24'),
				)
		),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));



$setup->addAttribute('catalog_product', 'manufacturing_process', array(
		'group'     	=> 'Wheel and Tyres Attributes',
		'input'         => 'text',
		'type'          => 'text',
		'label'         => 'Manufacturing Process',
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

$arg_attribute = 'brand';
$brand = array('OZ RACING','CSA','SSW','PDW','KING WHEELS','BBS','Adela','Speedy Wheels','TSW','BLACK RHINO','Pro Comp','EXIDE');

$attr_model = Mage::getModel('catalog/resource_eav_attribute');
$attr = $attr_model->loadByCode('catalog_product', $arg_attribute);
$attr_id = $attr->getAttributeId();

$option['attribute_id'] = $attr_id;
for($iCount=0;$iCount<sizeof($brand);$iCount++){
	$option['value']['option'.$iCount][0] = $brand[$iCount];
}
$setup->addAttributeOption($option);


$installer->endSetup();