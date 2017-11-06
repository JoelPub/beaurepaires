<?php
require 'Mage/Checkout/controllers/OnepageController.php';
class ApdInteract_Checkout_OnepageController extends Mage_Checkout_OnepageController
{
    public function saveBillingAction()
    {
        if (!Mage::helper('apdinteract_checkout')->getHideShipping()){
            parent::saveBillingAction();
            return;
        }


        if ($this->isFormkeyValidationOnCheckoutEnabled() && !$this->_validateFormKey()) {
            return;
        }


        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('billing', array());
            $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
            $data['use_for_shipping'] = 1;

            if (isset($data['email'])) {
                $data['email'] = trim($data['email']);
            }
            $result = $this->getOnepage()->saveBilling($data, $customerAddressId);

            if (!isset($result['error'])) {
                /* check quote for virtual */
                if ($this->getOnepage()->getQuote()->isVirtual()) {
                    $result['goto_section'] = 'payment';
                    $result['update_section'] = array(
                        'name' => 'payment-method',
                        'html' => $this->_getPaymentMethodsHtml()
                    );
                } elseif (isset($data['use_for_shipping']) && $data['use_for_shipping'] == 1) {
                    //add default shipping method
                    $data = Mage::helper('apdinteract_checkout')->getDefaultShippingMethod();
                    $result = $this->getOnepage()->saveShippingMethod($data);
                    $this->getOnepage()->getQuote()->save();
                    /*
                    $result will have erro data if shipping method is empty
                    */
                    if(!$result) {
                        Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method',
                            array('request'=>$this->getRequest(),
                                'quote'=>$this->getOnepage()->getQuote()));
                        $this->getOnepage()->getQuote()->collectTotals();
                        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

                        $grandTotal = $this->getOnepage()->getQuote()->getGrandTotal();
                        if($grandTotal == 0){
                            $defaultMethod = array('method'=>'free');
                            $result = $this->getOnepage()->savePayment($defaultMethod);
                            parent::loadLayout('checkout_onepage_review');
                            $result['goto_section'] = 'review';
                            $result['update_section'] = array(
                                'name' => 'review',
                                'html' => $this->_getReviewHtml()
                            );

                        }else{
                            $result['goto_section'] = 'payment';
                            $result['update_section'] = array(
                                'name' => 'payment-method',
                                'html' => $this->_getPaymentMethodsHtml()
                            );
                        }

                    }


                    $result['allow_sections'] = array('shipping');
                    $result['duplicateBillingInfo'] = 'true';
                } else {
                    $result['goto_section'] = 'shipping';
                }
            }

            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }
    public function saveShippingAction()
    {
        if (!Mage::helper('apdinteract_checkout')->getHideShipping()){
            parent::saveShippingAction();
            return;
        }

        if ($this->isFormkeyValidationOnCheckoutEnabled() && !$this->_validateFormKey()) {
            return;
        }

        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping', array());
            $customerAddressId = $this->getRequest()->getPost('shipping_address_id', false);
            $result = $this->getOnepage()->saveShipping($data, $customerAddressId);

            $data = Mage::helper('apdinteract_checkout')->getDefaultShippingMethod();
            $result = $this->getOnepage()->saveShippingMethod($data);
            $this->getOnepage()->getQuote()->save();

            if (!isset($result['error'])) {
                $result['goto_section'] = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );
            }
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }
}