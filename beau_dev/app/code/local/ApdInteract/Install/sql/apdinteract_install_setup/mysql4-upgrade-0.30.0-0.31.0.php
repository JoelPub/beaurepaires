<?php

/**
 * 9/14/2016 - See BFT-2031
 */
$installer = $this;
$installer->startSetup();

$installer->getConnection()
->addColumn($installer->getTable('sales/order'),'fleet_number', array(
		'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
		'nullable'  => false,
		'length'    => 255,
		'comment'   => 'Fleet Number'
));

$installer->getConnection()
->addColumn($installer->getTable('sales/order'),'speedometer_hub', array(
		'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
		'nullable'  => false,
		'length'    => 255,
		'comment'   => 'Speedometer Hub'
));


$installer->endSetup();


