<?php
class IWD_StoreLocator_JsonController extends Mage_Core_Controller_Front_Action{
	
	public function regionAction(){
		
		$region = Mage::helper('storelocator')->prepareRegion($this);
		
	}
	 
	
	public function searchAction(){
		Mage::helper('storelocator')->prepareResultSearch($this);
	}
	
	public function geolocationAction(){
		Mage::helper('storelocator/geolocation')->getDataJson($this);
	}
        
        public function neareststoreAction(){
            Mage::helper('storelocator')->findNearestStore();
        }
        
         public function search_10_kmsAction() {
            Mage::helper('storelocator')->prepareResultSearch($this,true);
        }
} 