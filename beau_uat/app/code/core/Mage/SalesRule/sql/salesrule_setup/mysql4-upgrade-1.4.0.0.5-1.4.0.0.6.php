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
 * @package     Mage_SalesRule
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/* @var $installer Mage_Sales_Model_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

$installer->getConnection()->dropForeignKey(
    $installer->getTable('salesrule/coupon_usage'),
    'FK_SALESRULE_COUPON_CUSTOMER_COUPON_ID_CUSTOMER_ENTITY'
);

$installer->getConnection()->dropForeignKey(
    $installer->getTable('salesrule/coupon_usage'),
    'FK_SALESRULE_COUPON_CUSTOMER_CUSTOMER_ID_CUSTOMER_ENTITY'
);

$installer->getConnection()->addConstraint('FK_SALESRULE_CPN_CUST_CPN_ID_CUST_ENTITY',
    $installer->getTable('salesrule/coupon_usage'), 'coupon_id',
    $installer->getTable('salesrule/coupon'), 'coupon_id'
);


$installer->getConnection()->addConstraint('FK_SALESRULE_CPN_CUST_CUST_ID_CUST_ENTITY',
    $installer->getTable('salesrule/coupon_usage'), 'customer_id',
    $installer->getTable('customer_entity'), 'entity_id'
);

$installer->endSetup();
