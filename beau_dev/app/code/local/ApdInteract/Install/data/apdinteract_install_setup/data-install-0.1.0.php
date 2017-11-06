<?php
/**
 * This data upgrade script used for install configuration:
 *  + Number item of cart
 *  + Persistent shopping cart
 *  + Enable guest checkout
 *  + Enable term and condition
 */
$config = new Mage_Core_Model_Config();
$config->saveConfig('checkout/cart_link/use_qty',1,'default',0);
$config->saveConfig('persistent/options/enabled',1,'default',0);
$config->saveConfig('checkout/options/guest_checkout',1,'default',0);
$config->saveConfig('checkout/options/enable_agreements',1,'default',0);




////New tax class
//$customerTaxClass = Mage::getModel('tax/class')
//    ->setClassName('Charge account')
//    ->setClassType(Mage_Tax_Model_Class::TAX_CLASS_TYPE_CUSTOMER)
//    ->save();
//$taxId=$customerTaxClass->getId();
////New group
//$customerGroup=Mage::getModel('customer/group');
//$customerGroup->setCode('Charge account');
//$customerGroup->setTaxClassId($taxId);
//$customerGroup->save();