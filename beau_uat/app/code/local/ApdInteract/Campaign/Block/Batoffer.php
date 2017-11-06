<?php

class ApdInteract_Campaign_Block_Batoffer extends Mage_Core_Block_Template {

    public function SaveBatofferCampaign() {
        $postData = $this->getRequest()->getPost();
        $url = Mage::getStoreConfig('campaign/batoffer_campaign/campaign_url');
        $this->_createOrder($postData);
        
        Mage::app()->getResponse()->setRedirect($url);
    }

    private function _createOrder($postData) {
         try {
            
            $cart = Mage::getModel('checkout/cart');
            $cart->truncate(); 
            $cart->init();
            
            $helper = Mage::Helper('apdinteract_requestprice'); 
            $storeLocationId = $helper->getPostedValues('cartaddress-id');
            Mage::getSingleton('core/session')->setRequestStoreId($storeLocationId);
            
            $parent_product_id = Mage::getStoreConfig('booking_date/date/batoffer');
            $qty = 1; //$helper->getQty();

            $helper->addSimpleProductToCart($parent_product_id, $qty);

            $order_id = $helper->convertQuoteToOrder("batoffer");

            //return $this->_successMessageCode();
            $analytics = Mage::helper('apdwidgets')->getAnalyticsAsJson($order_id);
            return array("Success" => 1,"analytics"=>$analytics);

        } catch (Exception $e) {
            Mage::logException($e);
            return $helper->errorMessageCode();
        }            
    }

}
