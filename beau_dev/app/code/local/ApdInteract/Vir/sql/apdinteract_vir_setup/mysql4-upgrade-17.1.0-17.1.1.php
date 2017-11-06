<?php

$installer = $this;
$installer->startSetup();
$installer->run("
	ALTER TABLE {$this->getTable('apdinteract_vir/healthcheck')}
	DROP COLUMN `tread_depth`,
	DROP COLUMN `tyre_pressure`,
	DROP COLUMN `treadwear`,
	DROP COLUMN `has_sixyears`,
	ADD `lf_tread_depth` DECIMAL(6, 2) NOT NULL,
	ADD `lf_tyre_pressure` DECIMAL(6, 2) NOT NULL,
	ADD `lf_treadwear` VARCHAR(80) NOT NULL,
	ADD `lf_hassixyears` BOOL NOT NULL DEFAULT FALSE,
	ADD `rf_tread_depth` DECIMAL(6, 2) NOT NULL,
	ADD `rf_tyre_pressure` DECIMAL(6, 2) NOT NULL,
	ADD `rf_treadwear` VARCHAR(80) NOT NULL,
	ADD `rf_hassixyears` BOOL NOT NULL DEFAULT FALSE,
	ADD `spare_tread_depth` DECIMAL(6, 2) NOT NULL,
	ADD `spare_tyre_pressure` DECIMAL(6, 2) NOT NULL,
	ADD `spare_treadwear` VARCHAR(80) NOT NULL,
	ADD `spare_hassixyears` BOOL NOT NULL DEFAULT FALSE,
	ADD `lr_tread_depth` DECIMAL(6, 2) NOT NULL,
	ADD `lr_tyre_pressure` DECIMAL(6, 2) NOT NULL,
	ADD `lr_treadwear` VARCHAR(80) NOT NULL,
	ADD `lr_hassixyears` BOOL NOT NULL DEFAULT FALSE,
	ADD `rr_tread_depth` DECIMAL(6, 2) NOT NULL,
	ADD `rr_tyre_pressure` DECIMAL(6, 2) NOT NULL,
	ADD `rr_treadwear` VARCHAR(80) NOT NULL,
	ADD `rr_hassixyears` BOOL NOT NULL DEFAULT FALSE");
$installer->run($sql);
$installer->endSetup();

?>