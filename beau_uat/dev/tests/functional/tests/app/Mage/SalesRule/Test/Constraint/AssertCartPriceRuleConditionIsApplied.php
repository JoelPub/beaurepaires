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

namespace Mage\SalesRule\Test\Constraint;

/**
 * Assert that shopping cart subtotal not equals with grand total(excluding shipping price if exist).
 */
class AssertCartPriceRuleConditionIsApplied extends AbstractCartPriceRuleApplying
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that shopping cart subtotal not equals with grand total.
     *
     * @return void
     */
    protected function assert()
    {
        $totals = $this->getTotals();
        \PHPUnit_Framework_Assert::assertNotEquals(
            $totals['subtotal'],
            $totals['grandTotal'],
            "Shopping cart subtotal: " . $totals['subtotal'] . " equals with grand total: " . $totals['grandTotal']
            . ".\nPrice rule hasn't been applied."
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Shopping cart subtotal doesn't equal to grand total - price rule has been applied.";
    }
}
