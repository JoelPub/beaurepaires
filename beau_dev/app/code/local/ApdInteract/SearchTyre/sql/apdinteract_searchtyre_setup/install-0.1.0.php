<?php

/**
 * - Create API data cache for wheel / tyre data
 * request,body
 */
$installer = $this;
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS apd_datacache;
CREATE TABLE `apd_datacache` (
 `request` VARCHAR(255) NOT NULL PRIMARY KEY,
 `body` LONGTEXT NOT NULL
 ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->endSetup();