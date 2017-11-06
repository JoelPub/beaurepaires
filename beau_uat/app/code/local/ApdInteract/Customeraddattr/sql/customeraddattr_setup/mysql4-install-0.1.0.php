<?php

$installer = $this;
 
$installer->startSetup();

$installer->updateAttribute(
    'customer_address',
    'telephone',
    'is_required',
    0
);
 
$installer->addAttribute('customer_address', 'mobile', array(
    'label'             => 'Mobile',
    'type'              => 'varchar',
    'input'             => 'text',
    'position'          => 180,
    'visible'           => true,
    'required'          => false,
    'is_user_defined'   => true,
));

Mage::getSingleton('eav/config')
    ->getAttribute('customer_address', 'mobile')
    ->setData('used_in_forms', array('customer_register_address','customer_address_edit','adminhtml_customer_address'))
    ->save();
 
$installer->endSetup();

	 