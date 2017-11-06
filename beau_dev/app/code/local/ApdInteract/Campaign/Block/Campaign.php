<?php

class ApdInteract_Campaign_Block_Campaign extends Mage_Core_Block_Template {

    public function SaveCampaign() {

        $campaign = Mage::getModel("campaign/campaign");
        $postData = $this->getRequest()->getPost();
        //if (!Mage::Helper("campaign")->checkIfDuplicate($postData['email'])) :
            $store = Mage::app()->getStore();
            $postData['store_id'] = $store->getId();
            $url = Mage::getStoreConfig('campaign/campaign/campaign_url');
            $postData['date_added'] = Mage::Helper('campaign')->convertDateTime();
            
            $campaign->addData($postData)
                    ->save();
            if (Mage::getStoreConfig('campaign/campaign/enable_sending') == 1):
                $this->_sendEmailAdmin($postData); // send email to admin
                $this->_sendEmailVisitor($postData); //send email to customer
            endif;
            
            Mage::Helper("campaign")->clearSession();
            Mage::app()->getResponse()->setRedirect($url);
        //else:
            //Mage::getSingleton("core/session")->addError("Email already subscribed.");
            //Mage::Helper("campaign")->addSession($postData);
            //Mage::app()->getResponse()->setRedirect("/taking-care-of-christmas");
        //endif;
    }

    private function _sendEmailAdmin($postData) {
        try {
            $postObject = new Varien_Object();
            $postObject->setData($postData);

            $store = Mage::app()->getStore();
            $storeId = $store->getId();
            $sender = array(
                'name' => $store->getName(),
                'email' => Mage::getStoreConfig('campaign/campaign/campaign_sender', $storeId)
            );

            $vars = array(
                'data' => $postObject,
            );
            $templateId = Mage::getStoreConfig('campaign/campaign/campaign_adminemail_tpl', $storeId);
            $mailTemplate = Mage::getModel('core/email_template');
            $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($postData['email'])
                    ->sendTransactional($templateId, $sender, Mage::getStoreConfig('campaign/campaign/campaign_recipient', $storeId), '', $vars, $storeId);
        } catch (Exception $e) {
            throw new Exception('No Template set on System->Configuration->APD Extension->Campaign Settings->Admin Email Template. Details: ' . $e);
        }
    }

    private function _sendEmailVisitor($postData) {
        try {

            $postObject = new Varien_Object();
            $postObject->setData($postData);

            $store = Mage::app()->getStore();
            $storeId = $store->getId();
            $sender = array(
                'name' => $store->getName(),
                'email' => Mage::getStoreConfig('campaign/campaign/campaign_sender', $storeId)
            );

            $vars = array(
                'data' => $postObject,
            );

            $templateId = Mage::getStoreConfig('campaign/campaign/campaign_customer_tpl', $storeId);
            $mailTemplate = Mage::getModel('core/email_template');
            $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($sender['sender'])
                    ->sendTransactional($templateId, $sender, $postData['email'], '', $vars, $storeId);
        } catch (Exception $e) {
            throw new Exception('No Template set on System->Configuration->APD Extension->Campaign Settings->Admin Customer Template. Details: ' . $e);
        }
    }

}
