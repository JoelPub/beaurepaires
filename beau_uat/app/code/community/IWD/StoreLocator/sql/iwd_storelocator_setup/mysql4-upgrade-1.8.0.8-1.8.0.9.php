<?php

$installer = $this;

$installer->startSetup();
$setup = $installer->getConnection();

$regions = array(
		'melbourne' => "Melbourne ",
		'geelong' => "Geelong",
		'adelaide' => "Adelaide",
		'darwin' => "Darwin",
		'gold-coast' => "Gold Coast",
		'hobart' => "Hobart",
		'newcastle' => "New Castle",
		'northern-territory' => "Northern Territory",
		'nsw' => "New South Wales",
		'perth' => "Perth",
		'western-australia' => "Western Australia",
		'queensland' => "Queensland",
		'sydney' => "Sydney",
		'tasmania' => "Tasmania",
		'victoria' => "Victoria",
		'canberra' => "Canberra",
		'act' => "Australian Capital Territory",
);

foreach ($regions as $region_url_key => $region_name) {
	$setup->insert($installer->getTable('storelocator/region'), array(
			'name' 		=> $region_name,
			'url_key'   => $region_url_key
	));
}


$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'nps_ratings_score',array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'length'   => 50,
        'comment' =>'NPS Ratings Score'
    ));
$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'windscreen_wipers',array(
        'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
        'nullable' => false,
        'comment' =>'Windscreen Wipers'
    ));
$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'flat_tyres_repair',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'nullable' => false,
    'comment' =>'Flat Tyre Repair'
));
$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'road_hazard_warranty',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'nullable' => false,
    'comment' =>'Road Hazard Warranty'
));
$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'payment_eftpos',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'nullable' => false,
    'comment' =>'Payment EFTPOS'
));
$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'payment_card',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'nullable' => false,
    'comment' =>'Payment Visa, Mastercard'
));
$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'payment_interest_free_terms',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'nullable' => false,
    'comment' =>'Payment Interest Free Terms'
));
$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'email_copy_invoices',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_BOOLEAN,
    'nullable' => false,
    'comment' =>'Email Copy Invoices'
));


$installer->endSetup();
