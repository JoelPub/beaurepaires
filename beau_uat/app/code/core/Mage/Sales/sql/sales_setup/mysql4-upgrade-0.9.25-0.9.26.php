<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

$installer = $this;
/* @var $installer Mage_Sales_Model_Mysql4_Setup */

$installer->run("
DELETE FROM `{$this->getTable('sales_order_tax')}`
WHERE `order_id` NOT IN (
    SELECT `entity_id` FROM `{$this->getTable('sales_order')}`
)
");

$installer->getConnection()->addConstraint(
    'FK_SALES_ORDER_TAX_ORDER',
    $this->getTable('sales_order_tax'), 'order_id',
    $this->getTable('sales_order'), 'entity_id'
);
