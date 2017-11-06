<?php
//BFT-2245: As an Administrator, I require the ability to exclude stores (eg. franchise) from the Shopping Cart process if they have not opted into eCom Click+Fit
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
    ALTER TABLE iwd_storelocator    
    ADD `exclude_from_cart` INT(1) NOT NULL AFTER `p_branch_password`;
    ");

$installer->endSetup();