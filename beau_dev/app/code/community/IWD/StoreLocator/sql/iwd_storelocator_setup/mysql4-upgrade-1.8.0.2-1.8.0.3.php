<?php
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
    ALTER TABLE `iwd_storelocator` 
ADD COLUMN `channel` VARCHAR(45) NULL DEFAULT NULL AFTER `position`,
ADD COLUMN `mon_fri_open` VARCHAR(45) NULL DEFAULT NULL AFTER `channel`,
ADD COLUMN `mon_fri_close` VARCHAR(45) NULL DEFAULT NULL AFTER `mon_fri_open`,
ADD COLUMN `sat_open` VARCHAR(45) NULL DEFAULT NULL AFTER `mon_fri_close`,
ADD COLUMN `sat_close` VARCHAR(45) NULL DEFAULT NULL AFTER `sat_open`,
ADD COLUMN `man_firstname` VARCHAR(45) NULL DEFAULT NULL AFTER `sat_close`,
ADD COLUMN `man_lastname` VARCHAR(45) NULL DEFAULT NULL AFTER `man_firstname`,
ADD COLUMN `man_background` VARCHAR(255) NULL DEFAULT NULL AFTER `man_lastname`,
ADD COLUMN `comm_tyres` VARCHAR(45) NULL DEFAULT NULL AFTER `man_background`,
ADD COLUMN `cons_tyres` VARCHAR(45) NULL DEFAULT NULL AFTER `comm_tyres`,
ADD COLUMN `tyre_brands` VARCHAR(255) NULL DEFAULT NULL AFTER `cons_tyres`,
ADD COLUMN `brakes_service` VARCHAR(45) NULL DEFAULT NULL AFTER `tyre_brands`,
ADD COLUMN `balancing` VARCHAR(45) NULL DEFAULT NULL AFTER `brakes_service`,
ADD COLUMN `wheel_alignments` VARCHAR(45) NULL DEFAULT NULL AFTER `balancing`,
ADD COLUMN `batteries` VARCHAR(45) NULL DEFAULT NULL AFTER `wheel_alignments`,
ADD COLUMN `nitrogen` VARCHAR(45) NULL DEFAULT NULL AFTER `batteries`,
ADD COLUMN `servicing_suburbs` VARCHAR(255) NULL DEFAULT NULL AFTER `nitrogen`,
ADD COLUMN `wheelchair_access` VARCHAR(45) NULL DEFAULT NULL AFTER `servicing_suburbs`,
ADD COLUMN `dropp_off` VARCHAR(45) NULL DEFAULT NULL AFTER `wheelchair_access`,
ADD COLUMN `mobile_fleet` VARCHAR(45) NULL DEFAULT NULL AFTER `dropp_off`,
ADD COLUMN `waiting_area` VARCHAR(45) NULL DEFAULT NULL AFTER `mobile_fleet`,
ADD COLUMN `guest_wifi` VARCHAR(45) NULL DEFAULT NULL AFTER `waiting_area`,
ADD COLUMN `guest_tablet` VARCHAR(45) NULL DEFAULT NULL AFTER `guest_wifi`,
ADD COLUMN `coffee_tea` VARCHAR(45) NULL DEFAULT NULL AFTER `guest_tablet`,
ADD COLUMN `shopping_nearby` VARCHAR(255) NULL DEFAULT NULL AFTER `coffee_tea`,
ADD COLUMN `cafe_nearby` VARCHAR(255) NULL DEFAULT NULL AFTER `shopping_nearby`,
ADD COLUMN `public_transport` VARCHAR(255) NULL DEFAULT NULL AFTER `cafe_nearby`,
ADD COLUMN `off_street_parking` VARCHAR(45) NULL DEFAULT NULL AFTER `public_transport`;		
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