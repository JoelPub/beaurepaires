<?php
class ApdInteract_Customer_Helper_Data extends Mage_Core_Helper_Abstract
{
        /*
         * Assigned customer group.
         */
        private  $mobility_name = 'Mobility Subscriber';

       /*
        * Check if customer is in mobility subscription
        *
        * @return bool
        */
        public function mobilitySubscriber(){

            $var = false;

            if(Mage::getSingleton('customer/session')->isLoggedIn()){
                $groupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
                $groupName = Mage::getModel('customer/group')->load($groupId)->getCustomerGroupCode();

                if($groupName == $this->mobility_name){
                    $var = true;

                }
            }

            return $var;
        }
        
        public function sendDetails($customer_id) {
        $url = Mage::getStoreConfig('sync/sync/apdinteract_order_ordersaveapi', Mage::app()->getStore()) . 'addcustomer/';
        $customer = Mage::getModel('customer/customer')->load($customer_id);
        $defaultBilling = $customer->getDefaultBillingAddress();
        $data = array();
        $data['first_name'] = $customer->getFirstname();
        $data['last_name'] = $customer->getLastname();
        $data['email'] = $customer->getEmail();
        if ($defaultBilling) {
            $data['mobile_number'] = $defaultBilling->getMobile();
            $data['phone_number'] = $defaultBilling->getTelephone();
            $data['address'] = $defaultBilling->getStreet();
            $data['city'] = $defaultBilling->getCity();
            $data['state'] = $defaultBilling->getRegion();
            $data['zip_code'] = $defaultBilling->getPostcode();
        }
        $data['magento_customer_id'] = $customer_id;
        $json = json_encode($data);

        $response = json_decode(Mage::helper('apdinteract_order')->connectCurl($url, $json), true);
    }


    /**
     * @param $string
     * @return bool
     */
    public function validatePassword($string){

        $is_valid = false;
        $validate = preg_match('/(?=.*[a-z])(?=.*[0-9]).{7,}/i', $string);

        if($validate){
            $is_valid = true;
        }

        return $is_valid;
    }

}
?>