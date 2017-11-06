<?php

/*
 * Added new column 'Booking date and Booking time' - See BFT-2184
 * SD 12/02/2016
 */
$installer = $this;
$installer->startSetup();	

    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'booking_date', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_DATE,
        'nullable'  => false,
        'comment'   => 'Booking Date'
        ));
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'booking_time', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => false,
        'comment'   => 'Booking Time'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'booking_date', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_DATE,
        'nullable'  => false,
        'comment'   => 'Booking Date'
    ));
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'booking_time', array(
    	'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    	'nullable'  => false,
    	'comment'   => 'Booking Time'
    ));

$installer->endSetup();

