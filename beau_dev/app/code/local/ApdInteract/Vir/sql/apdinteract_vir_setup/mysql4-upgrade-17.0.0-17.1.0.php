<?php

$installer = $this;
$installer->startSetup();
$sql="
CREATE TABLE IF NOT EXISTS `apdinteract_vir_healthcheck` (
  `entity_id` INT(11) PRIMARY KEY AUTO_INCREMENT,
  `vir_id` INT(11) NOT NULL,
  `date` DATE NULL DEFAULT NULL,
  `time` TIME NULL DEFAULT NULL,
  `store_name` VARCHAR(80) NOT NULL,
  `store_manager` VARCHAR(80) NOT NULL,
  `customer_name` VARCHAR(80) NOT NULL,
  `vehicle_registration` VARCHAR(80) NOT NULL,
  `vehicle_make` VARCHAR(80) NOT NULL,
  `vehicle_model` VARCHAR(80) NOT NULL,
  `odometer` VARCHAR(80) NOT NULL,
  `tread_depth` DECIMAL(6, 2) NOT NULL,
  `tyre_pressure` DECIMAL(6, 2) NOT NULL,
  `treadwear` VARCHAR(80) NOT NULL,
  `has_sixyears` BOOL NOT NULL DEFAULT FALSE,
  `battery` BOOL NOT NULL DEFAULT FALSE,
  `wiper_blades` BOOL NOT NULL DEFAULT FALSE,
  `steering` BOOL NOT NULL DEFAULT FALSE,
  `break_lights` BOOL NOT NULL DEFAULT FALSE,
  `indicator_lights` BOOL NOT NULL DEFAULT FALSE,
  `head_lights` BOOL NOT NULL DEFAULT FALSE,
  `interior_lights` BOOL NOT NULL DEFAULT FALSE,
  `boot_lights` BOOL NOT NULL DEFAULT FALSE,
  `tail_lights` BOOL NOT NULL DEFAULT FALSE,
  `hazard_lights` BOOL NOT NULL DEFAULT FALSE,
  `front_callipers` BOOL NOT NULL DEFAULT FALSE,
  `rear_callipers` BOOL NOT NULL DEFAULT FALSE,
  `front_brakes` BOOL NOT NULL DEFAULT FALSE,
  `rear_brakes` BOOL NOT NULL DEFAULT FALSE,
  `front_discs` BOOL NOT NULL DEFAULT FALSE,
  `rear_discs` BOOL NOT NULL DEFAULT FALSE,
  `flexible_hydraulic_brakes_hoses` BOOL NOT NULL DEFAULT FALSE,
  `comments` VARCHAR(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

$installer->run($sql);
$installer->endSetup();
	 