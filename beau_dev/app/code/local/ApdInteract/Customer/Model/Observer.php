<?php
class ApdInteract_Customer_Model_Observer
{

    protected $canSend = false; // TODO: fix code below, change to false
    
    public function sendToBooking(Varien_Event_Observer $observer)
    {
        $excludeController = array('api', 'onepage');
        if(in_array(Mage::app()->getRequest()->getControllerName(),$excludeController)){
            return;
        }

        if (!$this->canSend) {
            $url = Mage::getStoreConfig('sync/sync/apdinteract_order_ordersaveapi', Mage::app()->getStore()) . 'addcustomer/';
            if (empty($url)) {
                throw new exception('apdinteract_order_ordersaveapi value needs to be set in admin area');
            }

            $customer = Mage::getModel('customer/customer')->load($observer->getEvent()->getCustomer()->getId());
            $data                        = array();

            $data['first_name']          = $customer->getFirstname();
            $data['last_name']           = $customer->getLastname();
            $data['email']               = $customer->getEmail();
            $data['is_guest']            = $customer->getDormantFlag();
            $data['magento_customer_id'] = $customer->getId();

            //default billing address
            $defaultBilling = $customer->getPrimaryBillingAddress();
            if($defaultBilling){
                $data['mobile_number']       = $defaultBilling->getData('mobile');
                $data['phone_number']        = $defaultBilling->getData('telephone');
                $data['company_name']        = (string)$defaultBilling->getData('company');
                $data['address']             = $defaultBilling->getStreet()[0];
                $data['street']              = isset($defaultBilling->getStreet()[1]) ? $defaultBilling->getStreet()[1] : '';
                $data['city']                = $defaultBilling->getData('city');
                $data['state']               = $defaultBilling->getData('region_id');
                $data['zip_code']            = $defaultBilling->getData('postcode');
            }

            $json = json_encode($data);
            $response                    = json_decode(Mage::helper('apdinteract_order')->connectCurl($url, $json), true);
            // TODO: fix connectCurl function does not exist in helper error.


            /*if (!is_array($response)) {
               throw new exception('Customer add returned invalid response: ' . $response);
            }*/

            $this->canSend = true;
        }
    }

    /**
     * Set Expire session if password length below 7.
     * Redirect to customer acount edit
     *
     * @param Varien_Event_Observer $observer
     *
     */
    public function validateCustomerLogin(Varien_Event_Observer $observer){

        $post = Mage::app()->getRequest()->getParams();
        Mage::log("POST: ".$post['login']['password'], null, "social.debug.log");
        if(isset($post['login']['password']) && Mage::helper('core/string')->strlen($post['login']['password']) < 7){

            Mage::getSingleton('customer/session')->setPasswordExpired(true);
            Mage::getSingleton('customer/session')->addError('Your password has expired and must be changed.');
            Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl().'customer/account/edit/changepass/1')->sendResponse();
            exit;
        }
    }

    /**
     * If password expired locked user in changepass page.
     *
     * @param Varien_Event_Observer $observer
     */
    public function redirectToChangePass(Varien_Event_Observer $observer){

        $isPasswordExpired =  Mage::getSingleton('customer/session')->getPasswordExpired();
        $currentUri = rtrim(Mage::app()->getRequest()->getRequestUri(),"/");
        $excludeUri = array(
                            '/customer/account/edit/changepass/1',
                            '/customer/account/editPost',
                            '/customer/account/logout'
                        );
        
        if($isPasswordExpired && !in_array($currentUri,$excludeUri)){
            Mage::getSingleton('customer/session')->addError('Your password has expired and must be changed.');
            Mage::app()->getResponse()->setRedirect(Mage::getBaseUrl().'customer/account/edit/changepass/1')->sendResponse();
            exit;
        }
    }

}
