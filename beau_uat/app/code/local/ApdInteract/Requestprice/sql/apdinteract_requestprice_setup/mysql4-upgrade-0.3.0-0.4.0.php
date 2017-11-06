<?php
/** Updated values*/
$installer = $this;
$installer->startSetup();

//Translation -  required to be able to use silverpop template
Mage::getConfig()->saveConfig('design/theme/locale', 'beaurepaires', 'default', 0);

//Order emails
Mage::getConfig()->saveConfig('sales_email/order/template', 'sales_email_order_template', 'default', 0);
Mage::getConfig()->saveConfig('sales_email/order/guest_template', 'sales_email_order_guest_template', 'default', 0);

//Invoice emails
Mage::getConfig()->saveConfig('sales_email/invoice/template', 'sales_email_invoice_template', 'default', 0);
Mage::getConfig()->saveConfig('sales_email/invoice/guest_template', 'sales_email_invoice_guest_template', 'default', 0);

//Newsletter emails
Mage::getConfig()->saveConfig('newsletter/subscription/success_email_template', 'newsletter_subscription_success_email_template', 'default', 0);
Mage::getConfig()->saveConfig('newsletter/subscription/un_email_template', 'newsletter_subscription_un_email_template', 'default', 0);
Mage::getConfig()->saveConfig('newsletter/subscription/confirm_email_template', 'newsletter_subscription_confirm_email_template', 'default', 0);

//Customer Forgot password emails
Mage::getConfig()->saveConfig('customer/password/forgot_email_template', 'customer_password_forgot_email_template', 'default', 0);
Mage::getConfig()->saveConfig('customer/password/remind_email_template', 'customer_password_remind_email_template', 'default', 0);

//Customer emails
Mage::getConfig()->saveConfig('customer/create_account/email_template', 'customer_create_account_email_template', 'default', 0);
Mage::getConfig()->saveConfig('customer/create_account/email_confirmation_template', 'customer_create_account_email_confirmation_template', 'default', 0);
Mage::getConfig()->saveConfig('customer/create_account/email_confirmed_template', 'customer_create_account_email_confirmed_template', 'default', 0);

//Contact Us email
Mage::getConfig()->saveConfig('contacts/email/email_template', 'contacts_email_email_template', 'default', 0);

$this->endSetup();