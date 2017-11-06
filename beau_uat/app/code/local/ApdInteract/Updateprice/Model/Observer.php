<?php
class ApdInteract_Updateprice_Model_Observer
{

	
   public function finalPrice(Varien_Event_Observer $observer){
       

       $product = $observer->getEvent()->getProduct();
       $special_price = $product->getSpecialPrice();
       $regular_price = $product->getPrice();
       
       $specialPriceFromDate = $product->getSpecialFromDate();
       $specialPriceToDate = $product->getSpecialToDate();
       $today =  strtotime(Mage::getModel('core/date')->date('Y-m-d'));
       
       
       if($special_price >= $regular_price && ($today >= strtotime( $specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime( $specialPriceFromDate) && is_null($specialPriceToDate))){
           $product->setFinalPrice($special_price); 
       }elseif(isset($special_price) && ($today >= strtotime( $specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime( $specialPriceFromDate) && is_null($specialPriceToDate))){
           // do nothing
       }else{
            
           $product->setFinalPrice(0);
       }
     
   }
}
