<?php
/**
 * Update Goodyear Au and NZ Website Default Country
 */

/** @var $installer Cochlear_USA_Model_Resource_Mysql4_Setup */
$installer = $this;

$installer->startSetup();

$websiteAu = Mage::getModel('core/website')->load('goodyear_au', 'code');
$websiteNz = Mage::getModel('core/website')->load('goodyear_nz', 'code');

if ($websiteAu->getId()) {
	$installer->setConfigData('general/country/default', 'AU', 'websites', $websiteAu->getId());
}

if ($websiteNz->getId()) {
	$installer->setConfigData('general/country/default', 'NZ', 'websites', $websiteNz->getId());
}

$installer->endSetup();