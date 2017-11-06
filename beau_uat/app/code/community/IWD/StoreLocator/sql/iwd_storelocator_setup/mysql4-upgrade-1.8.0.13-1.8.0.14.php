<?php
$installer = $this;

$installer->startSetup();
$setup = $installer->getConnection();

$regions = array(
		'sa' => "South Australia",
		
);

foreach ($regions as $region_url_key => $region_name) {
	$setup->insert($installer->getTable('storelocator/region'), array(
			'name' 		=> $region_name,
			'url_key'   => $region_url_key
	));
}

$installer->endSetup();

