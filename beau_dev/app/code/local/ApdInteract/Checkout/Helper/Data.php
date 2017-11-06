<?php

class ApdInteract_Checkout_Helper_Data extends Mage_Core_Helper_Abstract
{
    
    const XML_HIDE_SHIPPING_PATH = 'checkout/options/hide_shipping';
    const XML_DEFAULT_SHIPPING_PATH = 'checkout/options/default_shipping';
    
    private $_quoteTotals;
    
    public function getHideShipping()
    {
        if (!Mage::getStoreConfigFlag(self::XML_HIDE_SHIPPING_PATH)) {
            return false;
        }
        if (!$this->getDefaultShippingMethod()) {
            return false;
        }
        return true;
    }
    
    public function getDefaultShippingMethod()
    {
        return Mage::getStoreConfig(self::XML_DEFAULT_SHIPPING_PATH);
    }
    
    public function getPriceInfo()
    {
        return Mage::getStoreConfig('add_ons/cart_page/price_info');
    }
    
    public function getSafetyInfo()
    {
        return Mage::getStoreConfig('add_ons/cart_page/safety_info');
    }
    
    public function getPaymentLogos($index)
    {
        $checkImage = Mage::getStoreConfig('add_ons/payment_logos/logo_' . $index);
        
        if ($checkImage) {
            return Mage::getBaseUrl('media') . 'theme/' . Mage::getStoreConfig('add_ons/payment_logos/logo_' . $index);
        }
    }
    
    public function getPaymentLogoTitles($index)
    {
        $title = Mage::getStoreConfig('add_ons/payment_logos/logo_' . $index .'_title');
        
        return $title;
    }
    
    public function getPaymentGatewayLogo()
    {
        $checkImage = Mage::getStoreConfig('add_ons/checkout_page/payment_logo');
        
        if ($checkImage) {
            return Mage::getBaseUrl('media') . 'theme/' . Mage::getStoreConfig('add_ons/checkout_page/payment_logo');
        }
    }
    
    public function getStoreDetails($id)
    {
        $store        = Mage::getModel('storelocator/stores')->load($id);
        $storeDetails = $store->getTitle() . "<br>" . $store->getStreet() . ' ' . $store->getCity() . ' ' . $store->getRegion() . ' ' . $store->getPostalCode();
        
        return $storeDetails;
    }
    
    public function getBookingTime($time)
    {
        $bookingTime = ltrim(date('h:i a', strtotime($time)), '0');
        
        return $bookingTime;
    }
    
    private function _getQuoteTotals()
    {
        if (!isset($this->_quoteTotals)) {
            $this->_quoteTotals = Mage::getSingleton('checkout/session')->getQuote()->getTotals();
        }
        return $this->_quoteTotals;
    }
    
    public function getValue($type)
    {
        $totals = $this->_getQuoteTotals();
        
        $unformattedPrice = 0;
        if (isset($totals[$type])) {
            $unformattedPrice = $totals[$type]->getValue();
        }
        if ($unformattedPrice == 0 && $type = 'shipping') {
            return "Free";
        }
        $value = $this->_formatPrice($unformattedPrice);
        
        return $value;
    }
    
    private function _formatPrice($value)
    {
        $formattedPrice = Mage::helper('checkout')->formatPrice($value);
        
        return $formattedPrice;
    }
    
    public function hasDiscount()
    {
        return $this->_hasTotal('discount');
    }
    
    private function _hasTotal($type)
    {
        $totals = $this->_getQuoteTotals();
        return (isset($totals[$type]));
    }
    
    public function getDiscountLabel()
    {
        $appliedRuleIds = Mage::getSingleton('checkout/session')->getQuote()->getAppliedRuleIds();
        $appliedRuleIds = explode(',', $appliedRuleIds);
        $rules          = Mage::getModel('salesrule/rule')->getCollection()->addFieldToFilter('rule_id', array(
            'in' => $appliedRuleIds
        ));
        
        $code = Mage::getSingleton('checkout/session')->getQuote()->getCouponCode();
        
        $oCoupon    = Mage::getModel('salesrule/coupon')->load($code, 'code');
        $oRule      = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
        $couponrule = $oRule->getData();
        $rule_name  = (string) $couponrule['name'];
        
        
        
        $rule_desc = '';
        $i         = 0;
        foreach ($rules as $rule) {
            $name = (string) $rule['name'];
            
            
            
            if ($name != $rule_name && $name!='') {
            	if ($rule_desc != '')
                	$rule_desc .= ", ";
                	$i++;
                if (trim($rule['code']) == '') {
                    $rule_desc .= $name;
                } else {
                    $rule_desc .= $rule['code'];
                }
            }
            
            
        }
        
        
        if ($rule_desc != '')
            $rule_desc .= ", ";
        
        
        $rule_desc .= $code;
        
        return $rule_desc;
    }

    /*
     * Check whether a customer is logged in or not before showing "Create Account Using Checkout Details" CTA
     * @return bolean
     */

    public function isLoggedIn(){
        $loggedIn = Mage::getSingleton('customer/session')->isLoggedIn();
        if($loggedIn){
            return true;
        }
        return false;
    }

    /*
     * Check whether a customer has created an account during checkout before "Create Account Using Checkout Details" CTA
     * @param $id int
     * @return bolean
     */
    public function isExisting($id){
        $order = Mage::getModel('sales/order')->loadByIncrementId($id);
        $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
        $session = Mage::getSingleton('core/session');

        if($customer->getId()){
            return false;
        }
        elseif ($order->getCustomerId() === NULL){
            $session->setGuestFirstName($order->getBillingAddress()->getFirstname());
            $session->setGuestLastName($order->getBillingAddress()->getLastname());
            $session->setGuestEmail($order->getBillingAddress()->getEmail());
            return true;
        }
    }
    
    public function getCatIds($_product) {       
        $ids = $_product->getCategoryIds();
        if(in_array(41,$ids))
           $category ='tyres';     
        elseif(in_array(42,$ids))
           $category ='wheels';  
        elseif(in_array(43,$ids))
           $category ='battery';  
        
        return $category;
    }
    
}
