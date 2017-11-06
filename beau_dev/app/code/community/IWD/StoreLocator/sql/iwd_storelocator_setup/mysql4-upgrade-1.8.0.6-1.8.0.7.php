<?php
	
	$resource = Mage::getSingleton('core/resource');
	
	/**
	 * Retrieve the read connection
	 */
	$readConnection = $resource->getConnection('core_read');
	$writeConnection = $resource->getConnection('core_write');
	
	$query = "SELECT * FROM directory_country_region WHERE country_id='AU' ORDER BY region_id ASC";
	
	$results = $readConnection->fetchAll($query);
	$not_to_delete = array(); 
	foreach($results as $result) {
		$region = $result['default_name'];
		$id = $result['region_id'];
		$code = strtoupper($result['code']);
		
		
		if(!in_array($region,$not_to_delete)) {
			$not_to_delete[] = $region;					
			$query_duplicate_directory_country_region = "DELETE FROM directory_country_region WHERE default_name='$region' AND region_id<>'$id'";
			
			$writeConnection->query($query_duplicate_directory_country_region);
			$query_duplicate_directory_country_region_name = "DELETE FROM directory_country_region_name WHERE name='$region' AND region_id<>'$id'";
			
			$writeConnection->query($query_duplicate_directory_country_region_name);
			
			$update_stores = "UPDATE iwd_storelocator SET region_id='$id' WHERE region='$code'";
			$writeConnection->query($update_stores);
			
		}
		
		
		
	}
?>