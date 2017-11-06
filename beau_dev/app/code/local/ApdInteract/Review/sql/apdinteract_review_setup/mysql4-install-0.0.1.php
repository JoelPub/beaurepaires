<?php
$installer = $this;
$installer->startSetup();

$installer->run("
    ALTER TABLE review
    ADD COLUMN `up_vote`  tinyint NOT NULL AFTER `status_id`,  
    ADD COLUMN `down_vote` tinyint NOT NULL AFTER `status_id`;
    ");

$installer->endSetup();
