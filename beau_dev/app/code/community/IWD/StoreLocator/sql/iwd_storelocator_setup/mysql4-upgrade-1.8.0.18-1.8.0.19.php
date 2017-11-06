<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'sunday_open',array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'length'   => 50,
        'comment' =>'Sunday Open'
    ));
$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'sunday_close',array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'length'   => 50,
        'comment' =>'Sunday Close'
    ));
$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'public_holiday_open',array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'length'   => 50,
        'comment' =>'Public Holiday Open'
    ));
$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'public_holiday_close',array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'length'   => 50,
        'comment' =>'Public Holiday Close'
    ));

$installer->endSetup();
?>