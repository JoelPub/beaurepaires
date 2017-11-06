<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
    ALTER TABLE iwd_storelocator
    ADD COLUMN `admin_users` VARCHAR(255) NOT NULL AFTER `phone`;
	CREATE TABLE iwd_storelocator_users (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`account_id` varchar(255) NOT NULL,
	`store_id` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;		
    ");

	
	
	
	
	
	
$installer->endSetup();

/*
 ALTER TABLE iwd_storelocator
    ADD COLUMN `url` VARCHAR(255) NOT NULL AFTER `entity_id`,  
    ADD COLUMN `email` VARCHAR(255) NOT NULL AFTER `phone`,
    ADD COLUMN `opening_hours` LONGTEXT AFTER `postal_code`;
 
 ALTER TABLE iwd_storelocator
    DROP COLUMN `url`,  
    DROP COLUMN `email`,
    DROP COLUMN `opening_hours`;
 
*/