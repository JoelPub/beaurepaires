<?php

/*
 * Added new column 'Ordernumber' BFT-1777 - to change status of consumer VIRs when orders are refunded/cancelled
 * SD 01/20/2016
 */
$installer = $this;
$installer->startSetup();	

    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'ordernumber', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => false,
    	'length'    => 255,
        'comment'   => 'Order number'
        )); 
    
    
$installer->endSetup();

