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

namespace Mage\Catalog\Test\Fixture\Cart;

use Mage\Catalog\Test\Fixture\CatalogProductSimple;
use Magento\Mtf\Fixture\DataSource;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Data for verify cart item block on checkout page.
 *
 * Data keys:
 *  - product (fixture data for verify)
 *
 * @SuppressWarnings(PHPMD.NPathComplexity)
 */
class Item extends DataSource
{
    /**
     * @constructor
     * @param FixtureInterface $product
     */
    public function __construct(FixtureInterface $product)
    {
        /** @var CatalogProductSimple $product */
        $checkoutData = $product->getCheckoutData();
        $cartItem = isset($checkoutData['cartItem']) ? $checkoutData['cartItem'] : [];
        $customOptions = $product->hasData('custom_options') ? $product->getCustomOptions() : [];
        $checkoutCustomOptions = isset($checkoutData['options']['custom_options'])
            ? $checkoutData['options']['custom_options']
            : [];

        foreach ($checkoutCustomOptions as $key => $checkoutCustomOption) {
            $attribute = str_replace('attribute_key_', '', $checkoutCustomOption['title']);
            $option = str_replace('option_key_', '', $checkoutCustomOption['value']);

            $checkoutCustomOptions[$key] = [
                'title' => isset($customOptions[$attribute]['title'])
                    ? $customOptions[$attribute]['title']
                    : $attribute,
                'value' => isset($customOptions[$attribute]['options'][$option]['title'])
                    ? $customOptions[$attribute]['options'][$option]['title']
                    : $option,
            ];
        }

        $cartItem['options'] = $checkoutCustomOptions;
        $cartItem['qty'] = isset($checkoutData['qty'])
            ? $checkoutData['qty']
            : 1;

        $this->data = $cartItem;
    }
}
