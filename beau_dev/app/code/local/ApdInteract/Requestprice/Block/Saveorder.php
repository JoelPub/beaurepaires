<?php

class ApdInteract_Requestprice_Block_Saveorder extends Mage_Core_Block_Template {

    const PRICE_REQUEST = 'PRICE REQUEST';
    const BOOKING = 'BOOKING';
    const TYRES = 'tyres';
    const WHEELS = 'wheels';
    const BATTERIES = 'batteries';

    private $sendPriceRequestEmail =false;
      
    public function saveOrder() {
        try {

            $cart = $this->_getCart();
            $cart->truncate(); 
            $cart->init();
            $requestSource  = $this->_getPostedValues('source');
            $requestType = array("Booking a Fitting","Request Price");
            if(in_array($requestSource,$requestType)) {
                $this->sendPriceRequestEmail = true;
            }
            
            
            $storeLocationId = $this->_getPostedValues('cartaddress-id');
            Mage::getSingleton('core/session')->setRequestStoreId($storeLocationId);            
            $store = Mage::getModel('storelocator/stores')->load($storeLocationId);
            
            $parent_product_id = $this->_getPostedValues('productId');                                
            $qty = $this->_getQty();                        

            if ($this->_isConfigurableProduct($parent_product_id))
            {
//                $child_product_id = $this->_getPostedValues('requestedProductId');
                $super_attribute_id = $this->_getPostedValues('configurableAttributeIdRear');
                $super_attribute_value_id = $this->_getPostedValues('configurableAttributeValueId');
                $super_attribute_value_id_rear = $this->_getPostedValues('configurableAttributeValueIdRear'); 
                $qty = explode(', ',$this->_getPostedValues('requestedQuantity'));

                $qty_1 = $qty[0];
                if($super_attribute_value_id == $super_attribute_value_id_rear)
                   $qty_1 =  $qty[0] + $qty[1];
                
                if($super_attribute_value_id>0) 
                $this->_addConfigurableProductToCart($parent_product_id, $super_attribute_id, $super_attribute_value_id, $qty_1, $options = null);                
                
                //check if different sizes
                if($super_attribute_value_id_rear>0 && $super_attribute_value_id != $super_attribute_value_id_rear) {
                    $this->_addConfigurableProductToCart($parent_product_id, $super_attribute_id, $super_attribute_value_id_rear, $qty[1], $options = null);
                }             
            }            
            else {                                
                $this->_addSimpleProductToCart($parent_product_id, $qty);
            }
            
            $order_id = $this->_convertQuoteToOrder();
            
            if (Mage::helper('addblock')->checkSF()):    
                $sku = $this->_getPostedValues('requestedProductSKU');
                Mage::getSingleton('core/session')->setRequestedProductName($this->_getProduct($sku)->getName());
                Mage::getSingleton('core/session')->setRequestedSku($sku);         
                Mage::getSingleton('core/session')->setParentProduct($this->_getPostedValues('productId'));
                $post = Mage::app()->getRequest()->getParams();
                $post['postcode'] = $store->getPostalCode();                
                Mage::helper('apdinteract_requestprice')->sendToSF($post,$order_id); // send to salesforce
            endif;
        
            //return $this->_successMessageCode();
            $analytics = Mage::helper('apdwidgets')->getAnalyticsAsJson($order_id);
            return array("Success" => 1,"analytics"=>$analytics);

        } catch (Exception $e) {
            Mage::logException($e);
            return $this->_errorMessageCode();
        }                
    }

    private function _convertQuoteToOrder() 
    {   
        $quote = $this->_getQuote();
        $this->_saveCustomerData($quote);         
        $this->_savePayment($quote);
        $order = $this->_getOrderFromQuote($quote);
        $this->_setMakeAndModel($order);

        //Can send Price Request Email - Checked Admin Config if enable/disable
        If(Mage::getStoreConfig("vir/price_request_booking/enabled")) {
            $this->_sendNewRequestPriceEmail($order);
        }
        $this->_clearCart($quote);
        
        return $order->getId();


    }
    
    private function _isConfigurableProduct($parent_product_id) {
        return Mage::getModel('catalog/product')->load($parent_product_id)->isConfigurable();        
        // return (!empty($child_product_id) && $child_product_id != $parent_product_id);
    }
    
    private function _saveCustomerData($quote) 
    {        
        $isGuest = true;
        $_customer = $this->_getCustomer();

        if ($_customer->isLoggedIn()) 
        {   
            // Associate customer account with order
            $this->_assignLoggedInCustomerToQuote($_customer, $quote);
            $isGuest = false;
        } 
        
        // Set order address to the selected store
        $details = $this->_saveGuestData($quote, $isGuest);
        
        if ($this->_hasUserTickedSubscribe()) 
        {
            $this->_sendNewsletterConfirmationEmailIfNotAlreadySubscribed($this->_getPostedValues('email'));
        }
        
        $this->_saveBillingAndShippingDetails($quote, $details); // Really slow line            
    }
    
    private function _getOrderFromQuote($quote)
    {                    
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
    
    private function _addSimpleProductToCart($product_id, $qty)
    {
        // Add the simple product to the cart                
        $cart = $this->_getCart();

        $product = $product = Mage::getModel('catalog/product')
        ->setStoreId(Mage::app()->getStore()->getId())
        ->load($product_id);

        if (!$product) {
            throw new Exception("Product with id:{$product_id} could not be loaded.");
        }

        $params = array('qty' => $qty);
        $cart->addProduct($product, $params);        
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
  	private function _getStorePostData($data)
	{

		if($this->_getPostedValues('cartaddress-id')=='')
		{
			return $this->_getNullValue();
		}
		else
		{
			$store_id = $this->_getPostedValues('cartaddress-id');
			$resource = Mage::getSingleton('core/resource');
			$readConnection = $resource->getConnection('core_read');
			$post_code = $readConnection->fetchOne("SELECT $data FROM iwd_storelocator WHERE entity_id='$store_id' LIMIT 1");	
			Mage::log($post_code."==".$data,null,"requestprice.log");
			if($post_code!='')
			{
				return $post_code;
			}
			else
			{
				return $this->_getNullValue();
			}
		}
	}  
    private function _saveGuestData($quote, $guest) {
        if ($guest == true) {
            $quote->setCustomerEmail($this->_getPostedValues('email'));
        }
        
        $region_id = $this->_getStorePostData('region_id');       
		$state = $this->_getStatebyId($region_id);
		
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
        return $addressData;
    }
    
    private function _getStatebyId($region_id) {
		$region = Mage::getModel('directory/region')->load($region_id);
		$state = $region->getCode();
		$state = strtoupper($state);
		
		return $state;
	}
    
    private function _getRegiondId($countryCode){
    	$region = Mage::getModel('directory/region')->loadByCode($countryCode,'AU');
    	return $region->getId();
    }
   
    private function _getAttributeValue($product_id, $attribute_code)
    {
        $prod = Mage::getModel('catalog/product')->load($product_id);
        return $prod->getData($attribute_code);
    }
    
    private function _addConfigurableProductToCart($parent_product_id, $super_attribute_id, $super_attribute_value_id, $qty, $options = null)
    {
        if (!$qty) {
            return;
        }

        $product = Mage::getModel('catalog/product')->load($parent_product_id);
        $cart = $this->_getCart();
        $cart->init();

        // To work with a child_product_id
        // $superAttributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
//        $params = array(
//            'product' => $parent_product_id,
//            'super_attribute' =>
//            array(
//                $superAttributes[0]['attribute_id'] =>
//                $this->_getAttributeValue($child_product_id, $superAttributes[0]['attribute_code'])
//            ),
//            'qty' => $qty,
//            'options' => $options
//        );

        $attributeCode = array('Tyres' => 'size', 'Wheels' => 'rim_diameter_configurable');
        $simpleProductSize = $attributeCode[$product->getAttributeText('category_main')];

        $simple_product = Mage::getModel('catalog/product')->load($super_attribute_value_id);
        $params = array(
            'super_attribute' =>
                array( $super_attribute_id => $simple_product->getData($simpleProductSize)),
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

    private function _setMakeAndModel($order) {    

        $post = $this->getRequest()->getParams();

        $order = Mage::getModel('sales/order')->loadByIncrementId($order->getIncrementId());

        $sizes_ar = explode(", ",$post['requestedSize']);
        
        $sizes = isset($post['configurableAttributeValueId']) ? $sizes_ar[0].', ' : '';
        $sizes .= isset($post['configurableAttributeValueIdRear']) ? $sizes_ar[1] : '';
        
        $size = $post['requestedSize'];

        if(isset($post['vehicle_make']))
        $order->setVmake($post['vehicle_make']); // values from the selected tyre/vehicle search
        
        if(isset($post['vehicle_model']))
        $order->setVmodel($post['vehicle_model']);
        $order->setYear(Mage::getSingleton('core/session')->getTYearF());
        $order->setEasterfrmTyreSize($size);

        $store = $this->_getPostedValues('store');
        $date = $this->_getPostedValues('data');
        $requestType = self::PRICE_REQUEST;
        if ($store != null && $date != null) {
            $storelocator = $this->_getStoreLocatorId($store);
            $requestType = self::BOOKING;
            $order->setStorelocation($storelocator);
            $order->setDeliveryDate($date);
            $order->setDeliveryTime('');
            $order->setAppointmentDatetime($this->_appointmentDateFormat($date, ''));
        } else {
            $order->setDeliveryDate('');
            $order->setDeliveryTime('');
            $order->setAppointmentDatetime('');
        }

        $order->setStoreDetails($store);
        $order->setStorelocation($this->_getPostedValues('cartaddress-id'));
        $order->setRequestType($requestType);
        $order->save();
    }

    protected function _appointmentDateFormat($date,$time)
    {
        $dateArray  = explode('/',$date);
        $newDateFormat = $dateArray[2] . '-' . $dateArray[1] . '-' . $dateArray[0];
        return date('Y-m-d H:i:s', strtotime($newDateFormat . $time));
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
                $salesOrder->sendNewOrderEmail($this->sendPriceRequestEmail);
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
//        $postdata = Mage::app()->getRequest()->getPost(); // debugging only        
        $value = Mage::app()->getRequest()->getPost($field);
        return $value;
    }

    private function _getStoreLocatorId($store) {
        $model = Mage::getModel('storelocator/stores')->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('title', array('eq' => $store))
                ->getFirstItem();
        return $model->getId();
    }
    
    protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }
    
    protected function _getCustomer()
    {
        return Mage::getSingleton('customer/session');
    }
    
    protected function _getCart() 
    {
        return Mage::getSingleton('checkout/cart');
    }
    
    private function _clearCart($quote)
    {
        $quote->setIsActive(0)->save();
        Mage::getSingleton('checkout/session')->clear();
    }
    
    private function _getProduct($sku) {
        return Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
    }

}