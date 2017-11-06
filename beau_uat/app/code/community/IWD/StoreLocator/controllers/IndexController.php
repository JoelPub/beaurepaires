<?php
class IWD_StoreLocator_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout(array('default', 'dealers_index_index'));

        $title = Mage::getStoreConfig('storelocator/meta/title');
        $description = Mage::getStoreConfig('storelocator/meta/description');
        $keywords = Mage::getStoreConfig('storelocator/meta/keywords');

        $head = $this->getLayout()->getBlock('head');
        $get =Mage::app()->getRequest();
        $session = Mage::getSingleton('core/session');
        
        if(isset($_GET['filters']) || isset($_GET['address'])) :
            
            $session->setFilters($get->getParam('filters'));          
            $session->setAddress($get->getParam('address'));
            $this->_redirect("store-locator");
            
        elseif($this->getRequest()->isPost()):    
            $session->unsFilters();
            $session->unsAddress();
        endif;
         
        if($session->getFilters() !='' || $session->getAddress()!=''):
            $_POST['filters'] = $session->getFilters();
            $_POST['address'] = $session->getAddress();
        endif;
        
        if (!empty($title)) {
            $head->setTitle($title);
        }

        if (!empty($description)) {
            $head->setDescription($description);
        }

        if (!empty($keywords)) {
            $head->setKeywords($keywords);
        }

        $this->renderLayout();
    }

    public function storedetailAction() {
    	$suffix = Mage::getStoreConfig('design/head/title_suffix');
    	$model = Mage::getModel('storelocator/stores');
        // Load by ID (eg /slocator/index/storedetail/id/2242
        $store_id = Mage::app()->getRequest()->getParam('id');

        if (!empty($store_id)) {
            $store_details = $model->load($store_id);
			if($store_details->getIsActive()){
				
				$description = $store_details->getMetaDescription();
				if (empty($description)) {
					$description = Mage::getStoreConfig('storelocator/meta/description');
				}
				
				$this->loadLayout();
				if($store_details->getPageTitle()=="")
					$pageTitle = $store_details->getTitle();
				else
					$pageTitle = $store_details->getPageTitle();
					
				$this->getLayout()->getBlock('head')->setTitle($pageTitle)->setDescription($description);
			}else{
				
				$this->_forward('noRoute');
			}
			
        }
        else {
            //possibly having store and url keys as parameter
            $storeUrl = Mage::app()->getRequest()->getParam('store');
            if (!empty($storeUrl)) {
                $store = Mage::getResourceModel('storelocator/stores_collection')
                    ->addFieldToFilter('url', $storeUrl)
                    ->getFirstItem(); 
				$this->loadLayout();
                $this->getLayout()->getBlock('head')->setTitle($store->getTitle());
            } 
        }
		
        $this->renderLayout();
        
         
    }
    
    public function storeregionAction() {
        $currentUrl = Mage::helper('core/url')->getCurrentUrl();
        $url = Mage::getSingleton('core/url')->parseUrl($currentUrl);
        $path = $url->getPath();
        $explode_slash = explode("/",$path);        
        
        
    	$store = Mage::getResourceModel('storelocator/region_collection')
                    ->addFieldToFilter('url_key', $explode_slash[2])
                    ->getFirstItem(); 
                    
        $title = $store->getPageTitle();
        $description = $store->getPageDescription();
        
    	$this->loadLayout();
    	$this->getLayout()->getBlock('head')->setTitle($title)->setDescription($description);
    	$this->renderLayout();
    }
    
    public function setStoreAction(){
        $storeId = $this->getRequest()->getParam('id');
        $response = 0;
        Mage::getSingleton('core/session')->unsStorelocation(); //unset
        
        if($storeId){
            Mage::getSingleton('core/session')->setStorelocation($storeId);
            Mage::getModel('core/cookie')->set('store_id',$storeId, 86400 * 60);
            $response = 1;
        }
        
        echo $response;
    }
    
}
