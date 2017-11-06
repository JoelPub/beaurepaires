<?php
class ApdInteract_Headings_Block_Onepage_Billing extends Mage_Checkout_Block_Onepage_Billing
{
    
    protected function _construct()
    {
        $this->getCheckout()->setStepData('billing', array(
            'label'     => Mage::getStoreConfig('headings/headings/apdinteract_account'),
            'is_show'   => $this->isShow()
        ));

        if ($this->isCustomerLoggedIn()) {
            $this->getCheckout()->setStepData('billing', 'allow', true);
        }
        #parent::_construct();
    }

  
}
