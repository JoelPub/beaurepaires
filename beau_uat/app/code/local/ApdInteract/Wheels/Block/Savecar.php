<?php   
class ApdInteract_Wheels_Block_Savecar extends Mage_Core_Block_Template {
   
    public function savecar() {
        // wheelinfo/index/savecar/?id=50658&y=2008
        $series_id = $this->getSeriesIdFromRequest();
        $year = $this->getYearFromRequest();
        
        if (!$series_id) {
            return $this->_getErrorJson('id must be > 0 and an integer');
        }
        if ($year < 1990) {
            return $this->_getErrorJson('y must be > 1989 and an integer');
        }        
        
        $json_tyre = Mage::helper('searchtyre')->getCacheTyreData( $series_id );
        $json_tyre->ModelYear = $year;
        $json_tyre->Id = $series_id;
        $this->_updateWheelsVehiclePostValues($json_tyre); 
        
        return $this->_getSuccessJson();
    }
    
    private function _getSuccessJson() {
        return array("Success" => 1);
    }
    
    private function _getErrorJson($message = 'Vehicle was not saved to session') {
        return array(
        "Success" => 0,
        "Error" => $message
        );
    }
    
    public function getSeriesIdFromRequest() {
        return intval($this->_getParam('id'));
    }
    
    public function getYearFromRequest() {
        return intval($this->_getParam('y'));
    }
      
    private function _getParam($key) {
        return Mage::app()->getRequest()->getPost($key);
    }
    
    private function _updateWheelsVehiclePostValues($json_tyre) {
        
        // This is nearly exactly the same as community/Flagbit/FilterUrls/Controller/Router.php
        // TODO: Refactor.
        // 
        // Set wheel sizes to override wheel size session values if set 
        
        $session = Mage::getSingleton('core/session');
        
        $session->setWidthF($json_tyre->OEFitmentData->FrontTyres->SectionWidth);
        $session->setProfileF($json_tyre->OEFitmentData->FrontTyres->AspectRatio);
        $session->setDiameterF($json_tyre->OEFitmentData->FrontTyres->RimDiameter);                       
        $session->setSeriesF($json_tyre->Id);
        $session->setTMakeF($json_tyre->Make->Id);
        $session->setTModelF($json_tyre->Model->Id);
        $session->setTYearF($json_tyre->ModelYear); 
        $session->setTSeriesNameF($json_tyre->Name);
    }
}