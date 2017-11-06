<?php
/**
 *Update Product Price Scope from 'Global' to 'Website'
 * We need to update this because we have multiple website on same instance and could be different price per store.
 *
 */

$installer = $this;

$installer->startSetup();

$installer->setConfigData('catalog/price/scope', '1');