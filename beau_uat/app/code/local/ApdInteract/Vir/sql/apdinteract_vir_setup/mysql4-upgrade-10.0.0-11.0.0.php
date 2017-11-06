<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn($installer->getTable('apdinteract_vir/order'),'custaddress2', array(
        'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
        'comment'   => 'Street'
    ));

$installer->run("
	ALTER TABLE {$this->getTable('apdinteract_vir/order')}
	CHANGE `custaddress` `custaddress` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Building/Street Number'");

$installer->run("
	ALTER TABLE {$this->getTable('apdinteract_vir/ordercommercial')}
	CHANGE `addressline1` `addressline1` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Building/Street Number'");

$installer->run("
	ALTER TABLE {$this->getTable('apdinteract_vir/ordercommercial')}
	CHANGE `addressline2` `addressline2` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Street'");


$installer->endSetup();
