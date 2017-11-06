<?php
$installer = $this;
$installer->startSetup();
try {
$installer->run("
    ALTER TABLE `iwd_storelocator` MODIFY off_street_parking TINYINT(1);
    ");
} catch (exception $e) {
    Mage::logException($e);
    // but don't break the website.
}

$installer->endSetup();
