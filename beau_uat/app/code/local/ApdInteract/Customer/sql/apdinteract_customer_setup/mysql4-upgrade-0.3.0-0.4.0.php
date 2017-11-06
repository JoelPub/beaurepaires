<?php
/**
 * Update Default config for modal box
 */

$installer = $this;

$installer->startSetup();

$installer->setConfigData('pslogin/share/enable', '0');

$installer->endSetup();