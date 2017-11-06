<?php
/**
 * - 01/27/2016 - Added column costar_store_code in IWD_Storelocator
 */
$installer = $this;
$installer->startSetup();

$installer->getConnection()
->addColumn($installer->getTable('storelocator/stores'),'costar_store_code', array(
		'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
		'nullable'  => false,
		'length'    => 255,
		'comment'   => 'Costar Store Code'
));

$installer->endSetup();

