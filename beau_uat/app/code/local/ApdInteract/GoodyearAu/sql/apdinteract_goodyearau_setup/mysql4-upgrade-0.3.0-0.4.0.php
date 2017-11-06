<?php
/**
 * Update Goodyear Au and NZ Website Logo name
 */

/** @var $installer Cochlear_USA_Model_Resource_Mysql4_Setup */
$installer = $this;

$installer->startSetup();

$websiteAu = Mage::getModel('core/website')->load('goodyear_au', 'code');
$websiteNz = Mage::getModel('core/website')->load('goodyear_nz', 'code');

if ($websiteAu->getId()) {
    $installer->setConfigData('design/header/logo_src', 'images/goodyear_logo.svg', 'websites', $websiteAu->getId());
    $installer->setConfigData('design/header/logo_src_small', 'images/goodyear_logo.svg', 'websites', $websiteAu->getId());
    $installer->setConfigData('design/header/logo_alt', 'Goodyear AU logo', 'websites', $websiteAu->getId());

}

if ($websiteNz->getId()) {
    $installer->setConfigData('design/header/logo_src', 'images/goodyear_logo.svg', 'websites', $websiteNz->getId());
    $installer->setConfigData('design/header/logo_src_small', 'images/goodyear_logo.svg', 'websites', $websiteNz->getId());
    $installer->setConfigData('design/header/logo_alt', 'Goodyear NZ logo', 'websites', $websiteNz->getId());
}

$installer->endSetup();