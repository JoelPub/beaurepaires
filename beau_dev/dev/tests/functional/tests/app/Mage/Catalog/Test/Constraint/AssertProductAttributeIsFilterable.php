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
use Mage\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Mage\Catalog\Test\Fixture\CatalogProductAttribute;
use Mage\Catalog\Test\Page\Category\CatalogCategoryView;

/**
 * Check whether the attribute filter is displayed on the frontend in Layered navigation.
 */
class AssertProductAttributeIsFilterable extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Check whether the attribute filter is displayed on the frontend in Layered navigation.
     *
     * @param CatalogCategoryView $catalogCategoryView
     * @param InjectableFixture $product
     * @param CatalogProductAttribute $attribute
     * @param CmsIndex $cmsIndex
     * @return void
     */
    public function processAssert(
        CatalogCategoryView $catalogCategoryView,
        InjectableFixture $product,
        CatalogProductAttribute $attribute,
        CmsIndex $cmsIndex
    ) {
        $cmsIndex->open()->getTopmenu()->selectCategory($product->getCategoryIds()[0]);
        $label = $attribute->hasData('manage_frontend_label')
            ? $attribute->getManageFrontendLabel()
            : $attribute->getFrontendLabel();
        \PHPUnit_Framework_Assert::assertTrue(
            in_array(strtoupper($label), $catalogCategoryView->getLayeredNavigationBlock()->getFilters()),
            'Attribute is absent in layered navigation on category page.'
        );
    }

    /**
     * Return string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Attribute is present in layered navigation on category page.';
    }
}
