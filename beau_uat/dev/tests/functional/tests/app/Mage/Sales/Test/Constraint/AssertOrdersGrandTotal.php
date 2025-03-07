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
 * @category    Tests
 * @package     Tests_Functional
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

namespace Mage\Sales\Test\Constraint;

use Mage\Sales\Test\Page\Adminhtml\SalesOrderIndex;
use Mage\Sales\Test\Page\Adminhtml\SalesOrderView;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that Orders Grand Total is correct on each order page in backend.
 */
class AssertOrdersGrandTotal extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'high';
    /* end tags */

    /**
     * Assert that Orders Grand Total is correct on each order page in backend.
     *
     * @param SalesOrderIndex $salesOrder
     * @param SalesOrderView $salesOrderView
     * @param array $grandTotal
     * @param array $ordersIds
     * @param AssertOrderGrandTotal $assertOrderGrandTotal
     * @return void
     */
    public function processAssert(
        SalesOrderIndex $salesOrder,
        SalesOrderView $salesOrderView,
        array $grandTotal,
        array $ordersIds,
        AssertOrderGrandTotal $assertOrderGrandTotal
    ) {
        foreach ($ordersIds as $key => $orderId) {
            $assertOrderGrandTotal->processAssert($salesOrder, $salesOrderView, $grandTotal[$key], $orderId);
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Grand Total prices equals to prices from data set for orders.';
    }
}
