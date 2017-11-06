<?php

/*
 * Added new column 'Status' - BFT-1240
 * SD 01/04/2016
 */
$installer = $this;
$installer->startSetup();	

    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'status', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => false,
    	'length'    => 255,
        'comment'   => 'Status',
    	'default'   => 'Not Started'
        )); 
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'status', array(
    	'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    	'nullable'  => false,
    	'length'    => 255,
    	'comment'   => 'Status',
    	'default'   => 'Not Started'
    ));
    
$installer->endSetup();

