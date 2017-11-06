<?php

class ApdInteract_Sync_Helper_Data extends Mage_Core_Helper_Abstract {

    public function connect_api($method) {

        $api = Mage::getStoreConfig('sync/sync/apdinteract_syncurl');
        $client = new Zend_Http_Client($api . $method);

        return $client;
    }

    public function _deleteappointment($appointment_id) {


        $method = "auto_delete_appointment";
        $client = Mage::Helper('sync')->connect_api($method);


        // set some parameters  
        $client->setParameterPost('appointment_id', $appointment_id);
        // POST request  
        $response = $client->request(Zend_Http_Client::POST);


        Mage::log("deleted:" . $appointment_id);
    }

    public function update_booking_status($appointmentId, $status) {

        $method = "update_appoinment_status";
        $data = array();
        $client = $this->connect_api($method);
        $data = json_encode(array(
            "id" => $appointmentId,
            "magento_vir_status" => $status
        ));



        // set some parameters  
        $client->setParameterPost('data', $data);
        // POST request  
        return $response = $client->request(Zend_Http_Client::POST);
    }

    public function sendBooking($params)
    {
        $dummyEmail = "noemail@noemail.com";
        $customerName = explode(" ",$params['orderData']['custname']);
        if(count($customerName) > 1){
            $firstName = implode(" ",array_slice($customerName, 0, -1));
            $lastName = end($customerName);
        }else{
                $firstName = $params['orderData']['custname'];
                $lastName = "";
            }

        $bookingData = array(
                'appointmentid'         => $params['appointmentid'],
                'status'                => $params['status'],
                'storeid'               => $params['storelocation_id'],
                'date'                  => $params['orderData']['booking_date'],
                'time'                  => $params['orderData']['booking_time'],
                'duration'              => '120',
                'is_guest'              => true,
                'notes'                 => '',
                'first_name'            => (string)$firstName,
                'last_name'             => (string)$lastName,
                'email'                 => empty($params['orderData']['custemail']) ? $dummyEmail : $params['orderData']['custemail'],
                'mobile_number'         => (string)$params['orderData']['custphonemobile'],
                'phone_number'          => (string)$params['orderData']['custphoneno'],
                'company_name'          => (string)$params['orderData']['custcompany'],
                'address'               => (string)$params['orderData']['custaddress'],
                'street'                => (string)$params['orderData']['custaddress2'],
                'city'                  => '',
                'state'                 => '',
                'zip_code'              => (string)$params['orderData']['custpostcode'],
                'magento_customer_id'   => (int)$params['customerid'],
            );
       $data = array();

        $data = Mage::helper('core')->jsonEncode($bookingData);
        $url = Mage::getStoreConfig('sync/sync/apdinteract_order_ordersaveapi', Mage::app()->getStore());
        if (empty($url)) {
                throw new exception('apdinteract_order_ordersaveapi value needs to be set in admin area');
        }
        $response = Mage::helper('core')->jsonDecode(Mage::helper('apdinteract_order')->connectCurl($url, $data));
        if((bool)$response['success']){
                return $response['message'];
        }else{
                throw new exception('Error on saving appointment. Message:' . $response['message']);
        }

    }
    
    public function getRegionById($regionId) {        
        $region = Mage::getModel('directory/region')->load($regionId);
        return $region->getName($regionId);
    }
    
    public function getMinutes() {
        return Mage::getStoreConfig('sync/sync/apdinteract_dbc_minutes') >0 ? Mage::getStoreConfig('sync/sync/apdinteract_dbc_minutes') : 60;
    }

}
