<?php
/** BFT-1725 set up all email templates to use default template */
$installer = $this;
$installer->startSetup();

//Translation -  required to be able to use silverpop template
Mage::getConfig()->saveConfig('design/theme/locale', 'beaurepaires', 'default', 0);

//Order emails
Mage::getConfig()->saveConfig('sales_email/order/template', '1', 'default', 0);
Mage::getConfig()->saveConfig('sales_email/order/guest_template', '1', 'default', 0);

//Invoice emails
Mage::getConfig()->saveConfig('sales_email/invoice/template', '1', 'default', 0);
Mage::getConfig()->saveConfig('sales_email/invoice/guest_template', '1', 'default', 0);

//Newsletter emails
Mage::getConfig()->saveConfig('newsletter/subscription/success_email_template', '1', 'default', 0);
Mage::getConfig()->saveConfig('newsletter/subscription/un_email_template', '1', 'default', 0);
Mage::getConfig()->saveConfig('newsletter/subscription/confirm_email_template', '1', 'default', 0);

//Customer Forgot password emails
Mage::getConfig()->saveConfig('customer/password/forgot_email_template', '1', 'default', 0);
Mage::getConfig()->saveConfig('customer/password/remind_email_template', '1', 'default', 0);

//Customer emails
Mage::getConfig()->saveConfig('customer/create_account/email_template', '1', 'default', 0);
Mage::getConfig()->saveConfig('customer/create_account/email_confirmation_template', '1', 'default', 0);
Mage::getConfig()->saveConfig('customer/create_account/email_confirmed_template', '1', 'default', 0);

//Contact Us email
Mage::getConfig()->saveConfig('contacts/email/email_template', '1', 'default', 0);

//Price request email
Mage::getConfig()->saveConfig('vir/price_request_booking/enabled', '1', 'default', 0);
Mage::getConfig()->saveConfig('vir/price_request_booking/price_request_booking_template', '1', 'default', 0);
$this->endSetup();