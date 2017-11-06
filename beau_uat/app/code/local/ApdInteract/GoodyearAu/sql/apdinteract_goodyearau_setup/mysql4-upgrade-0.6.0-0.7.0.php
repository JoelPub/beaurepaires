<?php
/**
 * GAN-71 : BUG - Missing the "Remember Me" checkbox on the Login form
 */
$installer = $this;

$installer->startSetup();

$installer->setConfigData('persistent/options/enabled', '1');
$installer->setConfigData('persistent/options/remember_default', '1');
