<?php

class ApdInteract_Order_Model_Observer
{
    
    public function sendCalendarData(Varien_Event_Observer $observer)
    {
        try
        {                    
            $checkout = $observer->getEvent()->getQuote();
            $billing  = $checkout->getBillingAddress();
            $order = $observer->getEvent()->getOrder();

            $data                  = array();
            $data['first_name']    = $billing->getFirstname();
            $data['last_name']     = $billing->getLastname();
            $data['email']         = $billing->getEmail();
            $data['mobile_number'] = $billing->getTelephone();
            $data['phone_number']  = $billing->getTelephone();
            $data['company_name']  = (string) $billing->getCompany();
            $data['address']       = (string) $billing->getStreet()[0];
            $data['street']        = (string) isset($billing->getStreet()[1]) ? $billing->getStreet()[1] : '';
            $data['city']          = $billing->getCity();
            $data['state']         = $billing->getRegion();
            $data['zip_code']      = $billing->getPostcode();
        
            $data['date']     = Mage::getSingleton("core/session")->getDeliveryDate();
            $data['time']     = Mage::getSingleton("core/session")->getDtime();            
            $data['storeid']  = Mage::getSingleton("core/session")->getStorelocation();
            $data['duration'] = Mage::Helper('sync')->getMinutes();       
            $data['service'] = $this->_getService($order);
            $data['notes'] = Mage::Helper("apdinteract_order")->getOrderInfo($order);
            $data['is_guest'] = false;
            $data['magento_customer_id'] = 0;

            $isLoggedIn = Mage::getSingleton('customer/session')->isLoggedIn();
     
            //check if logged in
            if ($isLoggedIn) {
                // Get the customer object from customer session
                $customer                    = Mage::getSingleton('customer/session')->getCustomer();
                $data['magento_customer_id'] = $customer->getId(); //get customer id
                $email                       = Mage::getSingleton('customer/session')->getCustomer()->getData('email');
                $subscriberModel             = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
                $subbed                      = ($subscriberModel->isSubscribed() ? true : false);
                if ($subbed == true) {
                    $customerData = Mage::getModel('customer/customer')->load($data['magento_customer_id']);
                    $customerData->setEmailSpecialOffers(1);
                    $customerData->setEmailProductNews(1);
                    $customerData->save();
                }
            } 

            $customerIdFromEmail = Mage::getModel('customer/customer')->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                ->loadByEmail($data['email'])->getId();

            if(!$isLoggedIn && !Mage::getSingleton('core/session')->getCreateAccount()) {
                if(is_null($customerIdFromEmail)) {
                    $virtualCustomer = Mage::getModel('customer/customer');
                    $virtualCustomer
                        ->setStore(Mage::app()->getStore())
                        ->setFirstname($data['first_name'])
                        ->setLastname($data['last_name'])
                        ->setEmail($data['email'])
                        ->setDormantFlag(1)
                        ->save();

                    $vAddress = Mage::getModel('customer/address')
                        ->setCustomerId($virtualCustomer->getId())
                        ->setFirstname($data['first_name'])
                        ->setLastname($data['last_name'])
                        ->setStreet($data['address'])
                        ->setTelephone($data['phone_number'])
                        ->setMobile($data['mobile_number'])
                        ->setCity($data['city'])
                        ->setRegion($data['state'])
                        ->setPostcode($data['zip_code'])
                        ->setCountryId(Mage::getStoreConfig('general/country/default'))
                        ->setIsDefaultBilling(1)
                        ->setIsDefaultShipping(1)
                        ->save();
                }
                $data['is_guest'] = true;
            }

            if (Mage::getSingleton('core/session')->getSkip() != '1') {
                $url = Mage::getStoreConfig('sync/sync/apdinteract_order_ordersaveapi', Mage::app()->getStore());
                if (empty($url)) {
                    throw new exception('apdinteract_order_ordersaveapi value needs to be set in admin area');
                }

                Mage::getSingleton("core/session")->setAppointmentid(0);

                $json     = json_encode($data);
                
                $response = json_decode(Mage::helper('apdinteract_order')->connectCurl($url, $json), true);
                if (is_int($response['message']))
                    //Mage::getSingleton("core/session")->setAppointmentid($response['message']);
                    $order->setAppointmentid($response['message'])->save();

                $verbose_logging = true;
                if ($verbose_logging) {
                    Mage::log(print_r($response, true), null, 'add_to_calendar.log');
                }

                if (!is_array($response)) {
                    throw new exception('Calendar add appointment returned invalid response: ' . $response);
                }

                if (!$response['success']) {
                    throw new exception('Calendar add appointment returned invalid response: ' . $response['message']);
                }
            }

            Mage::getSingleton('core/session')->setSkip('');
        
        } 
        catch (Exception $ex) 
        {
            Mage::logException($ex);            
            Mage::log($ex->getMessage() . ' - ' . $ex->getTraceAsString(), null, 'calendar_add_appointment_exception.log');
            
        }

        Mage::getSingleton("core/session")->unsAppointmentid();
        Mage::getSingleton("core/session")->unsStorelocation();
        Mage::getSingleton("core/session")->unsDeliveryDate();
        Mage::getSingleton("core/session")->unsDtime();
        
        
    }
    
    
    private function _getService($order) {       
        $allItems = $order->getAllVisibleItems();
        $ids_ar = array();
        foreach ($allItems as $item) {
            $product_id = $item->getData('product_id');
            $_product = Mage::getModel('catalog/product')->load($product_id);
            $ids = $_product->getCategoryIds();
            $ids_ar = array_merge($ids_ar, $ids);
        }

        $ids_ar = array_unique($ids_ar);
        sort($ids_ar);

        if (isset($ids_ar[0])) {
            $_category = Mage::getModel('catalog/category')->load($ids_ar[0]);
            $service = $_category->getData('dbc_service');
        } else {
            $service = "Mechanical";
        }
        
        return $service;
    }

    public function saveCustomData(Varien_Event_Observer $observer)
    {
        
        $json_vehicle_detail  = Mage::getSingleton("core/session")->getDetailTyres();
        $make                 = Mage::getSingleton("core/session")->getMake();
        $model                = Mage::getSingleton("core/session")->getModel();
        $odometer             = Mage::getSingleton("core/session")->getOdometer();
        $registration_number  = Mage::getSingleton("core/session")->getRegistrationNumber();
        $fleet_number         = Mage::getSingleton("core/session")->getFleetNumber();
        $speedometer_hub      = Mage::getSingleton("core/session")->getSpeedometerHub();
        $last_wheel_alignment = Mage::getSingleton("core/session")->getLastWheelAlignment();
        $last_wheel_balance   = Mage::getSingleton("core/session")->getLastWheelBalance();
        $storeloc             = Mage::getSingleton("core/session")->getStorelocation();
        $ddate                = Mage::getSingleton("core/session")->getDeliveryDate();
        $dtime                = Mage::getSingleton("core/session")->getDtime();
        
        $event          = $observer->getEvent();
        $order          = $event->getOrder();
        $appointment_id = Mage::getSingleton("core/session")->getAppointmentid();
        $fieldVal       = Mage::app()->getFrontController()->getRequest()->getParams();
        $storeName      = Mage::helper('pickupinstore')->getStorebyId($storeloc)->getTitle();

        $vehicle_details = json_decode($json_vehicle_detail,true);
        $order->setVmake($vehicle_details['make-tyres']);
        $order->setVmodel($vehicle_details['model-tyres']);
        $order->setYear($vehicle_details['year-tyres']);
        $order->setOdometer($odometer);
        $order->setRegistrationNumber($registration_number);
        $order->setFleetNumber($fleet_number);
        $order->setSpeedometerHub($speedometer_hub);
        $order->setLastWheelAlignment($last_wheel_alignment);
        $order->setLastWheelBalance($last_wheel_balance);
        
        if($storeloc) {
            $order->setStorelocation($storeloc);
        }

        if($storeName) {
            $order->setStoreDetails($storeName);
        }

        if($ddate) {
            $order->setDeliveryDate($ddate);
        }

        if($dtime) {
            $order->setDeliveryTime($dtime);
        }

        if($ddate && $dtime) {
            $order->setAppointmentDatetime($this->_appointmentDateFormat($ddate,$dtime));
        }
        
        
        //$order->setAppointmentid($appointment_id);

        /*Mage::getSingleton("core/session")->unsStorelocation();
        Mage::getSingleton("core/session")->unsDeliveryDate();
        Mage::getSingleton("core/session")->unsDtime();

        Mage::log('entry:' . $make . '====' . $model);*/
    }

    protected function _appointmentDateFormat($date,$time)
    {
        $dateArray  = explode('/',$date);
        $newDateFormat = $dateArray[2] . '-' . $dateArray[1] . '-' . $dateArray[0];
        return date('Y-m-d H:i:s', strtotime($newDateFormat . $time));
    }

    
    public function sendAppointmentNotification()
    {
        if (Mage::getStoreConfig('vir/pre_booking/enabled')) {
            $tomorrow      = new DateTime('tomorrow');
            $tomorrow_date = $tomorrow->format('d/m/Y');
            $orders        = Mage::getModel('sales/order')->getCollection()->addAttributeToSelect('*')->addAttributeToFilter('delivery_date', $tomorrow_date);
            foreach ($orders as $order) {
                $users                       = array();
                $users['email']              = $order->getCustomerEmail();
                $users['customer_firstname'] = $order->getCustomerFirstname();
                $users['customer_lastname']  = $order->getCustomerLastname();
                $users['storelocation']      = $order->getStorelocation();
                $users['delivery_time']      = $order->getDeliveryTime();
                $users['delivery_date']      = $order->getDeliveryDate();
                $this->sendTransactionalEmail($users);
            }
        }
    }
    private function sendTransactionalEmail($customer)
    {
        // Transactional Email Template's ID
        $templateId = Mage::getStoreConfig('vir/pre_booking/pre_booking_template');
        $storeId = Mage::app()->getStore()->getStoreId();			
        
        // Set sender information           
        $senderName  = Mage::getStoreConfig('trans_email/ident_support/name');
        $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
        $sender      = array(
            'name' => $senderName,
            'email' => $senderEmail
        );
        
        // Set recepient information
        $recepientEmail = $customer['email'];
        $recepientName  = $customer['firstname'] . " " . $customer['lastname'];
        
        // Get Store ID     
        $store = Mage::app()->getStore()->getId();
        
        // Set variables that can be used in email template
        $customerdata = array();
        foreach ($customer as $key => $val) {
            $varname                = "my" . $key;
            $customerdata[$varname] = $val;
        }
        
        $iwdhelper                       = Mage::helper('apdinteract_reminderemail');
        $customerdata['mydelivery_time'] = date("g:i a", strtotime($customerdata['mydelivery_time']));
        $customerdata['mystorename']     = $iwdhelper->getStore($customerdata['mystorelocation'], "name");
        $customerdata['mystorephone']    = $iwdhelper->getStore($customerdata['mystorelocation'], "phone");
        $customerdata['mystoreaddress']  = $iwdhelper->getStore($customerdata['mystorelocation'], "address");
        $vars                            = $customerdata;
        $translate                       = Mage::getSingleton('core/translate');
        
        // Send Transactional Email
        Mage::getModel('core/email_template')->sendTransactional($templateId, $sender, $recepientEmail, $recepientName, $vars, $storeId);
        
        $translate->setTranslateInline(true);
    }

}
