<?php
$installer = $this;
$installer->startSetup();

$setup = new Mage_Core_Model_Config();
$setup->saveConfig('catalog/layered_navigation/price_range_calculation', 'manual', 'default', 0);
$setup->saveConfig('catalog/layered_navigation/price_range_step', 100, 'default', 0);
$setup->saveConfig('catalog/layered_navigation/price_range_max_intervals', 5, 'default', 0);

$installer->endSetup();