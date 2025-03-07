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

use Mage\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Mage\Catalog\Test\Fixture\CatalogCategory;
use Mage\Catalog\Test\Page\Category\CatalogCategoryView;

/**
 * Assert that products are absent in category frontend page.
 */
class AssertProductsNotVisibleInCategory extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that products are absent in category frontend page.
     *
     * @param CatalogCategoryView $catalogCategoryView
     * @param CmsIndex $cmsIndex
     * @param CatalogCategory $category
     * @param array $unassignedProducts
     * @return void
     */
    public function processAssert(
        CatalogCategoryView $catalogCategoryView,
        CmsIndex $cmsIndex,
        CatalogCategory $category,
        array $unassignedProducts
    ) {
        $cmsIndex->open();
        $cmsIndex->getTopmenu()->selectCategory($category->getName());
        foreach ($unassignedProducts as $product) {
            $isProductVisible = $catalogCategoryView->getListProductBlock()->isProductVisible($product);
            while (!$isProductVisible && $catalogCategoryView->getBottomToolbar()->nextPage()) {
                $isProductVisible = $catalogCategoryView->getListProductBlock()->isProductVisible($product);
            }
            \PHPUnit_Framework_Assert::assertFalse(
                $isProductVisible,
                "Product {$product->getName()} is present in category page."
            );
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Products are absent in category page';
    }
}
