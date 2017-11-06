<?php
class ApdInteract_SearchResult_Helper_Data extends Mage_Core_Helper_Abstract
{

public function getStateLocation($orderincrement) {
	
//sales_flat_order
    $resource = Mage::getSingleton('core/resource');
    $readConnection = $resource->getConnection('core_read');
    $storelocation_query = $readConnection->fetchAll("SELECT storelocation FROM sales_flat_order WHERE increment_id='".$orderincrement."' ");
	foreach($storelocation_query as $str)
	{
		$storelocation = $str['storelocation'];
	}
	if($storelocation=='')
	{
		$state = 'n/a';
		$city = 'n/a';
	}
	else
	{
		$results = $readConnection->fetchAll("SELECT * FROM iwd_storelocator WHERE entity_id='".$storelocation."'");
		foreach($results as $var)
		{
			$state = $var['region'];
			$city = $var['city'];
			break;
		}
		if($state=='')
		{
			$state = 'n/a';
		}
		if($city=='')
		{
			$city = 'n/a';
		}		
	}
//	
		return $state;
}



public function getCityLocation($orderincrement) {
	
//sales_flat_order
    $resource = Mage::getSingleton('core/resource');
    $readConnection = $resource->getConnection('core_read');
    $storelocation_query = $readConnection->fetchAll("SELECT storelocation FROM sales_flat_order WHERE increment_id='".$orderincrement."' ");
	foreach($storelocation_query as $str)
	{
		$storelocation = $str['storelocation'];
	}
	if($storelocation=='')
	{
		$state = 'n/a';
		$city = 'n/a';
	}
	else
	{
		$results = $readConnection->fetchAll("SELECT * FROM iwd_storelocator WHERE entity_id='".$storelocation."'");
		foreach($results as $var)
		{
			$state = $var['region'];
			$city = $var['city'];
			break;
		}
		if($state=='')
		{
			$state = 'n/a';
		}
		if($city=='')
		{
			$city = 'n/a';
		}		
	}
//	
		return $city;
}



public function extractWheelSizes($seriesId) {       
        $wheels = Mage::helper('searchtyre')->getVehicleWheels($seriesId);
        $total = 0;
        $sizes_fr = array();
        $sizes_re = array();                
        if (isset($wheels->Items[0])):
            $array = $wheels->Items[0];
            $total = $wheels->Total;
        endif;                
        
        if($total>0):
            for($i=0;$i<=$total;$i++):
                if(isset($array->WheelFitments[$i]->FrontWheel->Size->Description))
                $frontSize = trim($array->WheelFitments[$i]->FrontWheel->Size->Description);
                
                if(isset($array->WheelFitments[$i]->RearWheel->Size->Description))
                $rearSize = trim($array->WheelFitments[$i]->RearWheel->Size->Description);
                if($frontSize !="")
                    $sizes_fr[] = $frontSize;
                if($rearSize !="")
                    $sizes_re[] =  $rearSize;                                        
            endfor;
        endif;                
        
        $re = array_unique($sizes_re);
        $fe = array_unique($sizes_fr);
        
        
        return array("fe"=>$fe,"re"=>$re);
}

public function sanitizeWheelSizes($seriesId) {
    $wheels = Mage::helper('searchtyre')->getVehicleWheels($seriesId);
        $total = 0;
        $sizes = array();
                        
        if (isset($wheels->Items[0])):
            $array = $wheels->Items[0];
            $total = $wheels->Total;
        endif;                
        
        if($total>0):
            for($i=0;$i<=$total;$i++):
                if(isset($array->WheelFitments[$i]->FrontWheel->Size->Description))
                $frontSize = trim($array->WheelFitments[$i]->FrontWheel->Size->Description);
                
                if(isset($array->WheelFitments[$i]->RearWheel->Size->Description))
                $rearSize = trim($array->WheelFitments[$i]->RearWheel->Size->Description);
                if($frontSize !="")
                    $sizes[] = $this->sanitizeValue($frontSize);
                if($rearSize !="")
                    $sizes[] =  $this->sanitizeValue($rearSize);                                        
            endfor;
        endif;                
        
        return $sizes;
}


 /*
    * This will match format of size at the pdp
    * @param string $value
    * @return string  $newValue
    */
    public function sanitizeValue($value) {
        $withoutQuote = preg_replace("/[^A-Za-z0-9\-\.\']/", '', $value);
        $ar = explode("x",$withoutQuote);    
        $newValue = (float)$ar[0] ."x".(float)$ar[1] ;
        return $newValue;
    }






}