<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('customer_vehicle')} 
 CHANGE `store_id` `website_id` int(3) unsigned NOT NULL default '0'
");


$this->endSetup();