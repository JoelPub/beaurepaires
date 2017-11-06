<?php
$installer = $this;
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('customer_vehicle')};
CREATE TABLE {$this->getTable('customer_vehicle')} (
`vehicle_id` int(10) unsigned NOT NULL auto_increment,
  `customer_id` int(10) unsigned NOT NULL default '0',
  `website_id` int(10) unsigned NOT NULL default '0',
  `make` varchar(255) NOT NULL default '',
  `manufacture_year` int(10) unsigned NOT NULL  default '0',
  `model` varchar(255) NOT NULL default '',
  `series` varchar(255) NOT NULL default '',
  `registration` varchar(32) NOT NULL UNIQUE,
  `details` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`vehicle_id`),
  KEY `FK_CUSTOMER_VEHICLE` (`customer_id`),
  CONSTRAINT `FK_CUSTOMER_VEHICLE` FOREIGN KEY (`customer_id`) REFERENCES {$this->getTable('customer_entity')} (`entity_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");


$this->endSetup();