<?php
require 'Mage/Newsletter/controllers/ManageController.php';
class ApdInteract_Newsletter_ManageController  extends Mage_Newsletter_ManageController
{    

    public function saveAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('account/dashboard');
        }

        try {
            Mage::getSingleton('customer/session')->getCustomer()
            ->setStoreId(Mage::app()->getStore()->getId())
            ->setIsSubscribed((boolean)$this->getRequest()->getParam('is_subscribed', false))
            ->save();
            if ((boolean)$this->getRequest()->getParam('is_subscribed', false)) {
                Mage::getSingleton('customer/session')->addSuccess($this->__('The subscription has been saved.'));
            } else {
                Mage::getSingleton('customer/session')->addSuccess($this->__('The subscription has been removed.'));
            }
			
        }
        catch (Exception $e) {
            Mage::getSingleton('customer/session')->addError($this->__('An error occurred while saving your subscription.'));
        }
        $this->_redirect('account/communications/');
    }
}
