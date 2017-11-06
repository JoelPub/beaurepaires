<?php

/**
 * 9/23/2016 - Set Grid View as default and only List mode
 */
$installer = $this;
$installer->startSetup();
$setup = new Mage_Core_Model_Config();
$setup->saveConfig('catalog/frontend/list_mode', 'grid', 'default', 0);

$installer->endSetup();


