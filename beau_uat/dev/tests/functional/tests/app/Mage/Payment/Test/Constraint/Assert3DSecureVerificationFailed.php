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

namespace Mage\Payment\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Mage\Checkout\Test\Page\CheckoutOnepage;

/**
 * Assert that 3D secure verification failed.
 */
class Assert3DSecureVerificationFailed extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Verification fail message.
     */
    const VERIFICATION_FAIL_MESSAGE = 'Verification Failed';

    /**
     * Assert that 3D secure verification failed.
     *
     * @param CheckoutOnepage $checkoutOnepage
     * @return void
     */
    public function processAssert(CheckoutOnepage $checkoutOnepage)
    {
        \PHPUnit_Framework_Assert::assertContains(
            self::VERIFICATION_FAIL_MESSAGE,
            $checkoutOnepage->getReviewBlock()->getVerificationResponseText()
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return '3D secure verification failed.';
    }
}
