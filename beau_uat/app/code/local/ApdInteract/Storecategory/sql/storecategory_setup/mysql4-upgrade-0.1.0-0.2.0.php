<?php
$installer = $this;
$installer->startSetup();

$installer->run("
    
SET @preparedStatement = (SELECT IF(
    (SELECT COUNT(*)
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE  table_name = 'iwd_storelocator'
        AND table_schema = DATABASE()
        AND column_name = 'type_id'
    ) > 0,
    'SELECT 1',
    'ALTER TABLE `iwd_storelocator` ADD `type_id` INT NOT NULL AFTER `public_holiday_close`;'
));

PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;



");


$this->endSetup();