<?php
class IWD_StoreLocator_Model_Observer{
	
	public function checkRequiredModules($observer){
		$cache = Mage::app()->getCache();
		
		if (Mage::getSingleton('admin/session')->isLoggedIn()) {
			if (!Mage::getConfig()->getModuleConfig('IWD_All')->is('active', 'true')){
				if ($cache->load("iwd_storelocator")===false){
					$message = 'Important: Please setup IWD_ALL in order to finish <strong>IWD Store Locator</strong> installation.<br />
						Please download <a href="http://iwdextensions.com/media/modules/iwd_all.tgz" target="_blank">IWD_ALL</a> and setup it via Magento Connect.<br />
						Please refer to installation <a href="https://docs.google.com/document/d/1Q2FmWcv4lIipqPR0QhaLVQs1IzrrYrN97XQds1MyJ_0/edit" target="_blank">guide</a>';
				
					Mage::getSingleton('adminhtml/session')->addNotice($message);
					$cache->save('true', 'iwd_storelocator', array("iwd_storelocator"), $lifeTime=5);
				}
			}
		}
	}
        
        public function addUrlRewrite($event) {
        $store = $event->getObject();

            $ids = $this->_getStoreId($store->getId());

            $urlKey = $store->getUrl();
            if (empty($urlKey)) {
                $urlKey = $store->getTitle();
            }
            $urlKeyClean = $this->stringToUrlKey($urlKey);
            if ($urlKeyClean != $urlKey) {
                $store->setUrl($urlKeyClean)->save();
            }

            $toUrl = 'slocator/index/storedetail/id/' . $store->getId(); // slocator/index/storedetail/id/2479
            $fromUrl = Mage::helper('storelocator')->getRoute() . '/' . $urlKeyClean;
            $all= false;
            $storeIds = array();
            //add to specfic stores
            foreach ($ids as $id):               
                $store_id = $id->getStoreId();
                if($store_id>0):   
                    $storeIds[] = $store_id;
                else:
                    $all= true;
                endif;
            endforeach;
            
            // fetch to all stores
            if($all):
                $storeIds = $this->_fetchAllStores();
            endif;
            
            $this->_addToStores($storeIds, $fromUrl, $toUrl);

//            die('addUrlRewrite Called');
    }
    
    /**
    * Add url key per store
    *
    * @param array $ids
    * @param string $fromUrl
    * @param string $toUrl
    * @return 
    */
    private function _addToStores($ids, $fromUrl, $toUrl) {
       foreach($ids as $id):
           $this->createUrlRewrite($fromUrl, $toUrl, $id);
       endforeach;
    }
    
    /**
    * Fetch all Store Ids
    *
    * @return $array;
    */
    private function _fetchAllStores() {
        
        $storeIds = array();
        $allStores = Mage::app()->getStores();
        foreach ($allStores as $_eachStoreId => $val):
            $storeIds[] = Mage::app()->getStore($_eachStoreId)->getId();
        endforeach;
        return $storeIds;
    }

    /**
    * Fetch Store Ids base on main table id
    *
    * @param int $id; 
    * @return int $id;
    */
    private function _getStoreId($id) {
            $stores = Mage::getModel('storelocator/store');
            $ids = $stores->getCollection()
                          ->addFieldToFilter('locatorstore',$id); 
            
            return $ids;
            
        }
		
        // Move to storelocator_save_after_event
        public function createUrlRewrite($fromUrl, $toUrl, $defaultStoreId = 1) {
        // Create rewrite:
        /** @var Enterprise_UrlRewrite_Model_Redirect $rewrite */
        $rewrite = Mage::getModel('enterprise_urlrewrite/redirect');

        // Assuming a single store
        //$defaultStoreId = 1; //Mage::app()
//                ->getWebsite(true)
//                ->getDefaultGroup()
//                ->getDefaultStoreId(); // 1
        // Attempt loading it first, to prevent duplicates:
        $rewrite->loadByRequestPath($fromUrl, $defaultStoreId);
        $rewrite->setStoreId($defaultStoreId);
        $rewrite->setOptions(''); // 'RP'
        $rewrite->setIdentifier($fromUrl);
        $rewrite->setTargetPath($toUrl);
        $rewrite->setEntityType(Mage_Core_Model_Url_Rewrite::TYPE_CUSTOM);
        $rewrite->save();
    }

    public function stringToUrlKey($string) {
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $string);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
        return $clean;
    }

        
}