<?php
$installer = $this;
$installer->startSetup();

$installer->run("
-- DROP TABLE IF EXISTS apdinteract_store_category;
CREATE TABLE apdinteract_store_category (
`id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `code` varchar(100) NOT NULL default '',
  `image` varchar(100) NOT NULL default '',
  `icon` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");


$this->endSetup();