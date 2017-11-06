<?php
$installer = $this;

$installer->startSetup();
$setup = $installer->getConnection();

$new_regions = array(
		'ACT' => "Australian Capital Territory",
		'NSW' => "New South Wales",
		'NT' => "Northern Territory",
		'Qld' => "Queensland",
		'SA' => "South Australia",
		'Tas' => "Tasmania",
		'Vic' => "Victoria",
		'WA' => "Western Australia",
);

// specify country code for new regions
$country_code = 'AU';

// specify locale
$locale = 'en_US';

foreach ($new_regions as $region_code => $region_name) {
$setup->insert($installer->getTable('directory/country_region'), array(
		'country_id'     => 'AU',
		'code' => $region_code,
		'default_name'     => $region_name
));
$region_id   = $setup->lastInsertId();

$setup->insert($installer->getTable('directory/country_region_name'), array(
		'locale'      => $locale,
		'region_id'     => $region_id,
		'name' => $region_name
));
}


$installer->endSetup();

