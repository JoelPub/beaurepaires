<?php

/**
 * - 09/30/2016 - See BFT-1815
 */
$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('sales/order'),'costar_sales_order_number', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable'  => true,
        'length'    => 255,
        'comment'   => 'Costar Sales Order Number'
    ));

$installer->getConnection()
    ->addColumn($installer->getTable('sales/order'),'costar_invoice_id', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'nullable'  => true,
        'length'    => 255,
        'comment'   => 'Costar Invoice Id'
    ));

$installer->getConnection()
    ->addColumn($installer->getTable('sales/order'),'costar_invoice_date', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        'nullable'  => true,
        'length'    => 255,
        'comment'   => 'Costar Invoice Date'
    ));



$installer->endSetup();


