<?php
class IWD_StoreLocator_Block_Storeregion extends Mage_Core_Block_Template {
	
	protected function _prepareLayout() {
		if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
			$breadcrumbs->addCrumb(
					'store_region',
					array(
							'label'=> $this->_getUrlKey(),
							'title'=> $this->_getUrlKey()
					));
		}
		parent::_prepareLayout();
	}
	
	public function getFilteredStores(){
		$area = $this->_getUrlKey();
		$region = Mage::getModel('storelocator/region')->load($area,'url_key');
		 
		$store = Mage::getModel('storelocator/stores')->getCollection()
		    	->addFieldToSelect('*')
		    	->addFieldToFilter( 'parent_region_id', $region->getId());
		
		return $store;
	}
	
	private function _getUrlKey(){
		$urlKey = Mage::registry('url-key');
		if(($urlKey == 'act') || ($urlKey == 'nsw') || ($urlKey == 'wa') || ($urlKey == 'sa')){
			$key = strtoupper($urlKey);
		}elseif (strpos($urlKey, '-')){
			$key = str_replace('-', " ", $urlKey);
			$key = ucwords($key);
		}
		else{
			$key = ucfirst($urlKey);
		}
		return 	$key;
	}
}