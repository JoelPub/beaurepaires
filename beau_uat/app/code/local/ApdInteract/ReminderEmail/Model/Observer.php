<?php
class ApdInteract_ReminderEmail_Model_Observer {
	
	public function sendYourNextServiceEmail() {
		
		$helper = Mage::helper('apdinteract_reminderemail');
		if ($helper->isNextServiceEmailEnabled()){
			
			$noticePeriod = $helper->getNoticePeriod();
			// currently set to days for testing purposes
			// TODO -> update to correct notice period after QA signoff
			$date = date("d/m/Y", strtotime("+{$noticePeriod} days"));
			
			$orders = Mage::getModel('sales/order')->getCollection()
						->addAttributeToSelect('*')
						->addAttributeToFilter('delivery_date', $date)
						->setPageSize(5); //limit rows fetched for testing
			
			foreach ($orders as $order){
					
				if (($order->getStorelocation()) && ($order->getDeliveryDate()) && ($order->getDeliveryTime())){ // make sure to get only orders with booking date and time
					$storeId = $order->getStorelocation();
					
					$bookingType = $this->_getBookingType($order);
					$orderDetails = $this->_getOrderDetails($storeId,$order,$bookingType,$helper,$noticePeriod);
					$this->_send($orderDetails);
				}
			}
		}
	}
	
	private function _getBookingType($orderedItems){
		foreach ($orderedItems->getAllItems() as $item){
			$bookingType = '';
			$product = Mage::getModel('catalog/product')->load($item->getProductId());
			$ids = $product->getCategoryIds();
			$displayAgain = false; // need to add the flag to avoid recurring display of booking type text when there are multiple orders of product with the same category.
			
			if (($ids[0] == '41') && (!$displayAgain)){
				$bookingType = 'tyre fitting';
				$displayAgain = true;
			}elseif (($ids[0] == '42') && (!$displayAgain)){
				$bookingType .= ' wheel fitting';
				$displayAgain = true;
			}elseif (($ids[0] == '43') && (!$displayAgain)){
				$bookingType .= ' battery fitting';
				$displayAgain = true;
			}else{
				$bookingType .= ' no fitting type';
			}
		
		}
		return $bookingType;
	}
	
	private function _getOrderDetails($id,$orderedItems,$type,$helper,$notice){
		return array (
				'customer_name'		 => $orderedItems->getBillingAddress()->getName(),
				'store_name'		 => $helper->getStore($id,'name'),
				'store_address'		 => $helper->getStore($id,'address'),
				'store_phone'		 => $helper->getStore($id,'phone'),
				'booking_date_time'	 => $orderedItems->getDeliveryDate() . ' - ' . $orderedItems->getDeliveryTime(),
				'booking_type'		 => $type,
				'months'			 => $notice,
				'increment_id'		 => $orderedItems->getIncrementId(),
				'hide_email'         => $orderedItems->getCustomerEmail(),
				'customer_email'     => 'sdialino@apdgroup.com'
		);
		
	}
	
	private function _send($details){
		
		$template = Mage::helper('apdinteract_reminderemail')->getNextServiceEmailTemplate();
		if (!$template){
			$email = Mage::getModel('core/email_template')->loadByCode('Your Next Service Email');
		}else{
			$email = Mage::getModel('core/email_template')->load($template);
		}
		
		$translate = Mage::getSingleton('core/translate');
		
		if($email->getId())
		{
				
			$from_email  = 'sdialino@apdgroup.com';
			$from_name   = 'Beaurepaires';
			// This $sender are not being used since we are using Silverpop extension but is needed as parameter for sendTransactional()
			$sender      = array('name'=> $from_name,
							'email' => $from_email);
			$storeId     = Mage::app()->getStore()->getStoreId();
			$name  = null; //Recipient name
		
			//Recipient email, currently hardcoded to my email to avoid escalations! 
			#TODO -> update the correct recipient after QA signoff
			$email_address = $details['customer_email'];
			$model = $email->setReplyTo($sender['email']);
		
			try {
				$model->sendTransactional($email->getId(), $sender, $email_address, $name, $details, $storeId);
				if(!$email->getSentSuccess()) {
					throw new exception("Something went wrong Next Service email was not sent");
				}
				$translate->setTranslateInline(true);
			} catch(Exception $e) {
				Mage::logException($e);
			}
		}
		
	}
	
}