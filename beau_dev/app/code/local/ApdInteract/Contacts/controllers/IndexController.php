<?php

require_once "Mage/Contacts/controllers/IndexController.php";

class ApdInteract_Contacts_IndexController extends Mage_Contacts_IndexController {

    public function postAction() {
        $post = $this->getRequest()->getPost();
        if ($post) {

            $post = $this->_setDefaultValues($post);
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $enquiries = Mage::Helper('apdinteract_contacts')->getAllEnquiries();
                if (!empty($post['storename'])) {
                    $storeId = $this->_getStoreId($post['storename']);
                    $post['phone'] = $this->_getStorePhone($storeId);
                    $post['address'] = $this->_getStoreAddress($storeId);
                    Mage::getSingleton("core/session")->setStoreName($post['storename']);
                    // Mage::registry not working, opted to used session
                    // save to session to preserve and reuse posted variables($post['storename'])
                } else {
                    $storeName = Mage::getSingleton("core/session")->getStoreName();
                    $storeId = $this->_getStoreId($storeName);
                    $post['phone'] = $this->_getStorePhone($storeId);
                    $post['address'] = $this->_getStoreAddress($storeId);
                    $post['storename'] = $storeName;
                }

                $post['subscribe'] = $post['is_subscribed'];

                $post['customer_id'] = '';
                if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                    $customerData = Mage::getSingleton('customer/session')->getCustomer();
                    $post['customer_id'] = $customerData->getId();
                    $customerAddressId = Mage::getSingleton('customer/session')->getCustomer()->getDefaultBilling(); //oder getDefaultShipping
                    if ($customerAddressId) {
                        $address = Mage::getModel('customer/address')->load($customerAddressId);

                        if ($address->getCompany() != '')
                            $post['company'] = $address->getCompany();

                        $post['zip'] = $address->getPostcode();
                        $post['city'] = $address->getCity();
                        $street = $address->getStreet();
                        $post['street'] = $street[0] . " " . $street[1];
                        $post['telephone'] = $address->getTelephone();
                        $post['mobile'] = $address->getMobile();
                        $post['fax'] = $address->getFax();

                        $request['region'] = $address->getRegion();
                        $post['country'] = $address->getCountry();
                    }
                }
                $contact_recipient = $enquiries[$post['enquiry']];
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;

                if (!Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['comment']), 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }

                if (Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                    $error = true;
                }

                if ($error) {
                    throw new Exception();
                }


                $recipients = "";

                if (isset($post['is_subscribed'])) {
                    Mage::getModel('newsletter/subscriber')->subscribe($post['email']);
                }



                if (isset($post['storeemail'])) {

                    $mailTemplate = Mage::getModel('core/email_template');
                    $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                            ->setReplyTo($post['email'])
                            ->sendTransactional(
                                    Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE), Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER), $post['storeemail'], null, array('data' => $postObject)

                    );

                    if (!$mailTemplate->getSentSuccess()) {
                        //throw new Exception();
                    }
                }


                $mailTemplate_customer = Mage::getModel('core/email_template');
                $mailTemplate_customer->setDesignConfig(array('area' => 'frontend'))
                        ->setReplyTo($post['email'])
                        ->sendTransactional(
                                Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE), Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER), $post['email'], null, array('data' => $postObject)
                );

                $default = Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT);
                $sender = self::XML_PATH_EMAIL_SENDER;
                $template = self::XML_PATH_EMAIL_TEMPLATE;


                if (!$mailTemplate_customer->getSentSuccess()) {
                    //throw new Exception();
                }

                $mailTemplate_default = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate_default->setDesignConfig(array('area' => 'frontend'))
                        ->setReplyTo($post['email'])
                        ->sendTransactional(
                                Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE), Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER), $contact_recipient, null, array('data' => $postObject)
                );

                if (!$mailTemplate_default->getSentSuccess()) {
                    //throw new Exception();
                }

                if (Mage::helper('addblock')->checkSF()):
                    $sao = Mage::getModel("apdinteract_salesforce/process_business_lead", $post);
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
                endif;

                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                $this->_redirect('*/*/');

                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);
                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later' . $e->getMessage()));
                $this->_redirect('*/*/');
                return;
            }
        } else {
            $this->_redirect('*/*/');
        }
    }

    private function _getStorePhone($id) {
        return $this->_getStoreHelper()->getStore($id, 'phone');
    }

    private function _getStoreAddress($id) {
        return $this->_getStoreHelper()->getStore($id, 'address');
    }

    private function _getStoreId($title) {
        $store = Mage::getModel('storelocator/stores')->load($title, 'title');
        return $store->getId();
    }

    private function _getStoreHelper() {
        return Mage::helper('apdinteract_reminderemail');
    }

    private function _setDefaultValues($post) {

        $post['lastname'] = 'N/A';
        $post['company'] = 'N/A';
        $post['zip'] = '';
        $post['city'] = '';
        $post['street'] = '';
        $post['telephone'] = '';
        $post['fax'] = '';
        $post['mobile'] = '';
        $post['country'] = '';
        $post['source'] = 'Contact Us Form';

        return $post;
    }

}
