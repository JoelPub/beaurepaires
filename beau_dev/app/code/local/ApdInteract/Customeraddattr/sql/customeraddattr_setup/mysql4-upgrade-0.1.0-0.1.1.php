<?php

$installer = $this;
 
$installer->startSetup();

 
$installer->addAttribute('customer_address', 'business_phone', array(
    'label'             => 'Business Phone',
    'type'              => 'varchar',
    'input'             => 'text',
    'position'          => 180,
    'visible'           => true,
    'required'          => false,
    'is_user_defined'   => true,
));

Mage::getSingleton('eav/config')
    ->getAttribute('customer_address', 'business_phone')
    ->setData('used_in_forms', array('customer_register_address','customer_address_edit','adminhtml_customer_address'))
    ->save();
 
$installer->endSetup();

	 