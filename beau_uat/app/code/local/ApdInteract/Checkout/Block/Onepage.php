<?php
class ApdInteract_Checkout_Block_Onepage extends Mage_Checkout_Block_Onepage 
{
    protected $excludedMethods = array('shipping_method');

    protected function _getStepCodes()
    {

        $grandTotal = Mage::getModel('checkout/session')->getQuote()->getGrandTotal();
        if($grandTotal <= 0){
            $this->excludedMethods[] = 'payment';
        }

        if (!Mage::helper('apdinteract_checkout')->getHideShipping()){
            return parent::_getStepCodes();
        }
        return array_diff(parent::_getStepCodes(), $this->excludedMethods);
    }
}