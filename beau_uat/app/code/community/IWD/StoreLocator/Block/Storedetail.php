<?php
class IWD_StoreLocator_Block_Storedetail extends Mage_Core_Block_Template {

    private $_store;
    private $_storeAddress;
    private $_urlencStoreAddress;
    
    protected function _prepareLayout() {
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb(
                    'store_detail', 
                    array(
                    'label'=>$this->getStoreTitle(), 
                    'title'=>$this->getStoreTitle()
                    ));
        }
        parent::_prepareLayout();
    }
    
    public function getLatLng() {
        $store = $this->getStore();
        return $store->getLatitude() . ',' . $store->getLongitude();
    }
    
    protected function _isFile($filename) {
        if (Mage::helper('core/file_storage_database')->checkDbUsage() && !is_file($filename)) {
            Mage::helper('core/file_storage_database')->saveFileToFilesystem($filename);
        }
        return is_file($filename);
    }
	
    
    public function getMarkerImageUrl() {        
        $folderName = IWD_StoreLocator_Model_System_Marker::UPLOAD_DIR;
        $storeConfig = Mage::getStoreConfig('storelocator/gmaps/marker');
        $faviconFile = Mage::getBaseUrl('media') . $folderName . '/' . $storeConfig;
        $absolutePath = Mage::getBaseDir('media') . '/' . $folderName . '/' . $storeConfig;

        if(!is_null($storeConfig) && $this->_isFile($absolutePath)) {
                $url = $faviconFile;
        } else {
                $url = Mage::getStoreConfig('web/unsecure/base_skin_url') . 'frontend/base/default/css/iwd/storelocator/images/marker.png';			
        }
        return $url;	
    }
    
    private function _getStoreAddress() {
        $store = $this->getStore();
        return  $store->getStreet() .' '. 
                $store->getCity() .' '. 
                $store->getRegion() .' '. 
                $store->getPostalCode();        
    }
    
    public function getStoreTitle() {
        if($this->getStore()) {
            return $this->getStore()->getTitle();
        }
        else {
            return "";
        }
    }
    
    public function getStoreAddress() {
        if (!isset($this->_storeAddress)) {
            $this->_storeAddress = $this->_getStoreAddress();
        }
        return $this->_storeAddress;
    }
    
    public function getUrlencAddress() {
        if (!isset($this->_urlencStoreAddress)) {
            $this->_urlencStoreAddress = urlencode($this->getStoreAddress());
        }
        return $this->_urlencStoreAddress;
    }
    
    public function getDirectionsUrl() {
        // https://maps.google.com?saddr=760+West+Genesee+Street+Syracuse+NY+13204&daddr=314+Avery+Avenue+Syracuse+NY+13204
        $address = $this->getUrlencAddress();
        $start = '';
        if ($saddress = $this->getStartAddress()) {
            $start = "saddr={$saddress}&";
        }
        return "https://maps.google.com?{$start}daddr={$address}";
    }
    
    public function getStaticMap() {        
        $address = $this->getUrlencAddress();
        //$zoom = 13;
        $size = '800x600';
        $type = 'roadmap';
        // &markers=size:mid%7Ccolor:red%7CSan+Francisco,CA
        return "https://maps.googleapis.com/maps/api/staticmap?size={$size}&maptype={$type}&markers=size:mid%7Ccolor:red%7C{$address}";
    }
    
    public function getStartAddress() {
        return Mage::app()->getRequest()->getParam('sa');
    }
    
    private function _getStore() {
        $model = Mage::getModel('storelocator/stores');
        // Load by ID (eg /slocator/index/storedetail/id/2242
        $store_id = Mage::app()->getRequest()->getParam('id');
        if (!empty($store_id)) {
            return $model->load($store_id);
        }
        
        // Or page url (eg /slocator/index/storedetail/store/bft-nerang
        $store_url = Mage::app()->getRequest()->getParam('store');
        if (!empty($store_url)) {           
            
            $collection = $model->getCollection()
			->addFieldToFilter('url', array('eq'=>$store_url));
            
            foreach ($collection as $store) {
                $id = $store->getId();
                $url = $store->getUrl();
                if (strtolower($url) == strtolower($store_url)){
                    return $store;
                }
            }
        }

        // Or title (eg /slocator/index/storedetail/n/BFT+NERANG
        // Doesn't work yet
        $store_name = Mage::app()->getRequest()->getParam('n');
        if (!empty($store_name)) {
            $store = $model->getCollection()->load($store_name, 'title');
            $id = $store->getId();
            if (!empty($id)) {
                return $store;
            }
        }
        
        // todo: if we got to here, we need to redirect to 404
        // for now, just return an empty model
        return $model;
    }
    
    public function getStore() {
        if (!isset($this->_store)) {
            $this->_store = $this->_getStore();
        }
        return $this->_store;
    }

    /**
     * @param $identifier
     * @return $static block object
     * This function returns the marketing message block for a particular store.
     */
    public function getMarketingMessageBlock($identifier){
        if (!empty($identifier)){
            $block = Mage::getModel('cms/block')->load($identifier,'identifier');
            if ($block->getId() && $block->getIsActive() == 1){
                return $this->getLayout()->createBlock('cms/block')->setBlockId($identifier)->toHtml();
            }
        }
    }
}