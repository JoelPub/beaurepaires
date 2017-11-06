<?php
// need to add this one to prevent the whiteliste error when adding custom blocks in CMS page
$installer = $this;
$installer->startSetup();

$installer->getConnection()->insertIgnore(
    $installer->getTable('admin/permission_block'),
    array('block_name' => 'campaign/easter', 'is_allowed' => 1)
);

$installer->endSetup();