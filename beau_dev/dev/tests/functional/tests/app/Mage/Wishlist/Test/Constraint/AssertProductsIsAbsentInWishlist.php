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

namespace Mage\Wishlist\Test\Constraint;

use Mage\Cms\Test\Page\CmsIndex;
use Mage\Customer\Test\Fixture\Customer;
use Mage\Wishlist\Test\Page\WishlistIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\InjectableFixture;
use Mage\Customer\Test\Constraint\FrontendActionsForCustomer;

/**
 * Assert products is absent in Wishlist on frontend.
 */
class AssertProductsIsAbsentInWishlist extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Assert that product is not present in Wishlist on frontend.
     *
     * @param WishlistIndex $wishlistIndex
     * @param CmsIndex $cmsIndex
     * @param Customer $customer
     * @param FrontendActionsForCustomer $frontendActionsForCustomer
     * @param InjectableFixture[] $products
     * @return void
     */
    public function processAssert(
        WishlistIndex $wishlistIndex,
        CmsIndex $cmsIndex,
        Customer $customer,
        FrontendActionsForCustomer $frontendActionsForCustomer,
        array $products
    ) {
        $frontendActionsForCustomer->loginCustomer($customer);
        $cmsIndex->getTopLinksBlock()->openAccountLink("My Wishlist");
        $itemBlock = $wishlistIndex->getItemsBlock();
        foreach ($products as $itemProduct) {
            \PHPUnit_Framework_Assert::assertFalse(
                $itemBlock->getItemProductBlock($itemProduct)->isVisible(),
                "Product '{$itemProduct->getName()}' is present in Wishlist on frontend."
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
        return 'Product is absent in Wishlist on frontend.';
    }
}
