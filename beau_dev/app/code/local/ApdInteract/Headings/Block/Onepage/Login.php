<?php
class ApdInteract_Headings_Block_Onepage_Login extends Mage_Checkout_Block_Onepage_Login
{
    protected function _construct()
    {    	
    	
        if (!$this->isCustomerLoggedIn()) {
            $this->getCheckout()->setStepData('login', array('label'=>Mage::getStoreConfig('headings/headings/apdinteract_checkout'), 'allow'=>true));
        }
        #parent::_construct();
    }
}