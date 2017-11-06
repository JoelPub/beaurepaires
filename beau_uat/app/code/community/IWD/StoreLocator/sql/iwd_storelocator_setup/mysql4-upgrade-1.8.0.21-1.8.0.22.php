<?php
/**
 * BCC-384 - BFT - Store Locator Opening hours Provision
 * Mar 16, 2017
 * Analyn Javier
 */

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'mon_open',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'length'   => 32,
    'comment' =>'Monday Opening Schedule'
));

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'tue_open',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'length'   => 32,
    'comment' =>'Tuesday Opening Schedule'
));

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'wed_open',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'length'   => 32,
    'comment' =>'Wednesday Opening Schedule'
));

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'thu_open',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'length'   => 32,
    'comment' =>'Thursday Opening Schedule'
));

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'fri_open',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'length'   => 32,
    'comment' =>'Friday Opening Schedule'
));

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'mon_close',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'length'   => 32,
    'comment' =>'Monday Opening Schedule'
));

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'tue_close',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'length'   => 32,
    'comment' =>'Tuesday Closing Schedule'
));

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'wed_close',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'length'   => 32,
    'comment' =>'Wednesday Closing Schedule'
));

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'thu_close',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'length'   => 32,
    'comment' =>'Thursday Closing Schedule'
));

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'fri_close',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'length'   => 32,
    'comment' =>'Friday Closing Schedule'
));


$installer->endSetup();