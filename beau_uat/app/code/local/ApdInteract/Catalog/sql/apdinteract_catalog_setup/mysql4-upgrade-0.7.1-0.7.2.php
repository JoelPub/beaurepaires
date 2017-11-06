<?php

$installer = $this;
$installer->startSetup();

$attributes = array(
	'feature_1',
	'feature_2',
	'feature_3',
	'feature_4',
	'feature_5',
	'feature_6'
);

foreach($attributes as $attribute) {
	$installer->updateAttribute('catalog_product', $attribute, array(
		'frontend_class' => 'validate-length maximum-length-100',
		'note' => 'Maximum character limit is 100'
	));
}

$installer->endSetup();
