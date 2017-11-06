<?php
$installer = $this;
$installer->startSetup();
$installer->run("
	ALTER TABLE {$this->getTable('apdinteract_vir/order')}
	ADD `vehicledetails` LONGTEXT NOT NULL");


$installer->endSetup();