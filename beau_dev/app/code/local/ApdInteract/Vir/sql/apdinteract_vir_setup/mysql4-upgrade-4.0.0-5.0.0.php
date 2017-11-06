<?php

/*
 * Added new column 'Appointment ID' BFT-1371 - As a Store Manager, I need to assign a status to a VIR so the status will show in a Digital Booking Calendar
 * SD 08/22/2016
 */
$installer = $this;
$installer->startSetup();	

    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'appointmentid', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => false,
    	'length'    => 255,
        'comment'   => 'Appointment ID'
        ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'appointmentid', array(
    	'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    	'nullable'  => false,
    	'length'    => 255,
    	'comment'   => 'Appointment ID'    	
    ));
    
    
$installer->endSetup();

