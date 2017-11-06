<?php

$installer = $this;
$installer->startSetup();
$installer->run("
	ALTER TABLE {$this->getTable('customer_vehicle')}
	ADD `details` LONGTEXT NOT NULL AFTER `registration`;
	");


$installer->endSetup();

