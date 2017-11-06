<?php

/**
 * - Added request type field that will contain value either BOOKING or PRICE REQUEST 
 */
$installer = $this;
$installer->startSetup();
$installer->getConnection()
->addColumn($installer->getTable('sales/order'),'request_type', array(
		'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
		'nullable'  => false,
		'length'    => 255,
		'comment'   => 'Request Type'
));

$installer->endSetup();


