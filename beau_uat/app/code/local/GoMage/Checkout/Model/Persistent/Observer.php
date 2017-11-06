<?php

/**
 * GoMage LightCheckout Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2014 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 5.7
 * @since        Class available since Release 5.7
 */
class GoMage_Checkout_Model_Persistent_Observer extends Mage_Persistent_Model_Observer
{

    /**
     * Emulate quote by persistent data
     *
     * @param Varien_Event_Observer $observer
     */
    public function emulateQuote($observer)
    {
        parent::emulateQuote($observer);
        if (( bool )Mage::helper('gomage_checkout')->getConfigData('general/enabled') == true && $this->_isShoppingCartPersist() && !Mage::getSingleton('customer/session')->isLoggedIn()) {
            /** @var $checkoutSession Mage_Checkout_Model_Session */
            $checkoutSession = Mage::getSingleton('checkout/session');
            $customer        = Mage::getModel('customer/customer');
            $checkoutSession->setCustomer($customer);
            $checkoutSession->getQuote()->setCustomer($customer);
        }
    }


}