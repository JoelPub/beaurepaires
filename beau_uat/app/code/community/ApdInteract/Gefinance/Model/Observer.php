<?php
class ApdInteract_Gefinance_Model_Observer
{   
   public function saveOrderQuoteToSession($observer){
        /* @var $event Varien_Event */
        $event = $observer->getEvent();
        /* @var $order Mage_Sales_Model_Order */
        $order = $event->getOrder();
        /* @var $quote Mage_Sales_Model_Quote */
        $quote = $event->getQuote();
              
        $session = Mage::getSingleton('checkout/session');
        $quoteId = $quote->getId();
        $orderId = $order->getId();
        $incrId = $order->getIncrementId();
        Mage::log("Saving quote  [$quoteId] and order [$incrId] to checkout/session");

        $session->setData('OrderId',$orderId);
        $session->setData('OrderIncrementId',$incrId);
        
        unset($event);
        unset($order);
        unset($quote);
        unset($session);
        
        return $this;
    }
    
    
     public function _clearappointments() {
    	    	
		$fromDate = '2015-07-01 23:59:59';
		$toDate = date('Y-m-d H:i:s', time()-3600);
		 
		/* Get the collection */
		$orders = Mage::getModel('sales/order')->getCollection()
		    ->addAttributeToFilter('created_at', array('from'=>$fromDate, 'to'=>$toDate))
		    ->addAttributeToFilter('status', array('eq' => 'pending'));

		foreach($orders as $order) {
			$order_id = $order->getId();
			$orderModel = Mage::getModel('sales/order');
            $orderModel->load($order_id);
			$orderModel->cancel();
			$orderModel->save();
			$appointment_id = $order->getAppointmentid();
			if($appointment_id>0) {
				//Mage::log($order_id);
				Mage::Helper('sync')->_deleteappointment($appointment_id);
				
			}
		}
		
	}
    
    private function _getParam($fieldname) {
        return Mage::app()->getRequest()->getParam($fieldname);
    }
    
    private function _checkValidationFlag() {
        $validationFlag = $this->_getParam('validationFlag');
        if ($validationFlag == 'Y') {
            return true;            
        }
        
        if ($validationFlag == 'N') {
            Mage::getSingleton('core/session')->addError('Sorry, GE Apply is not available right now. Please try again later');            
        }
        return false;        
    }
        
    public function controllerActionPredispatch($observer) {
        // Check for GeFinance params. 
        if (!$this->_checkValidationFlag()) {
            return;
        }
        
        $eapps_merchantData = $this->_getParam('eapps_merchantData'); 
        $eapps_hopData = $this->_getParam('eapps_hopData');  
        
        // For testing
//        $eapps_merchantData = 'PyXvU/FIceodOmUVKK1k1fejG9lTg3Toer5LgJnZiIbTsz6Zq+61unP+lcPqTTS4KliKy6QYb1DT+PSOvVqZavcPxnLXeOO5fsgxO7SYZpBWwT/jXVMVz7953+3QGP/P6zBxXak07vGMZ/oduKGjjzj125+ynTU6w8iCxAI2o69n634dVvwOqpZ1WGbFrJZZVBEvHCcLYaf7UK6kncnDJ5OcldCdj0qv6tgUNkDRgBaxixbvVUUidyIcnu2InLH1K7Bw+qpBFevcwZmFyEriKw==';       
//        $eapps_hopData = 'IyxmDUKgHUeqOJiadjEy8lVf9rgbKdHNKkSHevHjTEvZWOVHBP0XtpFVEVHxMnGJq1lVqyskfopvIuC5mI4qpg==';
        
        
        Mage::helper('gefinance')->handleGeApplyResponse($eapps_merchantData, $eapps_hopData);
    }
}