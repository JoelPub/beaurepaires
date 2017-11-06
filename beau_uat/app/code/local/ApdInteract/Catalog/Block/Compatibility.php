<?php
class ApdInteract_Catalog_Block_Compatibility extends Mage_Core_Block_Template {
        
    /*
    * This method determines if the compatibility box will appear
    * @param 
    * @return boolean
    */
    public function IfApplicable() {  
        $session = Mage::getSingleton('core/session');
        $this->getAllCompatibleSizes();        
        return ($this->_checkIfTyreOrWheels()>0 && $session->getTSeriesNameF() !='') ? true : false;
    }
        
    /*
    * This method determines if the product is under tyre category
    * @param 
    * @return boolean $flag
    */    
    private function _checkIfTyreOrWheels() {
        $product = Mage::registry('current_product');
        $flag = Mage::helper('apdinteract_catalog')->checkCategoryOfProduct($product);
        return $flag;
    }
    
   
    /*
    * This all applicable sizes (wheels & tyres) of selected series
    * @param 
    * @return array $sizes
    */ 
    public function getAllCompatibleSizes() {
        $sizes = array();
        $session = Mage::getSingleton('core/session');
        $seriesId = $session->getSeriesF();
        if($seriesId!=""):
            $wheelSizes = $this->_extractWheelSizes($seriesId);
            $tyreSizes = $this->_extractTyreSizes($seriesId);
            $sizes = array_merge($wheelSizes,$tyreSizes);
            $sizes = array_unique($sizes);
        endif;
        
        return $this->_convertToString($sizes);                
    }
    
    /*
    * This returns comma delimited sizes 
    * @param  array $array
    * @return string $sizes
    */
    private function _convertToString($array) {
        $sizes = "";
        $count = count($array);       
        foreach($array as $item):
            $sizes .= $item.",";
        endforeach;
        
        return $sizes;
    }
    
    /*
    * This returns the all wheel sizes
    * @param  int $seriesId
    * @return array $sizes
    */ 
    private function _extractWheelSizes($seriesId) {       
        return Mage::Helper('apdinteract_searchresult')->sanitizeWheelSizes($seriesId);
    }
    
    /*
    * This returns the all tyre sizes
    * @param  int $seriesId
    * @return array $sizes
    */ 
    private function _extractTyreSizes($seriesId) {
        $tyres = Mage::helper('searchtyre')->getCacheTyreData($seriesId);
        $sizes = array();
        if(isset($tyres->OEFitmentData->FrontTyres->Description)):
            $sizes[] = trim($tyres->OEFitmentData->FrontTyres->Description);
        endif;
        if(isset($tyres->OEFitmentData->FrontTyres->Description)):
            $sizes[] = trim($tyres->OEFitmentData->RearTyres->Description);
        endif;
        
        return $sizes;
    }
    
    /*
    * This returns the label of size
    * @param
    * @return string 
    */ 
    public function getLabel() {
        $categoryId = $this->_checkIfTyreOrWheels();
        return ($categoryId==41) ? 'Your Tyre Size': 'Rim Diameter';        
    }
    
    /*
    * This returns url
    * @param
    * @return string 
    */
    public function getUrl() {
        $categoryId = $this->_checkIfTyreOrWheels();
        return ($categoryId==41) ? '/tyres': '/wheels';
    }
    
    /*
    * This will match format of size at the pdp
    * @param string $value
    * @return string  $newValue
    */
    private function _sanitizeValue($value) {
        $withoutQuote = preg_replace("/[^A-Za-z0-9\-\.\']/", '', $value);
        $ar = explode("x",$withoutQuote);    
        $newValue = (float)$ar[0] ."x".(float)$ar[1] ;
        return $newValue;
    }
}
