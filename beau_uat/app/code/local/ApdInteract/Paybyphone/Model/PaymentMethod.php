<?php
class ApdInteract_Paybyphone_Model_PaymentMethod extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'paybyphone';
    protected $_isGateway = true;
    protected $_canAuthorize = true;
    protected $_canUseCheckout = true;
    public function authorize(Varien_Object $payment, $amount) {
        return $this;
    }
}