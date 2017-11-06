<?php

/**
 * Salesforce data helper
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * root
     *
     * @var string
     */
    const CONFIG_ROOT = "salesforce/";

    /**
     * keys
     *
     * @var array
     */
    protected $keys = array(
        "rest" => array(
            "consumerkey",
            "consumersecret",
            "token",
            "redirect",
            "instance",
            "login",
            "username",
            "password",
            "method",
            "security_token"
        ),
        "mapping" => array(
            "configuration"
        )
            )

    ;

    /**
     * get configuration
     *
     * @param string $section        	
     * @param unknown $store        	
     * @return mixed[]|string[]|NULL[]
     */
    public function getConfig($section = "rest", $store = null) {
        $path_root = self::CONFIG_ROOT . $section . "/";
        $result = array();
        if (key_exists($section, $this->keys)) {
            $keys = $this->keys [$section];
            foreach ($keys as $key) {
                $path = $path_root . $key;
                $result [$key] = Mage::getStoreConfig($path, $store);
            }
        }
        return $result;
    }

    /**
     * save configuration
     * 
     * @param string $item        	
     * @param string $value        	
     * @param string $section        	
     * @return ApdInteract_Salesforce_Helper_Data
     */
    public function saveConfig($item, $value, $section = "rest") {
        $root = self::CONFIG_ROOT . $section . "/";
        if (key_exists($section, $this->keys)) {
            $path = $root . $item;
            Mage::getConfig()->saveConfig($path, $value);
        }
        return $this;
    }

    /**
     * clean cache
     * 
     * @return ApdInteract_Salesforce_Helper_Data
     */
    public function cleanCache() {
        Mage::getConfig()->cleanCache();
        return $this;
    }

    public function checkConnection() {
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array());
        $response = $connector->authorize();
        $status = $response->getStatus();
        if (Mage::helper('addblock')->checkSF()): //check if salesforce module is enabled                                        
        /* if ($status!= 200) :

          $session = Mage::getSingleton('core/session');
          $session->addError('Connection issue. Unable to post the review.');
          $redirectUrl = $_SERVER['HTTP_REFERER'];
          $response = Mage::app()->getFrontController()->getResponse();
          $response->setRedirect($redirectUrl);
          $response->sendResponse();
          exit();
          endif; */
        endif;
    }


    public function getLastUpdatedDateTime($class, $ifMage) {
        $dictionary = Mage::getModel("apdinteract_salesforce/updates");
        if($ifMage)
        $class = ($this->getEquivalent($class) != '') ? $this->getEquivalent($class) : $class;
        else
        $class = $class;
        
        $dateTime = $dictionary->getCollection()
                ->addFieldToFilter("entity_type", $class)
                ->setOrder('updated_at', 'DESC')
                ->getFirstItem()
                ->getData();
        
        return $dateTime['updated_at'];
    }

    public function getSFId($id, $class) {
        $class = ($this->getEquivalent($class) != '') ? $this->getEquivalent($class) : $class;
        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        $object = $dictionary->getCollection()
                ->addFieldToFilter("entity_type", $class)
                ->addFieldToFilter("entity_id", $id)
                ->getFirstItem()
                ->getData();

        return isset($object['salesforce_id']) ? $object['salesforce_id'] : 0;
    }

    public function disableFlatTables($status) {
        Mage::app()->getStore()->setConfig('catalog/frontend/flat_catalog_product', $status);
        Mage::app()->getCacheInstance()->cleanType('config');
    }

    public function addCustomerByBilling($billing_id, $store_id, $email, $id) {

        $address_data = $this->loadByBillingId($billing_id, $store_id, $email);

        try {
            if ($id <= 0):
                $customer = Mage::getModel("customer/customer");
                $customer->setWebsiteId($store_id)
                        ->setFirstname($address_data['firstname'])
                        ->setLastname($address_data['lastname'])
                        ->setEmail($email)
                        ->setPassword('password123')
                        ->setGroupId(1)
                        ->setDormantFlag(1);

                $customer->save();
                $customer_id = $customer->getId();
                $this->setCustomerAddress($customer_id, $address_data);
            else:
                $customer_id = $id;
            endif;
            return $this->createCustomerToSf($customer_id, $address_data);
        } catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
        }
    }

    public function setCustomerAddress($customer_id, $address_data) {
        $address = Mage::getModel("customer/address");
        $address->setCustomerId($customer_id)
                ->setFirstname($address_data['firstname'])
                ->setLastname($address_data['lastname'])
                ->setCountryId($address_data['country_id'])
                ->setRegionId($address_data['region_id'])
                ->setPostcode($address_data['postcode'])
                ->setCity($address_data['city'])
                ->setTelephone($address_data['telephone'])
                ->setFax($address_data['fax'])
                ->setCompany($address_data['company'])
                ->setStreet($address_data['street'])
                ->setIsDefaultBilling('1')
                ->setIsDefaultShipping('1')
                ->setSaveInAddressBook('1');

        try {
            $address->save();
        } catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
        }
    }

    public function createCustomerToSf($customer_id, $data, $booking=false) {
        
        $name = $data['firstname'] . ' ' . $data['lastname'];
        if(trim($name)=='' && $booking):            
            $name = $booking['customer_first'] . ' ' . $booking['customer_last'];
        endif;
        
       if(trim($name)=='')
           $name= "N/A";
        
        $data = array("magento_customer_id__c" => $customer_id,
            "magento_website__c" => $this->getWebsiteByStoreId($data['store_id']),
            "email__c" => $data['email'],
            "Name" => $name,
            "Dormant_Flag__c" => 1,
            "magento_customer_group__c" => 'Guest',
            "BillingStreet" => $data['street'],
            "BillingCity" => $data['city'],
            "BillingPostalCode" => $data['post_code'],
            "BillingState" => $data['region_id'],
            "BillingCountry" => $data['country_id']
        );


        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => "Account"));
        $connector->authorize();
        $result = $connector->create($data)->getResult();		

        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        $customer = Mage::getModel("customer/customer")->load($customer_id);
        if (isset($result->id) != ''):
            $dictionary->saveDictionary($customer, $result->id);
            return $result->id;
        elseif (isset($result[0]->errorCode) && $result[0]->errorCode == 'DUPLICATE_VALUE'):

            $message = explode(":", $result[0]->message);
            $dictionary->saveDictionary($customer, trim($message[3]));
            return trim($message[3]);
        endif;
    }

    public function getWebsiteByStoreId($store_id) {
        $store = Mage::getModel('core/store')->load($store_id);
        return $store->getName();
    }

    public function loadByBillingId($billing_id, $store_id = '', $email = '') {

        $billing = Mage::getModel('sales/order_address')->load($billing_id);
        $websiteId = Mage::app()->getWebsite()->getId();
        $store = Mage::app()->getStore();
        $address_data = array(
            'firstname' => $billing->getData('firstname'),
            'lastname' => $billing->getData('lastname'),
            'country_id' => $billing->getData('country_id'),
            'city' => $billing->getData('city'),
            'postcode' => $billing->getData('post_code'),
            'street' => $billing->getData('street'),
            'company' => $billing->getData('company'),
            'fax' => $billing->getData('fax'),
            'region_id' => $billing->getData('region_id'),
            'telephone' => $billing->getData('telephone'),
            'store_id' => $store_id,
            'email' => $email,
        );

        return $address_data;
    }

    public function getEquivalent($class_name) {
        $class = array('ApdInteract_Customer_Model_Customer' => 'Mage_Customer_Model_Customer', 'ApdInteract_Requestprice_Model_Sales_Order' => 'Mage_Sales_Model_Order', 'ApdInteract_Requestprice_Model_Sales_Order_Contract' => 'Mage_Sales_Model_Order_Contract');
        return isset($class[$class_name]) ? $class[$class_name] : '';
    }

    public function mapOrderDetails() {
        $orders = Mage::getModel('sales/order')->getCollection();
        $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        $before = mktime(0, 0, 0, date('m', $start), date("d") - 1, date('Y', $start));

        // calculate the first day of last month
        $first = date('Y-m-d H:i:s', $before);

        // calculate the last day of last month
        $last = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y', $start)));

        $orders->addAttributeToSelect('*');
        $orders->addFieldToFilter('updated_at', array(
            'from' => $first,
            'to' => $last,
            'date' => true
        ));

        foreach ($orders as $order):
            $order_id = $order->getId();
            ;
            $sales_order_model = 'Mage_Sales_Model_Order_Book';
            $pricebook_id = Mage::helper('apdinteract_salesforce')->getSFId($order_id, $sales_order_model);
            $this->sendOrderDetails($order_id, $pricebook_id); //order details
        endforeach;
    }

    protected function sendOrderDetails($order_id, $priceBook) {

        $order = Mage::getModel('sales/order')->load($order_id);
        $allItems = $order->getAllVisibleItems();

        $helper = Mage::helper('apdinteract_salesforce');
        foreach ($allItems as $item) {
            $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
            $order_item = Mage::getModel("sales/order_item")->load($item->getId());
            $data = array();
            $datab = array();

            if ($helper->getSFId($item->getId(), get_class($order_item)) == 0):
                $sku = $item->getData('sku');
                $_catalog = Mage::getModel('catalog/product');
                $_productId = $_catalog->getIdBySku($sku);

                $datab['UnitPrice'] = $item->getData('price_incl_tax');
                $datab['Pricebook2Id'] = $priceBook;
                $datab['Product2Id'] = $this->getProductId($_productId, $sku);
                $datab['IsActive'] = true;
                //$datab['UseStandardPrice'] = true;
                //$datab['ProductCode'] = $sku;
                //$datab['StandardPrice'] = $item->getData('price_incl_tax');
                //Zend_debug::dump($datab);

                $pricebookEntryId = $this->createPriceBookEntry($datab, $item->getId());

                $data['Magento_ID__c'] = $item->getId();

                $data['OrderId'] = Mage::helper('apdinteract_salesforce')->getSFId($order_id, get_class($order));
                //$data['ListPrice'] = $item->getData('price_incl_tax');
                $data['PricebookEntryId'] = $pricebookEntryId;
                //$data['Product2Id'] = $this->getProductId($item->getData('product_id'),$sku);               
                //$data['OriginalOrderItem'] = $this->getProductId($item->getData('product_id'),$sku);
                //$data['ProductCode'] = $sku;
                //$data['StandardPrice'] = $item->getData('price_incl_tax');
                $data['Quantity'] = $item->getData('qty_ordered');
                // $data['TotalPrice'] = $item->getData('price_incl_tax') * $item->getData('qty');
                $data['UnitPrice'] = $item->getData('price_incl_tax');



                $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => "OrderItem"));
                $connector->authorize();
                $result = $connector->create($data)->getResult();

                if (isset($result->id) != ''):
                    //echo "success";                                       
                    $dictionary->saveDictionary($order_item, $result->id);
                //return $result->id;
                elseif (isset($result[0]->errorCode) && $result[0]->errorCode == 'DUPLICATE_VALUE'):
                    $message = explode(":", $result[0]->message);
                    $dictionary->saveDictionary($order_item, trim($message[3]));
                //return $message[3];
                endif;

            endif;
        }
    }

    protected function getProductId($productId, $sku) {
        $class = get_class(Mage::getModel('catalog/product'));
        $helper = Mage::helper('apdinteract_salesforce');
        $id = $helper->getSFId($productId, $class);
        return ($id == 0) ? $this->fetchFromSF($sku) : $id;
    }

    protected function fetchFromSF($sku) {
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => "Product2"));
        $connector->authorize();
        $result = $connector->query("SELECT Id,Name from Product2 WHERE ProductCode='$sku'  LIMIT 1");
        //$result->Id.'rrrr';
        return $result->Id;
    }

    protected function createPriceBookEntry($data, $order_id) {

        $order_item = Mage::getModel("sales/order_item")->load($order_id);
        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => "PricebookEntry"));
        $connector->authorize();
        $result = $connector->create($data)->getResult();
        Zend_debug::dump($result);
        if (isset($result->id) != ''):
            $dictionary->saveDictionary($order_item, $result->id, 'Book_Entry');
            return $result->id;
        elseif (isset($result[0]->errorCode) && $result[0]->errorCode == 'DUPLICATE_VALUE'):
            $message = explode(":", $result[0]->message);
            $dictionary->saveDictionary($order, trim($message[3]), 'Book_Entry');
            return $message[3];
        endif;
    }

    protected function createPriceBook($data, $order_id) {
        $order = Mage::getModel('sales/order')->load($order_id);
        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => "Pricebook2"));
        $connector->authorize();
        $result = $connector->create($data)->getResult();
        //Zend_debug::dump($result);
        if (isset($result->id) != ''):
            $dictionary->saveDictionary($order, $result->id, 'Book');
            return $result->id;
        elseif (isset($result[0]->errorCode) && $result[0]->errorCode == 'DUPLICATE_VALUE'):
            $message = explode(":", $result[0]->message);
            $dictionary->saveDictionary($order, trim($message[3]), 'Book');
            return $message[3];
        endif;
    }

    public function getAllGroupAndTierPrices() {

        $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $before = mktime(0, 0, 0, date('m', $start), date("d") - 1, date('Y', $start));
        // calculate the first day of last month
        $first = date('Y-m-d H:i:s', $before);
        // calculate the last day of last month
        $last = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y', $start)));

        $class = Mage::getModel('catalog/product');
        $products = $class->getCollection();
        $products->addAttributeToSelect('*');
        $products->addFieldToFilter('updated_at', array(
            'from' => $first,
            'to' => $last,
            'date' => true
        ));

        foreach ($products as $custom):


            $salesforce_id = $this->getSFId($custom->getId(), get_class($custom));
            //$salesforce_id = "01tp0000004FJ1t";

            //$this->deletePrices($custom->getId(), 'group_price', 'Group_Price__c'); //delete prices
            //$this->deletePrices($custom->getId(), 'tier_price', 'Tier_Price__c'); //delete prices

            $groupPrices = $custom->getData('group_price');
            $TierPrices = $custom->getData('tier_price');
            $this->_processGroupPrices($groupPrices, $salesforce_id, $custom, 'group_price');
            $this->_processGroupPrices($TierPrices, $salesforce_id, $custom, 'tier_price');
            
        endforeach;
    }

    protected function _processGroupPrices($groupPrices, $salesforce_id, $custom, $price_type) {
        if (is_null($groupPrices)):
            $attribute = $custom->getResource()->getAttribute($price_type);
            if ($attribute):
                $attribute->getBackend()->afterLoad($custom);
                $groupPrices = $custom->getData($price_type);
            endif;
        endif;
        $data_gp =  array();
        $data_tp =  array();
        if (!is_null($groupPrices) || is_array($groupPrices)):
            foreach ($groupPrices as $groupPrice):
                if ($price_type == 'tier_price'):
                    $data_tp = array("Product__c" => $salesforce_id, "Price__c" => $groupPrice['price'], "Websites__c" => $this->getAllWebsites()[$groupPrice['website_id']], "Customer_Group__c" => $this->getGroupById($groupPrice['cust_group']), "Qty__c" => $groupPrice['price_qty']);                    
                else:
                    $data_gp = array("Product__c" => $salesforce_id, "Price__c" => $groupPrice['price'], "Website__c" => $this->getAllWebsites()[$groupPrice['website_id']], "Customer_Group__c" => $this->getGroupById($groupPrice['cust_group']));
                    
                endif;
            endforeach;
        endif;
        if(count($data_tp)>0)
        $this->_sendGroupPriceToSalesForce($data_tp, $custom, $price_type, 'Tier_Price__c');
        
        if(count($data_gp)>0)
        $this->_sendGroupPriceToSalesForce($data_gp, $custom, $price_type, 'Group_Price__c');
    }

    private function _sendGroupPriceToSalesForce($data, $custom, $price_type, $entity) {

        //Zend_debug::dump($data);

        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => $entity));
        $connector->authorize();
        //$result = $connector->create($data)->getResult();
        //Zend_debug::dump($result);
        if (isset($result->id) != ''):
            $dictionary->saveDictionary($custom, $result->id, 'Price_' . strtoupper($price_type));        
        elseif (isset($result[0]->errorCode) && $result[0]->errorCode == 'DUPLICATE_VALUE'):
            $message = explode(":", $result[0]->message);
            $dictionary->saveDictionary($custom, trim($message[3]), 'Price_' . strtoupper($price_type));
        endif;
    }

    public function deletePrices($product_id, $type, $entity) {
        $class = "Mage_Catalog_Model_Product_Price_" . strtoupper($type);
        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        $object = $dictionary->getCollection()
                ->addFieldToFilter("entity_type", $class)
                ->addFieldToFilter("entity_id", $product_id);

        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => $entity));
        $connector->authorize();

        foreach ($object as $data):
            $sf_id = $data->getData('salesforce_id');
            $result = $connector->delete($sf_id)->getResult();
            Zend_Debug::dump($result);
            $data->delete();
        endforeach;
    }

    public function getAllWebsites() {
        $array = array();
        foreach (Mage::app()->getWebsites() as $website):
            $array[$website->getId()] = $website->getName();
        endforeach;

        return $array;
    }

    public function getGroupById($group_id) {
        return Mage::getModel('customer/group')->load($group_id)->getCustomerGroupCode();
    }

    public function addStandardPrice() {
        $products = Mage::getModel("apdinteract_salesforce/dictionary");
        $object = $dictionary->getCollection()
                ->addFieldToFilter("entity_type", 'Mage_Catalog_Model_Product');
    }

    public function createSfEntries($entity, $data, $extra, $model) {
        
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => $entity));
        $connector->authorize();
        $result = $connector->create($data)->getResult();

        Zend_Debug::dump($result);

        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");

        if (isset($result->id) != ''):
            $dictionary->saveDictionary($model, $result->id, $extra);
            return $result->id;
        elseif (isset($result[0]->errorCode) && $result[0]->errorCode == 'DUPLICATE_VALUE'):
            $message = explode(":", $result[0]->message);
            $dictionary->saveDictionary($model, trim($message[3]), $extra);
            return trim($message[3]);
        endif;
        
        
        
        
    }
    
    public function getPaymentInfo($parent) {
        $payment = Mage::getModel("sales/order_payment");
        
        $info = $payment->getCollection()
                ->addFieldToFilter("parent_id", $parent)
                ->setOrder('entity_id', 'DESC')
                ->getFirstItem()
                ->getData();
        $method = $this->_getMethod($info['method']);
        $payment_info_ar = $info['additional_information'];
        $payment_info = $method."\n";

        if(isset($payment_info_ar['paypal_payer_id'])):
            $payment_info .= "Payer ID: ".$payment_info_ar['paypal_payer_id']."\n";
            $payment_info .= "Payer Email:: ".$payment_info_ar['paypal_payer_email']."\n";
            $payment_info .= "Payer Status: ".$payment_info_ar['paypal_payer_status']."\n";
            $payment_info .= "Payer Address Status: ".$info['address_status']."\n";
            $payment_info .= "Merchant Protection Eligibility: ".$payment_info_ar['paypal_protection_eligibility']."\n";
            $payment_info .= "Last Correlation ID: ".$payment_info_ar['paypal_correlation_id']."\n";
            $payment_info .= "Last Transaction ID: ".$payment_info_ar['paypal_payer_id']."\n";
        endif;
        
        $payment_info .= "Order was placed using AUD\n";
            
        return $payment_info;
    }
    
    protected function _getMethod($method) {
        $allActivePaymentMethods = Mage::helper('payment')->getPaymentMethodList();
        return trim($allActivePaymentMethods[$method])=='' ? $method :  $allActivePaymentMethods[$method];
    }

}
