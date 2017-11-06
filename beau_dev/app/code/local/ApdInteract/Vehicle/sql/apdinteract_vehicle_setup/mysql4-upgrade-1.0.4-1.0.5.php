<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('customer_vehicle')} DROP INDEX registration;
");


$this->endSetup();