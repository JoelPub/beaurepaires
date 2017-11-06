<?php
class ApdInteract_Onsale_Helper_Data extends Mage_Core_Helper_Abstract
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










}