<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('customer_vehicle')} CHANGE `details` `details` LONGTEXT;
");


$this->endSetup();