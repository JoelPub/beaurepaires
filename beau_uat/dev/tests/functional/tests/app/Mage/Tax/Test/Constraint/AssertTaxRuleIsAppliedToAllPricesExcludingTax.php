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

use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Checks that prices excl tax on category, product and cart pages are equal to specified in dataset.
 */
class AssertTaxRuleIsAppliedToAllPricesExcludingTax extends AbstractAssertTaxRuleIsAppliedToAllPrices
{
    /* tags */
    const SEVERITY = 'high';
    /* end tags */

    /**
     * Verify fields for category page.
     *
     * @var array
     */
    protected $categoryPrices = ['category_price'];

    /**
     * Verify fields for product page.
     *
     * @var array
     */
    protected $productPrices = ['product_view_price'];

    /**
     * Verify fields for assert.
     *
     * @var array
     */
    protected $verifyFields = [
        'subtotal',
        'discount',
        'shipping',
        'grand_total',
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
     * Get prices on category page.
     *
     * @param InjectableFixture $product
     * @return array
     */
    protected function getCategoryPrices(InjectableFixture $product)
    {
        return [
            'category_price' => $this->catalogCategoryView
                    ->getListProductBlock()
                    ->getProductPriceBlock($product->getName())
                    ->getResultPrice()
        ];
    }

    /**
     * Get product view prices.
     *
     * @return array
     */
    protected function getProductPagePrices()
    {
        return ['product_view_price' => $this->catalogProductView->getViewBlock()->getPriceBlock()->getResultPrice()];
    }

    /**
     * Returns string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Prices excl tax on category, product and cart pages are equal to specified in dataset.';
    }
}
