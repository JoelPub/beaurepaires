<?php
$installer = $this;
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS `apd_geo_postcode`;    
CREATE TABLE `apd_geo_postcode` (
`entity_id` int(10) unsigned NOT NULL auto_increment,
`country` VARCHAR( 50 ) NOT NULL,
`region_1` VARCHAR( 255 ) NOT NULL,
`region_2` VARCHAR( 255 ) NOT NULL,
`region_3` VARCHAR( 255 ) NOT NULL,
`region_4` VARCHAR( 255 ) NOT NULL,
`locality` VARCHAR( 255 ) NOT NULL,
`post_code` int(8) NOT NULL,
`suburb` VARCHAR( 255 ) NOT NULL,
`street` VARCHAR( 255 ) NOT NULL,
`range` VARCHAR( 255 ) NOT NULL,
`building` VARCHAR( 50 ) NOT NULL,
`latitude` VARCHAR( 50 ) NOT NULL,
`longtitude` VARCHAR( 50 ) NOT NULL,
`stat` VARCHAR( 255 ) NOT NULL,
`timezone` VARCHAR( 255 ) NOT NULL,
`utc` VARCHAR( 50 ) NOT NULL,
`dst` VARCHAR( 50 ) NOT NULL,

PRIMARY KEY  (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$this->endSetup();