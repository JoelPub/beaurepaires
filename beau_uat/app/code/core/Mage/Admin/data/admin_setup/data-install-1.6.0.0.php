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
 * @package     Mage_Admin
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Save administrators group role and rules
 */
$admGroupRole = Mage::getModel('admin/role')->setData(array(
    'parent_id'     => 0,
    'tree_level'    => 1,
    'sort_order'    => 1,
    'role_type'     => 'G',
    'user_id'       => 0,
    'role_name'     => 'Administrators'
    ))
    ->save();

Mage::getModel('admin/rules')->setData(array(
    'role_id'       => $admGroupRole->getId(),
    'resource_id'   => 'all',
    'privileges'    => null,
    'assert_id'     => 0,
    'role_type'     => 'G',
    'permission'    => 'allow'
    ))
    ->save();
