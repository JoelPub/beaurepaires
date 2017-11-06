<?php
try{
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run("
		ALTER TABLE {$this->getTable('iwd_storelocator')} ADD COLUMN `page_title` TEXT NULL DEFAULT NULL AFTER `image`;;
		");

$installer->endSetup();
}catch (Exception $e){
    
}