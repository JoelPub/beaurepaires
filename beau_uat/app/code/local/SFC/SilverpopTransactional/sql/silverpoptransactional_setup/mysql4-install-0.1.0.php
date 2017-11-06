<?php
/**
 * StoreFront Silverpop Transaction Email Magento Extension
 * NOTICE OF LICENSE
 *
 * This source file is subject to commercial source code license 
 * of StoreFront Consulting, Inc.
 *
 * @category	SFC
 * @package    	SFC_SilverpopTransactional
 * @website 	http://www.storefrontconsulting.com/
 * @copyright 	Copyright (C) 2009-2013 StoreFront Consulting, Inc. All Rights Reserved.
 */


$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS `{$this->getTable('silverpoptransactional/email')}`;
CREATE TABLE `{$this->getTable('silverpoptransactional/email')}` (
  `email_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` smallint(5) unsigned NOT NULL,
  `sender_name` varchar(255) NOT NULL DEFAULT '',
  `sender_email` varchar(255) NOT NULL DEFAULT '',
  `recipient_name` text,
  `recipient_email` text,
  `subject` varchar(255) NOT NULL,
  `content` text,
  `created_at` datetime DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `num_retries` int(11),
  `status` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$this->getTable('silverpoptransactional/logs')}`;
CREATE TABLE `{$this->getTable('silverpoptransactional/logs')}` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `email_id` int unsigned NOT NULL,
  `log_status` int(11) NOT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`log_id`),
  INDEX `indx_export_id` (`email_id`),
  INDEX `indx_log_status` (`log_status`),  
  CONSTRAINT `email_fk` FOREIGN KEY (`email_id`) REFERENCES `silverpoptransactional_email` (`email_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 