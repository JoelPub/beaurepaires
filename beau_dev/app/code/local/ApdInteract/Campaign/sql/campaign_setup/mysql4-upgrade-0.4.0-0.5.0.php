<?php

$installer = $this;

$installer->startSetup();

$sql = "ALTER TABLE `apdinteract_campaign` ADD `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `store_id`;";
        
$installer->run($sql);

$installer->endSetup();
