<?php
$installer = $this;
$installer->startSetup();
$installer->run("
	ALTER TABLE {$this->getTable('apdinteract_vir/order')}
	ADD `sketch_container` LONGTEXT NOT NULL");


$installer->endSetup();

