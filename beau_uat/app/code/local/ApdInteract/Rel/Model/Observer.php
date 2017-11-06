<?php
class ApdInteract_Rel_Model_Observer {
    public function coreBlockAbstractToHtmlBefore($o)
    {
    	$canonicalUrl = null;
    	$hasQueryString = null;
        $block = $o->getBlock();
        
        $currentUrl = Mage::helper('core/url')->getCurrentUrl();
        
        // for some reason when current Url contains '?' magento replaces it with " resulting to NULL, that's why we need to separate the url into two parts.
        $hasQueryString = strpos($currentUrl, '?');
        if($hasQueryString){
        	$firstUrl = strstr($currentUrl, '?',1); //first part of URL
        	$secondUrl = substr($currentUrl, strpos($currentUrl, "?") + 1); //second part of URL
        }
        
        if ($hasQueryString){
        	$collection = $this->_getCanonicalModel()->getCollection()
        				->addFieldToSelect('*')
        				->addFieldToFilter('url',array('like' => "%{$firstUrl}%"))
        				->addFieldToFilter('url',array('like' => "%{$secondUrl}%"))
        				->setPageSize(1)
        				->getFirstItem();
        }else{
        	$collection = $this->_getCanonicalModel()->getCollection()
        				->addFieldToSelect('*')
			        	->addFieldToFilter('url',array('eq' => $currentUrl))
			        	->setPageSize(1)
			        	->getFirstItem();
        }
        
        if ($collection->getId()){
        	$canonicalUrl = $collection->getNewCanonicalUrl();
        }
        
        if($block instanceof Mage_Page_Block_Html_Head && $canonicalUrl) {
     	   $block->addLinkRel('canonical', $canonicalUrl);
        }
    }
    
    private function _getCanonicalModel(){
    	return Mage::getModel('apdinteract_rel/canonical');
    }
}