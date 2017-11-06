<?php

$installer = $this;
$installer->startSetup();
$installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vehicle/vehicle'),'url',array(
        'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => false,
        'length'   => 255,
        'comment'  => 'Url'
    ));

$installer->endSetup();

?>
