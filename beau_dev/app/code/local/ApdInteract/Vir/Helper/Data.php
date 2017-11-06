<?php

class ApdInteract_Vir_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Check Module is Enable or Disable
     *
     * @return ApdInteract_Vir_Helper_Data
     */
    public function getModuleStatus() {
        return Mage::getStoreConfig('vir/virsetting/vir_enabled');
    }

    public function getOptionArray() {
        return
                array(
                    'Not Started' => $this->__('Not Started'),
                    'In Progress' => $this->__('In Progress'),
                    'Cancelled' => $this->__('Cancelled'),
                    'On Hold' => $this->__('On Hold'),
                    'Ready For Customer Pickup' => $this->__('Ready For Customer Pickup'),
                    'Completed' => $this->__('Completed')
        );
    }

    public function cancelRelatedVirsFromOrder($order) {
        // Update VIR records status to Cancelled
        $consumer = Mage::getModel('apdinteract_vir/order')->load($order->getIncrementId(), 'ordernumber');
        if ($consumer->getId()) {
            $consumer->setStatus('Cancelled')->save();
        }

        $commercial = Mage::getModel('apdinteract_vir/ordercommercial')->load($order->getIncrementId(), 'ordernumber');
        if ($commercial->getId()) {
            $commercial->setStatus('Cancelled')->save();
        }
    }

    public function getCommercialDetail($orderId) {
        $collection = Mage::getModel('apdinteract_vir/ordercommercial')
                ->getCollection()
                ->addFieldToFilter('ordernumber', $orderId);
        return $collection;
    }

    public function getConsumerDetail($orderId) {
        $collection = Mage::getModel('apdinteract_vir/order')
                ->getCollection()
                ->addFieldToFilter('ordernumber', $orderId);
        return $collection;
    }

    private function _getConsumerDetailByAppId($appointmentId) {
        $collection = Mage::getModel('apdinteract_vir/order')
                ->getCollection()
                ->addFieldToFilter('appointmentid', $appointmentId);
        return $collection;
    }

    private function _getCommercialDetailByAppId($appointmentId) {
        $collection = Mage::getModel('apdinteract_vir/ordercommercial')
                ->getCollection()
                ->addFieldToFilter('appointmentid', $appointmentId);
        return $collection;
    }

    public function updateVirStatus($appointmentId, $newstatus, $customer = null, $booking_datetime = null, $additional = array()) {

        $consumerVir = $this->_getConsumerDetailByAppId($appointmentId);

        if ($consumerVir->count() > 0) :
            $id = $consumerVir->getFirstItem()->getData('parent_id');
            $vir = Mage::getModel('apdinteract_vir/order')->load($id);
        else :
            $commercialVir = $this->_getCommercialDetailByAppId($appointmentId);
            $id = $commercialVir->getFirstItem()->getData('parent_id');
            $vir = Mage::getModel('apdinteract_vir/ordercommercial')->load($id);
        endif;


        // Create new VIR from DBC
        if(empty($vir->getParentId())){

            $createDate = Mage::getModel('core/date')->date('Y-m-d');
            if(isset($additional['vir_type']) && $additional['vir_type'] == "consumer"){
                $vir = Mage::getModel('apdinteract_vir/order');
                $vir->setData('orderdate',$createDate);
                $type = 0;

            }elseif(isset($additional['vir_type']) && $additional['vir_type'] == "commercial"){
                $commercialCustomer = json_decode($customer);
                $vir = Mage::getModel('apdinteract_vir/ordercommercial');
                $vir->setData('customername', $commercialCustomer->first_name .' '. $commercialCustomer->last_name);
                $vir->setData('inspectiondate', $createDate);
                $type = 1;
            }

            $store = Mage::getModel('storelocator/stores')->load($additional['store_id']);
            $vir->setData('appointmentid',$appointmentId);
            $vir->setData('store_name',$store->getTitle());
            $vir->setData('store_name',$store->getTitle());
            $vir->save();

            Mage::Helper('apdinteract_vir')->virMapper($vir->getId(),$additional['store_id'],$type);
        }

        if(!empty($booking_datetime)){
            list($formattedDate, $time) = explode(' ', $booking_datetime);
            $vir->setData('booking_date', $formattedDate);
            $vir->setData('booking_time', $time);
        }

        if(!empty($customer)){
            $customer = json_decode($customer);
            $vir->setData('custname', $customer->first_name .' '. $customer->last_name);
            $vir->setData('custaddress', $customer->address);
            $vir->setData('custsuburb', $customer->city);
            $vir->setData('custpostcode', $customer->zip_code);
            $vir->setData('custphoneno', $customer->phone_number);
            $vir->setData('custemail', $customer->email);
        }

        $vir->setStatus($newstatus);
        $vir->save();
    }

    public function saveCommVirVehicleData($data, $customerId) {

        if (isset($data['vehiclemake'])) : // check if checkoutpage
            $data = array(
                "vehiclemake" => $data['vehiclemake'],
                "vehicleyear" => $data['vehicleyear'],
                "vehiclemodel" => $data['vehiclemodel'],
                "vehiclerego" => $data['regonumber'],
                "fleetnumber" => $data['fleetnumber'],
                "speedohubreading" => $data['speedohubreading'],
                "customerId" => $customerId,
            );
        else : //if admin               
            $data = array(
                "vehiclerego" => $data['regonumber'],
                "fleetnumber" => $data['fleetnumber'],
                "speedohubreading" => $data['speedohubreading'],
                "customerId" => $customerId
            );
        endif;

        $this->_saveVehicle($data);
    }

    public function saveConsVirVehicleData($postData, $customerId) {

        $vehicleDetailsId = array('make-tyres' => $postData['make-tyres'],
            'year-tyres' => $postData['year-tyres'],
            'model-tyres' => $postData['model-tyres'],
            'series-tyres' => $postData['series-tyres']
        );

        $data = array(
            "vehiclemake" => $postData['vehiclemake'],
            "vehicleyear" => $postData['vehicleyear'],
            "vehiclemodel" => $postData['vehiclemodel'],
            "vehicleseries" => $postData['vehicleseries'],
            "vehiclerego" => $postData['vehiclerego'],
            "vehicledetails" => json_encode($vehicleDetailsId),
            "customerId" => $customerId,
            "customer_is_guest" => $postData['customer_is_guest'],
            "customer_email" => $postData['customer_email'],
        );

        $this->_saveVehicle($data);
    }

    /**
     * @param $data
     * @param $billing_address_id
     */
    public function updateCustomerBillingAddress($data, $billing_address_id) {

        try {

            $customer_address = Mage::getModel('customer/address')->load($billing_address_id);
            $customer_address->setTelephone($data['telephone']);
            $customer_address->setMobile($data['mobile']);
            $customer_address->setStreet($data['street']);
            $customer_address->setCity($data['suburb']);
            $customer_address->setPostcode($data['postcode']);
            $customer_address->save();
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    /**
     * @param $data
     * @throws Exception
     */
    private function _saveVehicle($data) {

        $regonumber = $data['vehiclerego'];

        if ((bool) $data['customer_is_guest']) {
            $guestCustomerId = $this->_guestCustomer($data['customer_email']);
            if ((bool) count($guestCustomerId)) {
                $data['customerId'] = $guestCustomerId;
            }
        }

        if ((isset($regonumber) || isset($data['vehiclemake']) || isset($data['vehiclemodel'])) && trim($data['customerId']) != '') :
            $vehicle = Mage::Helper('apdinteract_vehicle')->checkIfVehicleIsExisting($data['customerId'], $regonumber);
            $customer = Mage::getModel('customer/customer')->load($data['customerId']);
            if ($vehicle->count() <= 0): //check if regonumber does not exists
                $resource = Mage::getModel('apdinteract_vehicle/vehicle');

            else:
                $customer = Mage::getModel('customer/customer')->load($data['customerId']);
                $vehicle_data = $vehicle->getFirstItem();
                $resource = Mage::getModel('apdinteract_vehicle/vehicle')->load($vehicle_data->getId());

            endif;

            $resource->setCustomerId($customer->getId());
            $resource->setWebsiteId($customer->getWebsiteId());
            $resource->setRegistration($regonumber);

            if (isset($data['vehiclemake']) || isset($data['vehiclemodel'])): //check if checkout page, if yes add/update vehicle info

                $resource->setMake($data['vehiclemake']);
                $resource->setManufactureYear($data['vehicleyear']);
                $resource->setModel($data['vehiclemodel']);
                $resource->setSeries($data['vehicleseries']);
                $resource->setDetails($data['vehicledetails']);

            endif;

            $resource->save(); //add or update details*/

        endif;
    }

    /**
     * Return customer Id from  guest email
     * @param $email
     * @return mixed
     */
    protected function _guestCustomer($email) {
        $customer = Mage::getModel('customer/customer')->getCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('email', $email)->getData();

        if ((bool) count($customer)) {
            return $customer[0]['entity_id'];
        }
    }

    public function sendToSFConsumer($vir_id, $action = null) {


        $vir = Mage::getModel('apdinteract_vir/order')->load($vir_id);
        $request = $vir->getData();

        $sao = Mage::getModel("apdinteract_salesforce/process_business_virconsumer", $request);

        try {
            if ($action == 'update')
                $result = $sao->update()->getResult();
            else
                $result = $sao->add()->getResult();            

            if ((!isset($result->id) || is_array($result))):
                Mage::log($result[0]->errorCode . ':' . $result[0]->message, null, 'virconsumer_error.log');
            else:
                Mage::getModel('apdinteract_salesforce/dictionary')->saveDictionary($vir, $result->id);
                Mage::log($result->id, null, 'virconsumer_success.log');
            endif;
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
	
	public function sendToSFHealthCheck($request, $action = null, $healthcheckid ) {

        $helper = Mage::helper('apdinteract_salesforce');      
        
        if (isset($healthcheckid) || isset($request['healthcheckid']) && $request['healthcheckid'] > 0):
            if ($healthcheckid <= 0)
                $hid = $request['healthcheckid'];
            else
                $hid = $healthcheckid;
            echo $hid;

            $hc = Mage::getModel('apdinteract_vir/healthcheck')->load($hid);
            try {
                $sf_id = $helper->getSFId($hid, get_class(Mage::getModel('apdinteract_vir/healthcheck')));  
                $request['salesforce_id'] = $sf_id;
                $sao = Mage::getModel("apdinteract_salesforce/process_business_healthcheck", $request);
                if ($sf_id == '0'):                    
                    $result = $sao->add()->getResult();
                else:                       
                    $result = $sao->update()->getResult();                    
                endif;
                if ((!isset($result->id) || is_array($result))):
                    Mage::log($result[0]->errorCode . ':' . $result[0]->message, null, 'virhc_error.log');
                else:
                    Mage::getModel('apdinteract_salesforce/dictionary')->saveDictionary($hc, $result->id);
                    Mage::log($result->id, null, 'virhc_success.log');
                endif;
            } catch (Exception $e) {

                Mage::logException($e);
            }
        endif;
    }
    
    public function deleteToSfConsumer($vir_id) {

        $sao = Mage::getModel("apdinteract_salesforce/process_business_virconsumer", $vir_id);
        try {
            $result = $sao->delete()->getResult();

            if (!isset($result->id)):
                Mage::log($result[0]->errorCode . ':' . $result[0]->message, null, 'virconsumer_error.log');
            else:
                Mage::log($result->id, null, 'virconsumer_success.log');
            endif;
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }

    public function deleteToSfCommercial($vir_id) {

        $sao = Mage::getModel("apdinteract_salesforce/process_business_vircommercial", $vir_id);
        try {
            $result = $sao->delete()->getResult();

            if (!isset($result->id)):
                Mage::log($result[0]->errorCode . ':' . $result[0]->message, null, 'vircommercial_error.log');
            else:
                Mage::log($result->id, null, 'vircommercial_success.log');
            endif;
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
    
    
    
    public function sendToSFCommercial($vir_id, $action = null) {


        $vir = Mage::getModel('apdinteract_vir/ordercommercial')->load($vir_id);
        $request = $this->_updateValues($vir->getData());
        

        $sao = Mage::getModel("apdinteract_salesforce/process_business_vircommercial", $request);

        try {
            if ($action == 'update')
                $result = $sao->update()->getResult();
            else
                $result = $sao->add()->getResult();
                        
            if ((!isset($result->id) || is_array($result))):
                Mage::log($result[0]->errorCode . ':' . $result[0]->message, null, 'vircommercial_error.log');
            else:
                Mage::getModel('apdinteract_salesforce/dictionary')->saveDictionary($vir, $result->id);
                Mage::log($result->id, null, 'vircommercial_success.log');
            endif;
        } catch (Exception $e) {
            Mage::logException($e);
        }
    }
    
    private function _updateValues($array) {
        foreach($array as $key=>$value):
            if($value==null)
                $array[$key] = 0;
        endforeach;
        
        return $array;
        
    }

    /*
     * This method get the store name by loading the current Vir Id
     * @param int - Vir ID
     * @return string - Store name
     */
    public function getStoreName($id){
        $mapping = Mage::getModel('apdinteract_vir/orderstoremapping')->load($id,'vir_id');
        if ($mapping->getId()){
            return Mage::helper('pickupinstore')->getStorebyId($mapping->getStoreId())->getTitle();
        }
        return;
    }

    /**
     * @param $vir_id
     * @param $store_id
     * @param $type
     * @throws Exception
     */
    public function virMapper($vir_id, $store_id, $type){

        $_data = $this->loadVirMapper($vir_id,$type);

        if($_data){
            $mapperModel = Mage::getModel('apdinteract_vir/orderstoremapping')->load($_data['entity_id']);
            $mapperModel->setStoreId($store_id);
            $mapperModel->save();
        }else{
            $mapperModel =  Mage::getModel('apdinteract_vir/orderstoremapping');
            $mapperModel->setVirId($vir_id);
            $mapperModel->setStoreId($store_id);
            $mapperModel->setVirType($type);
            $mapperModel->save();
        }

    }

    /**
     * @param $vir_id
     * @param int $type
     */
    public function loadVirMapper($vir_id, $type = 0){

        $mapping = Mage::getModel('apdinteract_vir/orderstoremapping')->getCollection();
        $mapping->addFieldToSelect('*');
        $mapping->addFieldToFilter('vir_id',array('eq' => $vir_id));
        $mapping->addFieldToFilter('vir_type',array('eq' => $type));
        $mapping->getFirstItem();

        if(count($mapping->getData()) > 0){
            return $mapping->getData()[0];
        }else{
            return;
        }

    }


}
