<?php
class ApdInteract_Storecategory_Helper_Data extends Mage_Core_Helper_Abstract
{
    private function _getAllCodes() {
         $codes = array();
         $categories = Mage::getModel("storecategory/storecategory")->getCollection();
         foreach($categories as $category) {
             $codes[$category->getCode()] = array($category->getId(),$category->getImage(),$category->getIcon());
         }
         return $codes;
    }
    
    public function getIdFromCode($code) {
        $types = $this->_getAllCodes();        
        return (isset($types[$code]))? $types[$code][0] : 0;
    }
    
    public function getAllImageDetails() {
        $codes = array();
         $categories = Mage::getModel("storecategory/storecategory")->getCollection();
         foreach($categories as $category) {
             $codes[$category->getId()] = array($category->getImage(),$category->getIcon());
         }
         return $codes;
    }
}
	 