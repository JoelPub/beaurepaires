<?php
$installer = $this;
$installer->startSetup();

$installer->run("
   ALTER TABLE `review_entity_summary` ADD `total_approved` TINYINT NOT NULL DEFAULT '0' AFTER `store_id`; 
    ");

$installer->endSetup();
