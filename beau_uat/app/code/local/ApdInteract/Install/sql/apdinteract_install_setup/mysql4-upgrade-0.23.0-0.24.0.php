<?php

/**
 * - 10/12/2015 - Added column Delivery Time and Duration for Booking details
 */
$installer = $this;
$installer->startSetup();

$installer->getConnection()
->addColumn($installer->getTable('sales/order'),'delivery_time', array(
		'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
		'nullable'  => false,
		'length'    => 255,
		'comment'   => 'Delivery Time'
));

$installer->getConnection()
->addColumn($installer->getTable('sales/order'),'duration', array(
		'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
		'nullable'  => false,
		'length'    => 255,
		'comment'   => 'Duration'
));


$installer->endSetup();


