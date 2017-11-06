<?php
class ApdInteract_Gefinance_PaymentController extends Mage_Core_Controller_Front_Action
{
    
    public function redirectAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $this->getResponse()->setBody($this->getLayout()->createBlock('gefinance/standard_redirect')->toHtml());
    }
    
    public function cancelledAction()
    {
        $this->loadLayout();
        $this->renderLayout();
        
    }
    
    public function errorAction()
    {
        $this->_checkResponse();
        
    }
    
    public function failedAction()
    {
        
        $this->loadLayout();
        $this->renderLayout();
    }
    
    private function _map_values()
    {
        foreach ($_POST as $key => $val) {
            $map[$key] = $val;
        }
        
        return $map;
    }
    
    public function responseAction()
    {
        
        $this->_checkResponse();
    }
    
    public function successAction()
    {
        
        $this->_checkResponse();
        
    }
    
    public function backAction() {
    	 $standard = Mage::getModel('gefinance/paymentmethod');
    	 $appointment_id = Mage::getSingleton("core/session")->getAppointmentid();
    	 $transactionId = Mage::getSingleton('core/session')->getPOrderId(); //'145000140';
		 $standard->registerTransaction(ApdInteract_Gefinance_Model_Variables::DECISION_CANCELLED, '00', 'n/a', $transactionId, '0');	
		 $standard->reCreateQuoteFromOrder($transactionId);	 
		 Mage::Helper('sync')->_deleteappointment($appointment_id);
		 $this->_redirect('checkout/onepage/index/step/payment', array(
                    '_secure' => true
         ));
                		  
		 
	}
		
    
    public function _checkResponse()
    {
        $map = $this->_map_values();
        $key = Mage::helper('gefinance')->getConfig('ge_key');        
        $verified = Mage::helper('gefinance')->verifyTransactionSignature($map, $key);
        $standard = Mage::getModel('gefinance/paymentmethod');
        $appointment_id = Mage::getSingleton("core/session")->getAppointmentid();
        
        Mage::log('verify'.$verified.":", null, 'mylogfile.log');
        
        if ($verified) {
            $reasonCode         = $map[ApdInteract_Gefinance_Model_Variables::REASONCODE];
            $reasonMsg          = $map[ApdInteract_Gefinance_Model_Variables::REASONMESSAGE];
            $decision           = $map[ApdInteract_Gefinance_Model_Variables::DECISION];
            $transactionId      = $map[ApdInteract_Gefinance_Model_Variables::MERCHANTREFERENCECODE];
            $ccAuthReply_amount = $map[ApdInteract_Gefinance_Model_Variables::CCAUTHREPLY_AMOUNT];
            
            Mage::getSingleton('core/session')->setPaymentErrorCode($reasonCode);
            Mage::getSingleton('core/session')->setPaymentErrorDesc($reasonMsg);
            Mage::getSingleton('core/session')->setPOrderId($transactionId);            
            if ($reasonCode == '00' && $decision == ApdInteract_Gefinance_Model_Variables::DECISION_ACCEPT) {
                $standard->registerTransaction($decision, $reasonCode, $reasonMsg, $transactionId, $ccAuthReply_amount);
                Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
                
                $this->_redirect('checkout/onepage/success', array(
                    '_secure' => true
                ));
                
            } else {
                $standard->registerTransaction($decision, $reasonCode, $reasonMsg, $transactionId, $ccAuthReply_amount);
                $standard->reCreateQuoteFromOrder($transactionId);
                Mage::Helper('sync')->_deleteappointment($appointment_id);
                $this->_redirect('gefinance/payment/failed', array(
                    '_secure' => true
                ));
            }
        } else {        	
            $standard->registerTransaction($decision, $reasonCode, $reasonMsg, $transactionId, $ccAuthReply_amount);
            $standard->reCreateQuoteFromOrder($transactionId);
            Mage::Helper('sync')->_deleteappointment($appointment_id);
            $this->_redirect('gefinance/payment/failed', array(
                '_secure' => true
            ));
        }
    }
    
}