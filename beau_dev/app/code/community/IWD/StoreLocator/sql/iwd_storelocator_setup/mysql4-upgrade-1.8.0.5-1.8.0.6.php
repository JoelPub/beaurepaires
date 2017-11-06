<?php
$installer = $this;
$installer->startSetup();
try {
$installer->run("
    ALTER TABLE `iwd_storelocator` ADD COLUMN `meta_description` LONGTEXT AFTER `title`;
    ");
} catch (exception $e) {
    Mage::logException($e);
    // but don't break the website.
}

$installer->endSetup();
