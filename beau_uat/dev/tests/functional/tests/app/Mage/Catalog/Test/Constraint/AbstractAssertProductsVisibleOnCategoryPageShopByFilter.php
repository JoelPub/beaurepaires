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

namespace Mage\Catalog\Test\Constraint;

use Magento\Mtf\Fixture\InjectableFixture;
use Magento\Mtf\Constraint\AbstractConstraint;
use Mage\Catalog\Test\Page\Category\CatalogCategoryView;

/**
 * Assert that filtered product is present on category page by filter and another products are absent.
 */
abstract class AbstractAssertProductsVisibleOnCategoryPageShopByFilter extends AbstractConstraint
{
    /**
     * Check is visible product on category page.
     *
     * @param CatalogCategoryView $catalogCategoryView
     * @param InjectableFixture[] $products
     * @param string $searchProductsIndexes
     * @return void
     */
    protected function verify(CatalogCategoryView $catalogCategoryView, array $products, $searchProductsIndexes)
    {
        $searchProductsIndexes = explode(',', $searchProductsIndexes);
        foreach ($products as $key => $product) {
            $isProductVisible = $catalogCategoryView->getListProductBlock()->isProductVisible($product);
            while (!$isProductVisible && $catalogCategoryView->getBottomToolbar()->nextPage()) {
                $isProductVisible = $catalogCategoryView->getListProductBlock()->isProductVisible($product);
            }
            $expected = in_array($key, $searchProductsIndexes) ? true : false;
            \PHPUnit_Framework_Assert::assertEquals($expected, $isProductVisible);
        }
    }
}
