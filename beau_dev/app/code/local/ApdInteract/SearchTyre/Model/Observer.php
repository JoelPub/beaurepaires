<?php

class ApdInteract_Searchtyre_Model_Observer {

    protected function _getSession() {
        return Mage::getSingleton('checkout/session');
    }
    
    public function checkIfValid($observer) {        
        $request = Mage::app()->getFrontController()->getRequest();               
        $id = $request->getParam('product');        
        $sameSegment = Mage::helper('searchtyre')->checkTypeofProduct($id);

        if (!$sameSegment) {
            $message = 'Please choose either Consumer Products or Commercial Products.<br>You cannot proceed with both Categories of Items in the Cart';
            $this->_getSession()->addError($message);
            $url = Mage::helper('core/http')->getHttpReferer() ? Mage::helper('core/http')->getHttpReferer()  : Mage::getUrl();
            Mage::app()->getFrontController()->getResponse()->setRedirect($url);
            Mage::app()->getResponse()->sendResponse();
            exit;
        }    
        
    }


    /**
     *
     * Vehicle Logic API montioring Trigger
     *
     * @param $schedule
     */
    public function pingVehicleLogicApi($schedule) {

        $response = Mage::getModel('searchtyre/apimonitoring ')->pingApi();

        //If no error found in response then ignore.
        if(!$response['error'] || !$emailAddress = Mage::getStoreConfig('system/enterprise_import_export_log/error_email')) {
            return false;
        }

        //send email
        $message = "";
        $message .= "Vehicle Logic API response ";
        $message .= "<br/> Status Code: ".((isset($response['code'])) ? $response['code']: '');
        $message .= "<br/> Error: ".((isset($response['message'])) ? $response['message']: '');
        $subject = "Vehicle Logic Api Monitoring error";

        //Can send api error email
        if(Mage::helper('searchtyre')->canSendApiErrorEmail()) {
            Mage::helper('searchtyre')->sendEmail($subject, $message, $emailAddress);
            Mage::helper('searchtyre')->setEmailTriggerTime();
        }

    }

   

}
