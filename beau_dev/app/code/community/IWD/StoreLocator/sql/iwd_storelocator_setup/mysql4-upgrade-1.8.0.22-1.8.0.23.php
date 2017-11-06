<?php
//BFT-2356: COSTAR API - As a Magento Administrator, I require the ability to manage Costar Credentials from within the Store Locator (Admin)
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
    ALTER TABLE iwd_storelocator
    ADD `p_costar_live_id` INT NOT NULL AFTER `costar_store_code`,  
    ADD `p_branch_password` VARCHAR(100) NOT NULL AFTER `p_costar_live_id`;
    ");

$installer->endSetup();