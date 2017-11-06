<?php

/*
 * Added new column 'Customer ID' BFT-1990 - As a Magento Admin, I require customer and/or vehicle details collected by the Commercial VIR to be updated/added to the Customer and Vehicle tables in Magento
 * Added new column 'Customer ID' BFT-1989 - As a Magento Admin, I require customer and/or vehicle details collected by the Consumer VIR to be updated/added to the Customer and Vehicle tables in Magento
 * SD 09/06/2016
 */
$installer = $this;
$installer->startSetup();

    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'road_hazard_warranty_description', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment'   => 'Road Hazard Warranty Description'
    ));

    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'road_hazard_warranty_qty', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'comment'   => 'Road Hazard Warranty Quantity'
    ));

    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'road_hazard_warranty_price', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
        'comment'   => 'Road Hazard Warranty Price'
    ));

    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'custcompany', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment'   => 'Company Name'
    ));

$installer->endSetup();


