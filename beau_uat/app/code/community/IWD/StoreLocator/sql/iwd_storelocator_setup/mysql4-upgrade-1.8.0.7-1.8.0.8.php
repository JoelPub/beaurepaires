<?php

$installer = $this;
$installer->startSetup();	

    $installer->getConnection()
    ->addColumn($installer->getTable('storelocator/stores'),'parent_region_id', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
        'unsigned'  => true,
        'nullable'  => false,
        'comment'   => 'Parent Region Id'
        )); 
    
    $table = $installer->getConnection()
    ->newTable($installer->getTable('storelocator/region'))
    ->addColumn('region_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
    		'identity'  => true,
    		'unsigned'  => true,
    		'nullable'  => false,
    		'primary'   => true,
    ), 'Region Id')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
    		'nullable'  => false,
    ), 'Name')
    ->addColumn('url_key', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    		'nullable'  => false,
    ), 'Url Key');
    
    $installer->getConnection()->createTable($table);
    


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
