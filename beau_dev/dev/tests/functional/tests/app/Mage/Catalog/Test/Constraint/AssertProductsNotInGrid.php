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

use Mage\Catalog\Test\Page\Adminhtml\CatalogProduct;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\InjectableFixture;

/**
 * Assert that products cannot be found by name and sku on product grid.
 */
class AssertProductsNotInGrid extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that products cannot be found by name and sku on product grid.
     *
     * @param InjectableFixture[] $products
     * @param CatalogProduct $productGrid
     * @param AssertProductNotInGrid $assertProductNotInGrid
     * @return void
     */
    public function processAssert(
        array $products,
        CatalogProduct $productGrid,
        AssertProductNotInGrid $assertProductNotInGrid
    ) {
        $productGrid->open();
        foreach ($products as $product) {
            $assertProductNotInGrid->assert($product, $productGrid);
        }
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Assertion that products is absent in products grid.';
    }
}
