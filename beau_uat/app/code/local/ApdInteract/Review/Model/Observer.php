<?php

class ApdInteract_Review_Model_Observer {

    public function updateSF(Varien_Event_Observer $observer) {
        $this->checkSF();
        $object = $observer->getEvent()->getObject();
        $review_id = $object->getId();

        $ratings = Mage::app()->getRequest()->getParam('ratings');

        if (Mage::getDesign()->getArea() == 'adminhtml')
            $action = 'update';
        else
            $action = 'add';

        if (Mage::helper('addblock')->checkSF()): //check if salesforce module is enabled                                    
            Mage::helper('apdinteract_review')->sendToSF($review_id, $ratings, $action); // send to salesforce                        
        endif;
    }

    public function deleteToSF(Varien_Event_Observer $observer) {
        $this->checkSF();
        $object = $observer->getEvent()->getObject();
        $review_id = $object->getId();
        if (Mage::helper('addblock')->checkSF()): //check if salesforce module is enabled                                    
            Mage::helper('apdinteract_review')->deleteToSF($review_id); // send to salesforce                        
        endif;
    }

    public function checkSF() {
        if (Mage::helper('addblock')->checkSF()):
            Mage::helper('apdinteract_salesforce')->checkConnection();
        endif;
    }

}
