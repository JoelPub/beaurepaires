<?php   
class ApdInteract_SearchTyre_Block_Index extends Mage_Core_Block_Template {   

    private function _getApiData($request) {
        return Mage::getModel('searchtyre/searchtyre')->connect($request);
    }
    
    public function getMakes() {
        $request = 'vehicles/makes';
        //foreach ($this->_getApiData($request)) 
        
        return '<option value="">Loading...</option>';
    }



}