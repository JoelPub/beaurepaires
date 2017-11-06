<?php
class ApdInteract_Updateprice_Helper_Data extends Mage_Core_Helper_Abstract
{
    
    public function checkSpecialPrice($specialPrice,$specialPriceFromDate,$specialPriceToDate = null){
        
        $today =  strtotime(Mage::getModel('core/date')->date('Y-m-d'));
        $price = 0;
         if($today >= strtotime( $specialPriceFromDate) && 
            $today <= strtotime($specialPriceToDate) || 
            $today >= strtotime( $specialPriceFromDate) &&
            is_null($specialPriceToDate)){
             
            $price = number_format($specialPrice,2);
         }
         
         return $price;
    }
}
	  