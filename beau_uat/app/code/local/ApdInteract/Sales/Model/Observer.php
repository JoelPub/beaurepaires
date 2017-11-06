<?php

class ApdInteract_Sales_Model_Observer {

    public function cleanExpiredClientQuote() {

        $lifetimes = Mage::getConfig()->getStoresConfigByPath('checkout/cart/delete_quote_after');
        foreach ($lifetimes as $storeId => $lifetime) {

            $lifetime = 86400;

            $quotes = Mage::getModel('sales/quote')->getCollection();
            /* @var $quotes Mage_Sales_Model_Mysql4_Quote_Collection */
            $quotes->addFieldToFilter('store_id', $storeId);
            $quotes->addFieldToFilter('customer_id', array('null' => true));
            $quotes->addFieldToFilter('updated_at', array('to' => date("Y-m-d h:i:s", time() - $lifetime)));
            // not converted
            $quotes->addFieldToFilter('is_active', 1);
            $quotes->walk('delete');
        }
    }

    public function cleanExpiredLoginClientQuote() {

        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $customerId = $customerData->getId();
        #Mage::log('here:'.$customerId, null, 'customerlogin.log');
        $lifetimes = Mage::getConfig()->getStoresConfigByPath('checkout/cart/delete_quote_after');
        $count = 0;
        foreach ($lifetimes as $storeId => $lifetime) {

            $lifetime = 86400;

            $quotes = Mage::getModel('sales/quote')->getCollection();
            /* @var $quotes Mage_Sales_Model_Mysql4_Quote_Collection */
            $quotes->addFieldToFilter('store_id', $storeId);
            $quotes->addFieldToFilter('updated_at', array('to' => date("Y-m-d h:i:s", time() - $lifetime)));
            $quotes->addFieldToFilter('customer_id', $customerId);
            // not converted
            $quotes->addFieldToFilter('is_active', 1);
            #Mage::log($quotes->getSelect()->__toString(), null, 'customerlogin.log');
            $count = $quotes->getSize();
            if ($count > 0) {
                Mage::getSingleton('core/session')->addNotice('<li class="alert-box warning radius">Your Cart content has expired.</li>');
            }
            $quotes->walk('delete');
        }


        if ($count <= 0) { //repopulate session values
            Mage::getSingleton('core/session')->setSuperAttr('');
            Mage::getSingleton('core/session')->setSkuOrig('');
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            $cartItems = $quote->getAllItems();
            $array = array();
            foreach ($cartItems as $item) {
                $id = $item->getId();
                $productId = $item->getProductId();
                $super_attr = array();
                $product = Mage::getModel('catalog/product')->load($productId);
                Mage::helper('apdinteract_salesrule')->compileSkus($product->getSku(), $product->getFinalPrice());
                if ($product->getSize() != '') {
                    $super_attr['attr_id'] = 180; //size
                    $super_attr['attr_val'] = $product->getSize();
                    $array[$id] = $super_attr;
                }
            }

            if (count($array) > 0) {
                Mage::getSingleton('core/session')->setSuperAttr($array);
            }
        }
    }

    public function deleteOrderInBookingCalendar(Varien_Event_Observer $observer) {

        $event = $observer->getEvent()->getCreditmemo();
        $order = $event->getOrder();

        if ($order->getAppointmentid()) {
            try {
                Mage::Helper('sync')->_deleteappointment($order->getAppointmentid());
                Mage::Helper('apdinteract_vir')->cancelRelatedVirsFromOrder($order);
            } catch (Exception $e) {
                $debug = array('appoinment_id' => $order->getAppointmentid(),
                    'increment_id' => $order->getIncrementId(),
                    'order_id' => $order->getId()
                );
                Mage::log("Appointment Not Deleted: Debug Vars:" . print_r($debug, true) . ", Exception:" . $e->getMessage(), null, "order_deletion_errors.log");
            }
        }
    }

    /**
     * Update Increment ID add store Branch Code
     * @param Varien_Event_Observer $observer
     */
    public function changeIncrementId(Varien_Event_Observer $observer) {

        $storeId = Mage::getSingleton("core/session")->getStorelocation();        
        $store = Mage::getModel('storelocator/stores')->load($storeId);

        if ($store->getCostarStoreCode()) {
            $branchCode = $store->getCostarStoreCode();
            $order = $observer->getEvent()->getOrder();

            $payment = $order->getPayment()->getMethodInstance()->getCode();
            
            if ($payment != 'paybyphone'):
                $order_id = $order->getIncrementId();
                $id = explode('-', $order_id);
                $order->setIncrementId( $id[0]. "-{$branchCode}");
                $order->save();
            endif;
        }
    }

    /**
     * Update Reserved Order ID with store Branch Code
     * @param Varien_Event_Observer $observer
     */
    public function changeReservedId(Varien_Event_Observer $observer) {
        $payment = $observer->getEvent()->getPayment();
        $post = Mage::app()->getRequest()->getPost();

        if ($post['payment']['method'] == 'paypal_express') {
            $storeId = Mage::getSingleton("core/session")->getStorelocation();
            $store = Mage::getModel('storelocator/stores')->load($storeId);

            if ($store->getCostarStoreCode()) {
                $branchCode = $store->getCostarStoreCode();
                $quote = Mage::getSingleton('checkout/session')->getQuote();
                //generate Reserved  Order ID
                $orig_order = $quote->reserveOrderId()->save();
                //override generated with store code

                $id = explode('-', $orig_order->getReservedOrderId());
                $new_id = $id[0] . "-{$branchCode}";

                $quote->setReservedOrderId($new_id);
                $quote->save();
            }
        }
    }

    /**
     * set RRP price
     * @param Varien_Event_Observer $observer
     */
    public function salesQuoteItemSetRrPrice(Varien_Event_Observer $observer)
    {
        $quoteItem = $observer->getQuoteItem();
        $product = $observer->getProduct();


        // checks for parent item
        if ($quoteItem->getParentItem()) {

            $_product = $quoteItem->getParentItem()->getProduct();
            $product = Mage::getModel('catalog/product');
            $id = Mage::getModel('catalog/product')->getResource()->getIdBySku($_product->getSku());
            if ($id) {
                $product->load($id);
                $price = $product->getPrice();
            }

            $parentItem = $quoteItem->getParentItem();
            $parentItem->setRrPrice($price);
            $parentItem->getProduct()->setIsSuperMode(true);
        }else{
            $quoteItem->setRrPrice($product->getPrice());
        }

    }

}
