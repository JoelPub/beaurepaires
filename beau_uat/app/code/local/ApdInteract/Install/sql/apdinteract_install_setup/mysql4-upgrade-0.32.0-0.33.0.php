<?php
$installer = $this;
$installer->startSetup();

$setup = new Mage_Core_Model_Config();
$setup->saveConfig('web/cookie/cookie_lifetime', 604800, 'default', 0);

$installer->endSetup();