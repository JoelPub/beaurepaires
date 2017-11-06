<?php

class ApdInteract_Order_ApiController extends Mage_Core_Controller_Front_Action {

    protected $_customerInfo = array();

    protected $_customerAddress = array();

    private function checksecurity() {
        $urallowed = Mage::getStoreConfig('sync/sync/apdinteract_order_customerapi', Mage::app()->getStore());
        $refer_ar = explode('//',$_SERVER["HTTP_REFERER"]);
        
        if (strpos( $urallowed,$refer_ar[1]) !== false) {
            return;
        } else {
            return 'false';
        }
        
        
    }

    public function addAction() {
        $check = $this->checksecurity();
        if ($check == 'false') {
            exit('Not allowed to access this page');
        }

        $this->_customerInfo = array(
            'email'      => $this->_getPost('email'),
            'firstname' => $this->_getPost('first_name'),
            'lastname'  => $this->_getPost('last_name'),
        );

        $this->_customerAddress = array(
            'firstname' => $this->_getPost('first_name'),
            'lastname'  => $this->_getPost('last_name'),
            'street'     =>  array($this->_getPost('address'),$this->_getPost('street')
                        ),
            'city'       =>  $this->_getPost('city'),
            'company'    => $this->_getPost('company_name'),
            'telephone'  =>  $this->_getPost('phone_number'),
            'postcode'   => $this->_getPost('zip_code')
        );

        $customer = Mage::getModel("customer/customer");
        $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
        $websiteId = Mage::app()->getWebsite()->getId();
        $store = Mage::app()->getStore();

        //load customer by email id
        $customer->loadByEmail($this->_customerInfo['email']);
        $customer_array = $customer->getData();
        if ($customer_array['entity_id']) {

            unset($this->_customerInfo['email']);
            $updateCustomer = Mage::getModel("customer/customer")->load($customer_array['entity_id']);
            $updateCustomer->addData($this->_customerInfo);
            $updateCustomer->save();

            $this->_saveCustomerAddress($updateCustomer);
        } else {

            $newCustomer = Mage::getModel("customer/customer");
            $newCustomer->setWebsiteId($websiteId);
            $newCustomer->setGroupId(1);
            $newCustomer->setStore($store);
            $newCustomer->addData($this->_customerInfo);
            $newCustomer->setPassword('password1');

            try{
                $newCustomer->save();
            }catch (Exception $e){
                throw new exception('Customer add returned invalid response: ' . $e->getMessage());
            }


            $this->_saveCustomerAddress($newCustomer);
        }
    }

    private function _getPost($field){
        return $this->getRequest()->getPost($field);
    }

    private function _saveCustomerAddress($customer){
        $address = Mage::getModel("customer/address");

        if ($customer->getDefaultShipping() || $customer->getDefaultBilling()){
            $address->load($customer->getDefaultShipping());
            $address->addData($this->_customerAddress);
        }else{
            // meaning this is new customer with new billing/shipping address
            $address->setCustomerId($customer->getId())
                ->addData($this->_customerAddress)
                ->setCountryId('AU')
                ->setIsDefaultBilling('1')
                ->setIsDefaultShipping('1');
        }

        try{
            $address->setSaveInAddressBook('1')->save();
        }catch (Exception $e){
            throw new exception('Customer add returned invalid response: ' . $e->getMessage());
        }


        // commented for now, if one day the client realize they want to sync everything from DBC to Magento
        //        ->setPostcode($this->_getPost('zip_code'))
        //        ->setCity($this->_getPost('city'))
        //        ->setTelephone($this->_getPost('phone_number'))
        //        ->setStreet($this->_getPost('street'))


    }

    public function deleteAction() {
        $check = $this->checksecurity();
        if ($check == 'false') {
            exit('Not allowed to access this page');
        }
        
        Mage::register('isSecureArea', true);
        $customer_email = $this->getRequest()->getPost('email');
        $customer = Mage::getModel("customer/customer");
        $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
        //load customer by email id
        $customer->loadByEmail($customer_email);
        if ($customer->getId()) {
            $customer1 = Mage::getModel("customer/customer")->load($customer->getId());
            $customer1->delete();
        }
        Mage::unregister('isSecureArea');
    }

    public function updatevirAction() {
        $check = $this->checksecurity();
        if ($check == 'false') {
            exit('Not allowed to access this page');
        }
         
        $additional = array(
            'store_id' => $this->getRequest()->getPost('store_id'),
            'vir_type' => $this->getRequest()->getPost('vir_type'),
        );
        $appointmentId = $this->getRequest()->getPost('appointment_id');
        $newStatus = $this->getRequest()->getPost('status');
        $customer = $this->getRequest()->getPost('customer');
        $booking_datetime = $this->getRequest()->getPost('booking_start_datetime');

        Mage::helper('apdinteract_vir')->updateVirStatus($appointmentId, $newStatus, $customer, $booking_datetime, $additional );
    }

    public function checkbookingrecordAction()
    {
        $check = $this->checksecurity();
        if ($check == 'false') {
            exit('Not allowed to access this page');
        }
        $record = array("type","id");
        $appointmentId = $this->getRequest()->getParam('appointment_id');
        if(!empty($appointmentId)){

            $consumerTable = Mage::getModel('apdinteract_vir/order')
                ->getCollection()
                ->addFieldToSelect(array('parent_id','appointmentid'))
                ->addFieldToFilter('appointmentid', $appointmentId);

           if($consumerTable->count() > 0){
               $record = array(
                   "type" => "consumer",
                   "id"   => $consumerTable->getFirstItem()->getData('parent_id'),
               );
           }else{

               $commercialTable = Mage::getModel('apdinteract_vir/ordercommercial')
                   ->getCollection()
                   ->addFieldToSelect(array('parent_id','appointmentid'))
                   ->addFieldToFilter('appointmentid', $appointmentId);

               if($commercialTable->count() > 0){
                   $record = array(
                       "type" => "commercial",
                       "id"   => $commercialTable->getFirstItem()->getData('parent_id'),
                   );
               }
           }
        }

        echo Mage::helper('core')->jsonEncode($record);
    }

}
