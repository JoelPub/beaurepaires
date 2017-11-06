<?php
/**
 * Request made by BCC-367 - Added new field static_block_identifier
 * Feb 8, 2017
 * SD
 */

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('iwd_storelocator'),'static_block_identifier',array(
    'type'     => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable' => true,
    'length'   => 200,
    'comment' =>'Static Block Identifier'
));

$installer->endSetup();