<?php

class ApdInteract_Headings_Block_Onepage_Payment extends Mage_Checkout_Block_Onepage_Payment
{
    protected function _construct()
    {
        $this->getCheckout()->setStepData('payment', array(
            'label'     => Mage::getStoreConfig('headings/headings/apdinteract_payment'),
            'is_show'   => $this->isShow()
        ));
        #parent::_construct();
    }

}
