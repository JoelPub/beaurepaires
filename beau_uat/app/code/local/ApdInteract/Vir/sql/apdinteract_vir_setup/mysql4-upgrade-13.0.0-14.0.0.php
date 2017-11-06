<?php


/*
 * Added new column 'Customer ID' BFT-1990 - As a Magento Admin, I require customer and/or vehicle details collected by the Commercial VIR to be updated/added to the Customer and Vehicle tables in Magento
 * Added new column 'Customer ID' BFT-1989 - As a Magento Admin, I require customer and/or vehicle details collected by the Consumer VIR to be updated/added to the Customer and Vehicle tables in Magento
 * SD 09/06/2016
 */
$installer = $this;
$installer->startSetup();	

    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'vehicle_wheel_extra_spare', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment'   => 'Vehicle Wheel Extra Spare'
        ));

    $installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'vehicle_wheel_extra_spare_torque', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment'   => 'Vehicle Wheel Extra Spare Torque'
    ));

$installer->endSetup();

