<?php

class ApdInteract_Requestprice_Helper_Data extends Mage_Core_Helper_Abstract {

    private $_overridePostedValues;
    private $_order;

    const PRICE_REQUEST = 'PRICE REQUEST';
    const BOOKING = 'BOOKING';
    const TYRES = 'tyres';
    const WHEELS = 'wheels';
    const BATTERIES = 'batteries';
    const EASTER = 'EASTER CAMPAIGN';
    const TYRESFROM399 = 'TYRES FROM 399 CAMPAIGN';
    const BATOFFER = 'June $30 Battery offer';

    public function getCustomerDetails($detail) {
        $data = $this->_getCustomerData($detail);
        return $data;
    }

    private function _getCustomerData($detail) {
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            switch ($detail) {
                case 'fname': $customerData = $customer->getFirstname();
                    break;
                case 'lname': $customerData = $customer->getLastname();
                    break;
                case 'email': $customerData = $customer->getEmail();
                    break;
                case 'phone': $customerData = $customer->getPrimaryBillingAddress()->getTelephone();
                    break;
            }
            return $customerData;
        }
    }

    public function getMinimumDate($categoryName) {
        switch ($categoryName) {
            case '/tyres' : $minimumDate = Mage::getStoreConfig('booking_date/date/tyres');
                break;
            case '/wheels' : $minimumDate = Mage::getStoreConfig('booking_date/date/wheels');
                break;
            case '/batteries' : $minimumDate = Mage::getStoreConfig('booking_date/date/batteries');
                break;
            default: $minimumDate = 6;
        }
        return $minimumDate;
    }

    public function getTestProductDetails($sku = '529713-P', $qty = false, $child_option_index = null) {
        if (!$qty) {
            $qty = mt_rand(1, 6); // Random qty between 1 and 6.
        }


        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku); // Assurance Triplemax

        $test_order_info['productId'] = $product->getId();
        $test_order_info['qty'] = $qty;
        $test_order_info['configurableAttributeId'] = '';
        $test_order_info['randomAttributeValueId'] = '';

        if ($product->isConfigurable()) {
            $productAttributeOptions = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);

            // Assuming 1 configurable attribute
            $test_order_info['configurableAttributeId'] = $productAttributeOptions[0]['attribute_id'];

            // Pick a random child product
            $values = $productAttributeOptions[0]['values'];
            if (!isset($child_option_index)) {
                $child_option_index = mt_rand(0, count($values) - 1);
            }
            $test_order_info['randomAttributeValueId'] = $values[$child_option_index]['value_index'];
        }

        return $test_order_info;
    }

    public function formatTestCartString($cart) {
        // sku1|qty|option,sku2|qty|option
        // eg
        //abc123|4|1,def123|2|2
        $lines = explode(',', $cart);
        $fields = array('sku', 'qty', 'child_option_index');

        foreach ($lines as $line_index => $line) {
            $product_details = explode('|', $line);
            foreach ($fields as $field_index => $field) {

                if (!empty($product_details[$field_index]) || ( $field == 'child_option_index' && isset($product_details[$field_index]) )) {
                    $product_array[$line_index][$field] = $product_details[$field_index];
                } else {
                    if ($field == 'sku') {
                        break;
                    }
                    if ($field == 'qty') {
                        $product_array[$line_index]['qty'] = 1;
                    }
                }
            }
            if (isset($product_array[$line_index]['sku']) && !isset($product_array[$line_index]['child_option_index'])) {
                $product_array[$line_index]['child_option_index'] = '';
            }
        }

        return $product_array;
    }

    public function createTestCart($cart) {
        $product_array = $this->formatTestCartString($cart);

        foreach ($product_array as $product_params) {
            $product = $this->getTestProductDetails($product_params['sku'], $product_params['qty'], $product_params['child_option_index']); // First child product
            // Bottom 2 params are randomly generated if last 2 function params are blank 
            // Middle 2 are blank for simple products (batteries).

            $params = array(
                'productId' => $product['productId'],
                'configurableAttributeId' => $product['configurableAttributeId'],
                'configurableAttributeValueId' => $product['randomAttributeValueId'],
                'requestedQuantity' => $product['qty']
            );

            $this->setOrderParams($params)->addProductToCart();
        }

        return $this->_getCart();
    }

    public function createTestOrder() {

        $product = $this->getTestProductDetails('529713-P');

        $params = array(
            'productId' => $product['productId'],
            'configurableAttributeId' => $product['configurableAttributeId'],
            'configurableAttributeValueId' => $product['randomAttributeValueId'],
            'requestedQuantity' => $product['qty'],
            'fname' => "Auto-generated-TEST-Firstname-3213",
            'lname' => "TEST-Customer-please-Ignore-Lastname",
            'cartaddress-id' => "7242",
            'email' => "test@test.com",
            'subcribe' => 'false',
            'phone' => '0412123123',
            'date' => '24/02/2017'
        );

        return $this->createOrder($params);
    }

    public function deleteTestOrders() {
        $orders = Mage::getModel('sales/order')->getCollection()
                ->addFieldToFilter('customer_email', 'test@test.com');

        $sql = (string) $orders->getSelect();

        foreach ($orders as $order) {
            $this->deleteOrder($order);
        }
    }

    public function deleteOrder($order) {
        Mage::register('isSecureArea', true);
        $order->delete();
        Mage::unregister('isSecureArea');
    }

    public function setOrderParams($params) {
        $this->_overridePostedValues = $params;
        return $this;
    }

    public function createOrder($params) {
        $this->setOrderParams($params);
        return $this->saveOrder();
    }

    public function addProductToCart() {
        $product_id = $this->_getPostedValues('productId');
        $qty = $this->_getQty();

        if ($this->_isConfigurableProduct($product_id)) {
            $super_attribute_id = $this->_getPostedValues('configurableAttributeId');
            $super_attribute_value_id = $this->_getPostedValues('configurableAttributeValueId');
            $this->_addConfigurableProductToCart($product_id, $super_attribute_id, $super_attribute_value_id, $qty, $options = null);
        } else {
            $this->_addSimpleProductToCart($product_id, $qty);
        }
    }

    public function saveOrder() {
        try {
            $this->addProductToCart();
            $order = $this->_convertQuoteToOrder();
            return $order;
        } catch (Exception $e) {
            Mage::logException($e);
            return $this->_errorMessageCode();
        }
    }

    private function _convertQuoteToOrder($easter = false,$campaignCode="") {
        $quote = $this->_getQuote();
        $this->_saveCustomerData($quote,$easter);
        $this->_savePayment($quote);
        $order = $this->_getOrderFromQuote($quote);
        $this->_setMakeAndModel($order,$easter,$campaignCode);
        if(!$easter)
        $this->_sendNewRequestPriceEmail($order);
        
        $this->_clearCart($quote);
        return $order;
    }

    private function _isConfigurableProduct($parent_product_id) {
        return Mage::getModel('catalog/product')->load($parent_product_id)->isConfigurable();
        // return (!empty($child_product_id) && $child_product_id != $parent_product_id);
    }

    private function _saveCustomerData($quote,$easter = false) {
        $isGuest = true;
        $_customer = $this->_getCustomer();

        if ($_customer->isLoggedIn()) {
            // Associate customer account with order
            $this->_assignLoggedInCustomerToQuote($_customer, $quote);
            $isGuest = false;
        }

        // Set order address to the selected store      
        $details = $this->_saveGuestData($quote, $isGuest, $easter);
        

        if ($this->_hasUserTickedSubscribe()) {
            $this->_sendNewsletterConfirmationEmailIfNotAlreadySubscribed($this->_getPostedValues('email'));
        }

        $this->_saveBillingAndShippingDetails($quote, $details); // Really slow line            
            
    }

    private function _getOrderFromQuote($quote) {
        $service = Mage::getModel('sales/service_quote', $quote);
        $service->submitAll();
        $order = $service->getOrder();

        if (!$order->getId()) {
            throw new Exception('Order not created from quote: ' . print_r($quote->getData(), true));
        }
        return $order;
    }

    private function _getQty() {
        $qty = $this->_getPostedValues('requestedQuantity');

        if (isset($qty)) {
            $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
            );
            $qty = $filter->filter($qty);
        }

        return $qty;
    }

    private function _addSimpleProductToCart($product_id, $qty) {
        // Add the simple product to the cart                
        $cart = $this->_getCart();
        $cart->init();

        $product = $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($product_id);

        if (!$product) {
            throw new Exception("Product with id:{$product_id} could not be loaded.");
        }

        $params = array('qty' => $qty);
        $cart->addProduct($product, $params);
        $cart->save();
        Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
    }

    private function _hasUserTickedSubscribe() {
        $subscribe = $this->_getPostedValues('subcribe');
        return $subscribe == 'true'; // sic
    }

    private function _getProductId() {
        $id = $this->_getPostedValues('requestedProductId');

        if (empty($id)) {
            $id = $this->_getPostedValues('productId'); // product is a battery            
        }

        return $id;
    }

    private function _getCategoryName() {
        $product = Mage::getModel('catalog/product')->load($this->_getPostedValues('productId'));
        $category = $product->getCategoryIds();

        if ($category[0] == 41) {
            $categoryName = self::TYRES;
        } elseif ($category[0] == 42) {

            $categoryName = self::WHEELS;
        } elseif ($category[0] == 43) {

            $categoryName = self::BATTERIES;
        }

        return $categoryName;
    }

    private function _assignLoggedInCustomerToQuote($_customer, $quote) {
        $customerData = $_customer->getCustomer();
        $customer = Mage::getModel('customer/customer')
                ->setWebsiteId(1)
                ->loadByEmail($customerData->getEmail());

        $quote->assignCustomer($customer);
    }

    private function _getNullValue() {
        return 'n/a';
    }

    private function _getStorePostData($data) {

        if ($this->_getPostedValues('cartaddress-id') == '') {
            return $this->_getNullValue();
        } else {
            $store_id = $this->_getPostedValues('cartaddress-id');
            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $post_code = $readConnection->fetchOne("SELECT $data FROM iwd_storelocator WHERE entity_id='$store_id' LIMIT 1");
            Mage::log($post_code . "==" . $data . $this->_getPostedValues('cartaddress-id'), null, "requestprice-new.log");
            if ($post_code != '') {
                return $post_code;
            } else {
                return $this->_getNullValue();
            }
        }
    }

    private function _saveGuestData($quote, $guest, $easter = false) {
        if ($guest == true) {
            $quote->setCustomerEmail($this->_getPostedValues('email'));
        }
        
        $region_id = $this->_getStorePostData('region_id');
        $state = $this->_getStatebyId($region_id);
        
        if(!$easter) :
        $addressData = array(
            'firstname' => $this->_getPostedValues('fname'),
            'lastname' => $this->_getPostedValues('lname'),
            'street' => $this->_getStorePostData('street'),
            'city' => $this->_getStorePostData('city'),
            'postcode' => $this->_getStorePostData('postal_code'),
            'telephone' => $this->_getPostedValues('phone'),
            'country_id' => 'AU',
            'region' => $state,
            'region_id' => $region_id
        );
        else :
        $post = Mage::app()->getRequest()->getParams();    
        //extract first name & last name    
        $name_array = explode(' ',$this->_getPostedValues('name'));
        $count = count($name_array);        
        $lname = "";
        //$region_id = $post['region_id'];
        //$state = $this->_getStatebyId($region_id);
        for($i=0;$i<=$count-2;$i++): //loop to make sure all string is covered
            $fname .=$name_array[$i]. " ";
        endfor;
       
        $lname = $name_array[$count-1];        
        $fname = (trim($fname)=="" ? $lname :  $fname);
        $addressData = array(
            'firstname' => trim($fname),
            'lastname' => $lname,
            'street' => $this->_getStorePostData('street'),
            'city' => $this->_getStorePostData('city'),
            'postcode' => $this->_getStorePostData('postal_code'),
            'telephone' => $post['mobile'],
            'country_id' => 'AU',
            'region' => $state,
            'region_id' => $region_id
        );    
        endif;               
        return $addressData;
    }

    private function _getStatebyId($region_id) {
        $region = Mage::getModel('directory/region')->load($region_id);
        $state = $region->getCode();
        $state = strtoupper($state);

        return $state;
    }

    private function _getRegiondId($countryCode) {
        $region = Mage::getModel('directory/region')->loadByCode($countryCode, 'AU');
        return $region->getId();
    }

    private function _getAttributeValue($product_id, $attribute_code) {
        $prod = Mage::getModel('catalog/product')->load($product_id);
        return $prod->getData($attribute_code);
    }

    private function _addConfigurableProductToCart($parent_product_id, $super_attribute_id, $super_attribute_value_id, $qty, $options = null) {
        if (!$qty) {
            return;
        }

        $product = Mage::getModel('catalog/product')->load($parent_product_id);
        $cart = $this->_getCart();
        $cart->init();

        $params = array(
            'product' => $parent_product_id,
            'super_attribute' =>
            array($super_attribute_id => $super_attribute_value_id),
            'qty' => $qty,
            'options' => $options
        );

        $cart->addProduct($product, $params);
        $cart->save();
        Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
        return true;
    }

    private function _saveBillingAndShippingDetails($quote, $details) {
        $billingAddress = $quote->getBillingAddress()->addData($details);
        $shippingAddress = $quote->getShippingAddress()->addData($details);

        $shippingAddress->setCollectShippingRates(true)->collectShippingRates()
                ->setShippingMethod('flatrate_flatrate')
                ->setPaymentMethod('paybyphone'); // Free ('free') is only available when the order total is 0
    }

    private function _savePayment($quote) {
        $quote->getPayment()->importData(array('method' => 'paybyphone'));  // Free ('free') is only available when the order total is 0
        $quote->collectTotals()->save();
    }

    private function _setMakeAndModel($order, $easter = false, $campaignCode="") {
        $post = Mage::app()->getRequest()->getParams();
        $size = $post['size'];
        $json_tyre = Mage::helper('searchtyre')->getCacheTyreData(Mage::getSingleton('core/session')->getSeriesF());

        $order = Mage::getModel('sales/order')->loadByIncrementId($order->getIncrementId());
        
        if(isset($post['vehicle_make']))
        $order->setVmake($post['vehicle_make']); // values from the selected tyre/vehicle search
        
        if(isset($post['vehicle_model']))
        $order->setVmodel($post['vehicle_model']);
        
        $order->setYear(Mage::getSingleton('core/session')->getTYearF());
        $order->setEasterfrmTyreSize($size);

        $store = $this->_getPostedValues('store');
        $date = $this->_getPostedValues('date'); // was "data"
        $requestType = self::PRICE_REQUEST;
        if ($store != null && $date != null) {
            $storelocator = $this->_getStoreLocatorId($store);
            $requestType = self::BOOKING;
            $order->setStorelocation($storelocator);
            $order->setDeliveryDate($date);
        }

        if(!empty($campaignCode) && $campaignCode=="tyresfrom399"){
            $requestType = self::TYRESFROM399;
        }
        elseif(!empty($campaignCode) && $campaignCode=="batoffer"){
            $requestType = self::BATOFFER;
        }elseif(!empty($campaignCode)){
            $requestType = $campaignCode;
        } elseif($easter) {
            $requestType = self::EASTER;
        }
        
        $order->setStorelocation($this->_getPostedValues('cartaddress-id'));
        $order->setRequestType($requestType);
        $order->save();
    }

    private function _getNewsletterModel() {
        return Mage::getModel('newsletter/subscriber');
    }

    private function _sendNewsletterConfirmationEmailIfNotAlreadySubscribed($email) {
        if (!$this->_isEmailSubscribed($email)) {
            $this->_getNewsletterModel()->subscribe($email);
        }
    }

    private function _isEmailSubscribed($email) {
        $subscriber = $this->_getNewsletterModel()->loadByEmail($email);
        if ($subscriber->getId()) {
            return true;
        }
        return false;
    }

    private function _sendNewRequestPriceEmail($order) {
        $salesOrder = Mage::getModel("sales/order")->loadByIncrementId($order->getIncrementId());
        if ($salesOrder->getId()) {
            try {
                $salesOrder->sendNewOrderEmail();
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        } else {
            return $this->_errorMessageCode();
        }
    }

    private function _successMessageCode() {
        return array("Success" => 1);
    }

    private function _errorMessageCode() {
        return array("Error" => 0,
            "Message" => "Order not found");
    }
        

    private function _getPostedValues($field) {
        if (!isset($this->_overridePostedValues[$field])) {
            $value = Mage::app()->getRequest()->getPost($field);
        } else {
            $value = $this->_overridePostedValues[$field];
        }
        return $value;
    }

    private function _getStoreLocatorId($store) {
        $model = Mage::getModel('storelocator/stores')->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('title', array('eq' => $store))
                ->getFirstItem();
        return $model->getId();
    }

    protected function _getQuote() {
        return $this->_getCart()->getQuote();
    }

    protected function _getCustomer() {
        return Mage::getSingleton('customer/session');
    }

    protected function _getCart() {
        return Mage::getSingleton('checkout/cart');
    }

    private function _clearCart($quote) {
        $quote->setIsActive(0)->save();
        Mage::getSingleton('checkout/session')->clear();
    }

    public function clearCart($quote = null) {
        if (!isset($quote)) {
            $quote = $this->_getQuote();
        }
        $this->_clearCart($quote);
    }

    public function emptyCart() {
        $cart = $this->_getCart();
        $cart->truncate(); // remove all active items in cart page
        $cart->init();
    }

    public function sendToSF($post,$order_id) {

        $request = $this->getLoginUserDetails();
        $request['product_name'] = Mage::getSingleton('core/session')->getRequestedProductName();
        $request['product_sku'] = Mage::getSingleton('core/session')->getRequestedSku();
        $request['name'] = $post['fname'];
        $request['lastname'] = $post['lname'];
        $request['telephone'] = $post['phone'];
        $request['email'] = $post['email'];
        $request['storename'] = $post['store'];
        $request['source'] = $post['source'];
        $request['postcode'] = $post['postcode'];
        $request['requestedSize'] = $post['requestedSize'];
        $request['vehicle_make'] = $post['vehicle_make'];
        $request['vehicle_model'] = $post['vehicle_model'];   
        
        $order = Mage::getModel('sales/order')->load($order_id);
        $request['time_of_inquiry'] = $order->getCreatedAt();   
        if(isset($post['data']))
            $request['date'] = $post['data'];
        
        if(isset($post['subcribe']))
            $request['subscribe'] = 1;
        
        
        $sao = Mage::getModel("apdinteract_salesforce/process_business_lead", $request);
        try {
           
                $result = $sao->process()->getResult();
            
            if (!isset($result->id)):
                Mage::log($result[0]->errorCode . ':' . $result[0]->message, null, 'lead_error.log');
            //Mage::log($result[0]->message.':'.$result[0]->message);
            else:
                Mage::log($result->id, null, 'lead_success.log');
            endif;
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    public function getLoginUserDetails($post) {
        $request['company'] = "N/A";        
        
                            
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {

            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $request['customer_id'] = $customerData->getId();
            $customerAddressId = Mage::getSingleton('customer/session')->getCustomer()->getDefaultBilling(); //oder getDefaultShipping
            if ($customerAddressId) {
                $address = Mage::getModel('customer/address')->load($customerAddressId);

                if ($address->getCompany() != '')
                    $request['company'] = $address->getCompany();

                $request['zip'] = $address->getPostcode();
                $request['city'] = $address->getCity();
                $street= $address->getStreet();
                $request['street'] = $street[0]." ".$street[1];                
                $request['mobile'] = $address->getMobile();
                $request['fax'] = $address->getFax();
                $request['country'] = $address->getCountry();
                $request['region'] = $address->getRegion();

                if (!isset($post['email']) && $post['email'] == '')
                    $request['email'] = $customerData->getEmail();
            }
        }

        
        return $request;
    }
    
    public function successMessageCode() {
        return $this->_successMessageCode();
    }

    public function errorMessageCode() {
        return $this->_errorMessageCode();
    }
    
    public function getPostedValues($field) {
        return $this->_getPostedValues($field);
    }
    
    public function addSimpleProductToCart($product_id, $qty) {
         return $this->_addSimpleProductToCart($product_id, $qty);
    }
    
    public function getQty() {
        return $this->_getQty();
    }
    
    public function convertQuoteToOrder($campaignCode="") {
        return $this->_convertQuoteToOrder(true,$campaignCode);
    }
    

}