<?php
$setup = new Mage_Eav_Model_Entity_Setup('core_setup'); 
$setup->removeAttribute('customer','email_special_offers');
$setup->removeAttribute('customer','email_product_news');
$setup->addAttribute('customer', 'email_special_offers', array(
		'label'             => 'Subscribe to Special Offers and Rewards',
		'type'              => 'varchar',
		'input'             => 'select',
		'global'            => true,
		'visible'           => true,
		'source'            => 'eav/entity_attribute_source_boolean',
		'required'          => false,
		'user_defined'      => false,
		'option'            => array ('yes','no'),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));

 
$setup->addAttribute('customer', 'email_product_news', array(
		'label'             => 'Subscribe to Product News',
		'type'              => 'varchar',
		'input'             => 'select',
		'global'            => true,
		'visible'           => true, 
		'source'            => 'eav/entity_attribute_source_boolean',
		'required'          => false,
		'user_defined'      => false,
		'option'            => array ('yes','no'),
		'visible_on_front'  => false,
		'visible_in_advanced_search' => false,
		'unique'            => false
));


$usedInCustomerAddressForms = array(
    'adminhtml_customer',
);
 
$attribute = Mage::getSingleton('eav/config')->getAttribute('customer','email_special_offers');
$attribute->setData('used_in_forms', $usedInCustomerAddressForms);
$attribute->save();

$attribute = Mage::getSingleton('eav/config')->getAttribute('customer','email_product_news');
$attribute->setData('used_in_forms', $usedInCustomerAddressForms);
$attribute->save();

$setup->endSetup();