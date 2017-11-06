<?php
class ApdInteract_ReminderEmail_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getStore($id,$field){
		$store = Mage::getModel('storelocator/stores')->load($id);
		if ($field == 'name'){
			$data = $store->getTitle();
		}elseif ($field == 'address'){
			$data = $store->getStreet() . ' ' . $store->getCity() . ' ' . $store->getRegion() . ' ' . $store->getPostalCode();
		}elseif ($field == 'phone'){
			$data = $store->getPhone();
		}else{
			$data = 'Unexpected field parameter';
		}
		
		return $data;
	}
	
	public function isNextServiceEmailEnabled(){
		return Mage::getStoreConfig('vir/next_service/enabled');
	}
	
	public function getNextServiceEmailTemplate(){
		return Mage::getStoreConfig('vir/next_service/next_service_template');
	}
	
	public function getNoticePeriod(){
		return Mage::getStoreConfig('vir/next_service/reminder_notice_period');
	}
}
