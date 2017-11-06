<?php


class ApdInteract_Order_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getCustomOptionPrice($value) {
        $resource = Mage::getSingleton('core/resource');
        $connection = $resource->getConnection('read');
        $optionvaluePrice = $connection->fetchRow(
                sprintf('SELECT price FROM %1$s WHERE option_type_id = %2$d', $resource->getTableName('catalog/product_option_type_price'), $value
                )
        );

        if ($optionvaluePrice['price'] > 0) {
            $price = $optionvaluePrice['price'];
            return $price;
        } else {
            return false;
        }
    }


    public function hasPrice($value) {
        $hasPrice = $this->getCustomOptionPrice($value);
        if ($hasPrice) {
            return true;
        }
    }

    public function getVirStatus($orderid) {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $consumer = $readConnection->fetchOne("SELECT status FROM apdinteract_vir_order WHERE ordernumber ='" . $orderid . "'");
        $commercial = $readConnection->fetchOne("SELECT status FROM apdinteract_vir_ordercommercial WHERE ordernumber ='" . $orderid . "'");

        return $consumer . $commercial;
    }

    public function connectCurl($url, $post) {

        $verbose_logging = true;
        if ($verbose_logging) {
            Mage::log($url, null, 'add_to_calendar.log');
            Mage::log($post, null, 'add_to_calendar.log');
        }

        // new HTTP request to some HTTP address

        $client = new Zend_Http_Client($url);

        // set some parameters

        $client->setParameterPost('data', $post);

        // POST request

        $response = $client->request(Zend_Http_Client::POST);
        if ($verbose_logging) {
            Mage::log(print_r($response, true), null, 'add_to_calendar.log');
        }

        if ($response->getStatus() == 200) {
            return $response->getBody();
        } else {
            return $response->getStatus() . ": " . $response->getMessage();
        }
    }

    public function getMyOrders() {
        $customerData = Mage::getSingleton('customer/session')->getCustomer();
        $customerId = $customerData->getId();
        $collection = Mage::getModel('sales/order')
                ->getCollection()
//                ->addFieldToFilter('online_flag', 1)  need to show both online and costar order
                ->addFieldToFilter('customer_id', $customerId)
                ->setOrder('created_at', 'DESC');
        return $collection;
    }
    
    public function getOrderInfo($order) {
        //$order = Mage::getModel('sales/order')->load($order_id);
        $items = "Web Order #".$order->getIncrementId()."\n";
        
        $allItems = $order->getAllItems();
        $itemArray = $skuArray = array();
        $price = 0;
        $configurablePriceMap = array();
        
        foreach ($allItems as $item) {
            if(strtolower($item->getProductType()) == 'simple') 
            {
                if(!is_null($item->getParentItemId())) {
                    $price = $item->getParentItem()->getRowTotalInclTax();
                } else {
                    $price = $item->getRowTotalInclTax();
                }

                $price = Mage::helper('core')->currency($price, true, false);

                $size = $item->getProduct()->getAttributeText('size');

                if($size=='')
                    $size = $item->getProduct()->getAttributeText('rim_diameter_configurable');
                
                if($size=='')
                    $size = 'N/A';

                $items .= $item->getName().". SKU ".$item->getSku().". Size ".$size." .Qty ".intval($item->getQtyOrdered()).". ".$price.".\n";
            }
        }
                
        return $items;
        
    }
        
}
