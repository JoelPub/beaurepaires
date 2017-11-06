<?php
$regions = array(
    array(
        'name' => 'Brisbane',
        'url_key' => 'brisbane',
    ),
	array(
		'name' => 'Western Australia',
		'url_key' => 'wa',
	),
);
foreach ($regions as $region) {
    Mage::getModel('storelocator/region')
        ->setData($region)
        ->save();
}


