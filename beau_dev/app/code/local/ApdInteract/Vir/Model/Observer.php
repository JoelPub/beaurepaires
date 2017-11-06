<?php

class ApdInteract_Vir_Model_Observer {

    public function autoGenerateVir(Varien_Event_Observer $observer) {

        $event = $observer->getEvent();
        $incrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        $vehicle     = json_decode(Mage::getSingleton("core/session")->getDetailTyres(),true);
        $arrayVehicleId = Mage::getSingleton("core/session")->getVehicleDetailsId();
        /*$time = Mage::getSingleton('core/session')->getDtime();
        $bookingDate = Mage::getSingleton('core/session')->getDeliveryDate();
        $date = explode("/", $bookingDate);
        $formattedDate = $date[2].'-'.$date[1].'-'.$date[0];
        */
        //$order = $event->getOrder();        
        $order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
        list($formattedDate, $time) = explode(' ', $order->getAppointmentDatetime());

        $payment = $order->getPayment();
        $method = $payment->getMethodInstance()->getCode();

        if ($method) { // allow all payment methods to generate VIRs
            $billing = $order->getBillingAddress();
            $customer_address = Mage::getModel('customer/address')->load($billing->getCustomerAddressId());
            $store_id = $order->getStorelocation();
            $date = strtotime($order->getCreatedAtStoreDate());
            $orderdate = date("Y-m-j",$date);
            $ordertime = date("H:i:s", $date);

            $consumer_commercial_ar = $this->_getVirType($order->getAllVisibleItems());
            $commercial = $consumer_commercial_ar['commercial'];
            $consumer = $consumer_commercial_ar['consumer'];

            $lastwheelalignment_no = $this->_getMonthValue($order->getLastWheelAlignment());
            $lastbalance_no = $this->_getMonthValue($order->getLastWheelBalance());

            if ($commercial > 0) {
                $data = array(

                    "inspectiondate" => $orderdate,
                    "ordernumber" => $order->getIncrementId(),
                    "paymenttype" => $payment->getMethodInstance()->getTitle(),
                    "customername" => $billing->getFirstname() . " " . $billing->getlastname(),
                    "phonenumber" => $billing->getTelephone(),
                    "phonemobile" => $customer_address->getMobile(),
                    "addressline1" => $billing->getStreet(1),
                    "addressline2" => $billing->getStreet(2),
                    "suburb" => $billing->getCity(),
                    "state" => $billing->getRegion(),
                    "postcode" => $billing->getPostcode(),
                    "regonumber" => $order->getRegistrationNumber(),
                    "fleetnumber" => $order->getFleetNumber(),
                    "speedohubreading" => $order->getSpeedometerHub(),
                    "appointmentid" => $order->getData('appointmentid'),
                    "customerid" => $order->getData('customer_id'),
                    "booking_date" => date('Y-m-d',strtotime($formattedDate)),
                    "booking_time" => $time,
                );

                $additional_data = array(
                    "vehiclemake" => $order->getVmake(),
                    "vehicleyear" => $order->getYear(),
                    "vehiclemodel" => $order->getVmodel()
                );


                $commercial_order = Mage::getModel('apdinteract_vir/ordercommercial');
                $vir_id = $this->_saveData($data, $commercial_order);
                //Mage::log('commercial:' . $vir_id);
                $this->_mapVir($vir_id, $store_id, 1);
                $vir_ids = 'Commercial : ' . $vir_id;

                $new_data = array_merge($data,$additional_data);
                Mage::Helper('apdinteract_vir')->saveCommVirVehicleData($new_data,$order->getData('customer_id')); //update or add vehicle details
                
                if (Mage::helper('addblock')->checkSF()): //check if salesforce module is enabled
                     Mage::Helper('apdinteract_vir')->sendToSFCommercial($vir_id);
                endif;
                
            }

            if ($consumer > 0) {
                $data = array(
                    "custname" => $billing->getFirstname() . " " . $billing->getlastname(),
                    "custaddress" =>$billing->getStreet(1),
                    "custaddress2" =>$billing->getStreet(2),
                    "custsuburb" => $billing->getCity(),
                    "custstate" => $billing->getRegion(),
                    "custpostcode" => $billing->getPostcode(),
                    "custphoneno" => $billing->getTelephone(),
                    "custphonemobile" => $customer_address->getMobile(),
                    "custemail" => $billing->getEmail(),
                    "orderdate" => $orderdate,
                    "lastbalance" => $order->getLastWheelBalance(),
                    "lastwheelalignment" => $order->getLastWheelAlignment(),
                    "vehiclemake" => $vehicle['make-tyres'],
                    "vehicleyear" => $vehicle['year-tyres'],
                    "vehiclemodel" =>$vehicle['model-tyres'],
                    "vehicleseries" =>$vehicle['series-tyres'],
                    "vehicledetails" => json_encode($arrayVehicleId),
                    "vehicleodometer" => $order->getOdometer(),
                    "lastwheelalignment" => $lastwheelalignment_no,
                    "lastbalance" => $lastbalance_no,
                    "vehiclerego" => $order->getRegistrationNumber(),
                    "timereq" => $ordertime,
                    "ordernumber" => $order->getIncrementId(),
                    "appointmentid" => $order->getData('appointmentid'),
                    "customerid" => $order->getData('customer_id'),
                    "customer_email" => $order->getCustomerEmail(),
                    "customer_is_guest" => $order->getCustomerIsGuest(),
                    "booking_date" => date('Y-m-d',strtotime($formattedDate)),
                    "booking_time" => $time,
                );

                $consumer_order = Mage::getModel('apdinteract_vir/order');
                $vir_id = $this->_saveData($data, $consumer_order);
                $storeInfo = Mage::helper('pickupinstore')->getStorebyId($store_id);
                $this->_saveHealthCheckData(
                    array(
                        'vir_id' => $vir_id,
                        'date' => date('Y-m-d', $date),
                        'time' => date('H:i:s', $date),
                        'customer_name' => $data['custname'],
                        'vehicle_registration' => $data['vehiclerego'],
                        'vehicle_make' => $data['vehiclemake'],
                        'vehicle_model' => $data['vehiclemodel'],
                        'odometer' => ''/* as per AC should not be pre populated $data['vehicleodometer']*/,
                        'store_name' => $storeInfo['title'],
                        'store_manager' => $storeInfo['man_firstname'] .' '. $storeInfo['man_lastname']
                    )
                );
                Mage::getSingleton("core/session")->unsDetailTyres();
                //Mage::log('consumer:' . $vir_id);
                $this->_mapVir($vir_id, $store_id, 0);
                $vir_ids = ' Consumer : ' . $vir_id;
                Mage::Helper('apdinteract_vir')->saveConsVirVehicleData(array_merge($data,$arrayVehicleId),$order->getData('customer_id')); //update or add vehicle details
                
                if (Mage::helper('addblock')->checkSF()): //check if salesforce module is enabled
                     Mage::Helper('apdinteract_vir')->sendToSFConsumer($vir_id);
                endif;
            }


            if ($this->_isVirEmailEnabled()) {
                Mage::register('registry-order-id', $order->getIncrementId());
                $details = array(
                    'vir_id' => $vir_ids,
                    'order_no' => $order->getIncrementId(),
                    'order_date' => $order->getCreatedAt(),
                    'fitting_date' => $this->_convertDeliveryDate($orderdate),
                    'fitting_time' => $this->_convertDeliveryTime($order->getDeliveryTime()),
                    'customer_name' => $billing->getFirstname() . " " . $billing->getlastname(),
                    'phone' => $billing->getTelephone(),
                    'email' => $billing->getEmail()
                );

                $this->_sendVirEmail($details, $store_id); // send email notification to store
            }
        }
    }

    public function _getVirType($orderedItems) {
        $commercial = 0;
        $consumer = 0;

        foreach ($orderedItems as $item) {
            $product_id = $item->getData('product_id');
            $_product = Mage::getModel('catalog/product')->load($product_id);
            $categoryId = $_product->getCategoryIds();
            $product_type = $_product->getAttributeText('customer_segment');
            if ($product_type == 'Commercial')
                $commercial = 1;

            // All wheels should create  Consumer VIR for the meantime unless advise by Business to allow Commercial Virs then Data import is required
            if (($product_type == 'Consumer') || (isset($categoryId[0]) && $categoryId[0] == '42'))
                $consumer = 1;
        }
        return array('consumer' => $consumer, 'commercial' => $commercial);
    }

    public function _saveData($details, $model) {
        foreach ($details as $field => $value) {
            $model->setData($field, $value);
        }
        $model->save();
        return $model->getParentId();
    }

    protected function _saveHealthCheckData($data) {
        Mage::getModel('apdinteract_vir/healthcheck')
            ->addData($data)
            ->save();
    }

    public function _mapVir($vir_id, $store_id, $type) {
        $mapping = Mage::getModel('apdinteract_vir/orderstoremapping');
        $mapping->setVirId($vir_id);
        $mapping->setStoreId($store_id);
        $mapping->setVirType($type); //consumer type
        $mapping->save();
    }

    public function _getMonthValue($label) {
        $value = 0;
        if ($label == '0-6 months')
            $value = 0;
        elseif ($label == '6-12 months')
            $value = 1;
        elseif ($label == 'Over 12 months')
            $value = 2;

        return $value;
    }

    private function _sendVirEmail($virDetails, $id) {

        $template = Mage::getStoreConfig('vir/notification/notification_template');
        if (!$template) {
            $email = Mage::getModel('core/email_template')->loadByCode('VIR Notification Email');
        } else {
            $email = Mage::getModel('core/email_template')->load($template);
        }

        $translate = Mage::getSingleton('core/translate');

        if ($email->getId()) {

            $from_email = 'info@sales.beaurepaires.com.au';
            $from_name = 'Beaurepaires';
            // This $sender are not being used since we are using Silverpop extension but is needed as parameter for sendTransactional()
            $sender = array('name' => $from_name,
                'email' => $from_email);
            $storeId = Mage::app()->getStore()->getStoreId();
            $name = null; //Recipient name

            $model = $email->setReplyTo($sender['email']);
            if ($this->_sendVirEmailtoStore()) {
                //Recipient email
                $store = Mage::getModel('storelocator/stores')->load($id);
                $email_address = $store->getEmail();
            } else {
                //send to this email for testing purposes
                $email_address = 'sdialino@apdgroup.com';
            }

            try {
                $model->sendTransactional($email->getId(), $sender, $email_address, $name, $virDetails, $storeId);
                if (!$email->getSentSuccess()) {
                    throw new exception("Something went wrong VIR email was not send");
                }
                $translate->setTranslateInline(true);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }

    private function _isVirEmailEnabled() {
        return Mage::getStoreConfig('vir/notification/enabled');
    }

    private function _sendVirEmailtoStore() {
        return Mage::getStoreConfig('vir/notification/send_to');
    }

    private function _convertDeliveryTime($time) {
        $time = ltrim(date('h:i a', strtotime($time)), '0');

        return $time;
    }

    private function _convertDeliveryDate($date) {
        $newDate = date("d/m/Y", strtotime($date));

        return $newDate;
    }
    

}
