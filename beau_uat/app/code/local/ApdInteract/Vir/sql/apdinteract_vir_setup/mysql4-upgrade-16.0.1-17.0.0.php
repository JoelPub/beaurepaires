<?php

/*
 * BCC-442: 100HRS BCC - Corrections to the Commercial VIR pages
 * 
 */
$installer = $this;
$installer->startSetup();	

    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare1', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare2', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare3', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare4', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare5', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare6', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare7', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare8', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare9', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare10', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare11', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    
    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/ordercommercial'),'spare12', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable'  => true,
        'comment'   => 'Vehicle Make'
    ));
    

$installer->endSetup();

