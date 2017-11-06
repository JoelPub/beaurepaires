<?php
class ApdInteract_Subscribe_Model_Observer
{

	/**
	 * also include assigning of vehicle details from checkout
	 * @param Varien_Event_Observer $observer
	 * @throws Exception
	 * @throws Mage_Core_Exception
	 */
	public function AssignNewletter(Varien_Event_Observer $observer)
	{

		$session = Mage::getSingleton("core/session",  array("name"=>"frontend"));
		$post = Mage::app()->getFrontController()->getRequest()->getPost();

		if(isset($post['billing'])){
			if(Mage::getSingleton("core/session")->getIsConsumer()){
				//Consumer segment

				if(isset($post['existing_vehicle']) && $post['existing_vehicle'] != "new"){

					$vehicleCollection = Mage::getModel('apdinteract_vehicle/vehicle')->load($post['existing_vehicle'])->getData();
					if(count($vehicleCollection)){

						$vehicleDetailsId = json_decode($vehicleCollection['details'],true);
						$post['registration_number'] = $vehicleCollection['registration'];
					}
				}else{

					$vehicleDetailsId = array('make-tyres'  => $post['make-tyres'],'year-tyres'  => $post['year-tyres'],'model-tyres' => $post['model-tyres'],'series-tyres'=> $post['series-tyres']);
				}

				Mage::getSingleton("core/session")->setDetailTyres($post['details-tyres']);
				Mage::getSingleton("core/session")->setVehicleDetailsId($vehicleDetailsId);
				Mage::getSingleton("core/session")->setOdometer($post['odometer']);
				Mage::getSingleton("core/session")->setLastWheelAlignment($post['last_wheel_alignment']);
				Mage::getSingleton("core/session")->setLastWheelBalance($post['last_wheel_balance']);
			}else{
				//Commercial segment
				Mage::getSingleton("core/session")->setFleetNumber($post['fleet_number']);
				Mage::getSingleton("core/session")->setSpeedometerHub($post['speedometer_hub']);
			}

			Mage::getSingleton("core/session")->setRegistrationNumber($post['registration_number']);
			if(isset($post['create_account'])) {
				Mage::getSingleton("core/session")->setCreateAccount($post['create_account']);
			}
		}

		if(isset($post['billing']['email'])) {

			$email = $post['billing']['email'];

			if (isset($post['is_subscribed']) && $post['is_subscribed']==1){

				$emailExist = Mage::getModel('newsletter/subscriber')->load($email, 'subscriber_email');
				if (!$emailExist->getId()) {

					$status = Mage::getModel('newsletter/subscriber')->subscribe($email);
				}
				else
				{
					$customer = Mage::getModel("customer/customer");
					$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
					$customer->loadByEmail($post['billing']['email']);
					if($customer->getId())
					{
						$subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
						$subscriber->setStatus(Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED);
						$subscriber->save();
						$customerData = Mage::getModel('customer/customer')->load($customer->getId());
						$customerData->setEmailSpecialOffers(1);
						$customerData->setEmailProductNews(1);
						$customerData->save();
					}

				}

			}

		}

	}

}