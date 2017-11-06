<?php
$installer = $this;
$installer->startSetup();
try {
$installer->run("
    ALTER TABLE `iwd_storelocator` MODIFY comm_tyres TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY cons_tyres TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY brakes_service TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY balancing TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY wheel_alignments TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY batteries TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY nitrogen TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY wheelchair_access TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY drop_off TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY mobile_fleet TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY waiting_area TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY guest_wifi TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY guest_tablet TINYINT(1);
    ALTER TABLE `iwd_storelocator` MODIFY coffee_tea TINYINT(1);
    ALTER TABLE `iwd_storelocator` CHANGE dropp_off drop_off TINYINT(1); 
    ");
} catch (exception $e) {
    Mage::logException($e);
    // but don't break the website.
}

$installer->endSetup();
