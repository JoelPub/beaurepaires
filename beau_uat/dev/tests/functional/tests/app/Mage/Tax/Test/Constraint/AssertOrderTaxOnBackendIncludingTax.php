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

namespace Mage\Tax\Test\Constraint;

/**
 * Checks that prices displayed including tax in order are correct on backend.
 */
class AssertOrderTaxOnBackendIncludingTax extends AssertOrderTaxOnBackendExcludingIncludingTax
{
    /**
     * Verify fields for assert.
     *
     * @var array
     */
    protected $verifyFields = [
        'subtotal',
        'discount',
        'shipping',
        'grand_total_excl_tax',
        'grand_total_incl_tax',
        'tax'
    ];

    /**
     * Verifiable fields for cart items.
     *
     * @var array
     */
    protected $cartItemVerifiableFields = [
        'cart_item_price',
        'cart_item_subtotal'
    ];

    /**
     * Prepare prices for credit memo.
     *
     * @param array $prices
     * @return array
     */
    protected function preparePricesCreditMemo(array $prices)
    {
        unset($prices['shipping']);
        return $prices;
    }

    /**
     * Returns string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Prices on backend after order creation is correct.';
    }
}
