<?php
class ApdInteract_Addblock_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getModule(){
    	$module = null;
    	$module = Mage::app()->getRequest()->getModuleName();
    	
    	// searchresult module
    	return $module;
    }
    
    public function displayTyreSearchWidget(){
    	$type = Mage::app()->getRequest()->getParam('type');
    	if ($this->getModule() != null){
		    	if ($type == 'tyres'){
		    		return true;
		    	}else 
		    		return false;
    	}else {
    		return false;
    	}
    }
    
    public function displayWheelSearchWidget(){
    	$type = Mage::app()->getRequest()->getParam('type');
    	if ($this->getModule() != null){
		    	if ($type == 'wheels'){
		    		return true;
		    	}else
		    		return false;
	    }else {
    		return false;
    	}
    }
    
    public function checkSF() {
        $modules = Mage::getConfig()->getNode('modules')->children();
        $modulesArray = (array)$modules;
        
        if(isset($modulesArray['ApdInteract_Salesforce']) && $modulesArray['ApdInteract_Salesforce']->active=='true')
            return true;
        else
            return false;
    }


	/**
	 * @return bool
	 */
	public function isCatalogPage(){

		$result = false;
		$routeArray = array('searchresult','catalog','catalogsearch');
		$routeName = Mage::app()->getRequest()->getRouteName();;

		if(in_array($routeName,$routeArray)){
			$result = true;
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function addQuickFinderClass(){

		$class = "";
		if($this->isCatalogPage()){
			$class = "quick-finder-cdp";
		}

		return $class;
	}
        
        public function getAllTyreWidgetCat() {
            return $tyre_widget = array(41, 45, 46, 47, 48, 49, 50);
        }
}