<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'vehicleseries', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment'   => 'Vehicle Series'
    ));

$installer->endSetup();


