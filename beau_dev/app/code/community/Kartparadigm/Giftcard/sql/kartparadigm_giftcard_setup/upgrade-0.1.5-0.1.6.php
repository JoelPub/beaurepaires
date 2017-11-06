<?php

$installer = $this;
$installer->startSetup();

/**
 * Create Giftcardtemplates Type Table 
 *
 *
 */
$tableName = $installer->getTable('kartparadigm_giftcard/giftcard');
$this->getConnection()
        ->addColumn(
                $tableName, 'group_id', "INT"
);

$this->getConnection()->addColumn(
        $tableName, 'group_name', "VARCHAR(200) NOT NULL DEFAULT ''"
)

;


$installer->endSetup();
