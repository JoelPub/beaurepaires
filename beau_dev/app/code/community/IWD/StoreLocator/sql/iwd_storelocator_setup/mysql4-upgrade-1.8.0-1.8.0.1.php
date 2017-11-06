<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
    ALTER TABLE iwd_storelocator
    ADD COLUMN `url` VARCHAR(255) NOT NULL AFTER `entity_id`,  
    ADD COLUMN `email` VARCHAR(255) NOT NULL AFTER `phone`,
    ADD COLUMN `opening_hours` LONGTEXT AFTER `postal_code`;
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