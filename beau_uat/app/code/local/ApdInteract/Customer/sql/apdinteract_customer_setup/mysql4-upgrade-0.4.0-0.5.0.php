<?php

$installer = $this;
$eavConfig = Mage::getSingleton('eav/config');
/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer->startSetup();

$installer->addAttribute('customer', 'costar_customer_id', array(
    'label'        => 'Costar Customer ID',
    'visible'      => 1,
    'required'     => 0,
    'position'     => 142,
    'type'         => 'varchar',
    'default'      => 0,
    'input'        => 'text',

));

$defaultUsedInForms = array(
    'adminhtml_customer',
);

$Attribute = $eavConfig->getAttribute('customer', 'costar_customer_id');
$Attribute->setData('used_in_forms', $defaultUsedInForms);
$Attribute->save();

$installer->endSetup();
