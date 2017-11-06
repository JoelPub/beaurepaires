<?php

$installer = $this;
$installer->startSetup();

$installer->run("
	DROP TABLE IF EXISTS `{$installer->getTable('apdinteract_salesforce/dictionary')}`; 	
	CREATE TABLE `{$installer->getTable('apdinteract_salesforce/dictionary')}` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`entity_type` VARCHAR( 255 ) NOT NULL,
		`entity_id` int(11) NOT NULL,
		`salesforce_id` VARCHAR (255) NOT NULL,
		`created_at` datetime NULL DEFAULT NULL,
        `updated_at` datetime NULL DEFAULT NULL,
        PRIMARY KEY (`id`)
	)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
");

$this->endSetup();