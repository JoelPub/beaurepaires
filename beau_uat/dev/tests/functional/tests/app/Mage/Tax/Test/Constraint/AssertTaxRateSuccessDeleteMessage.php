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

use Mage\Tax\Test\Page\Adminhtml\TaxRateIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert that success delete message is displayed after tax rate has been deleted.
 */
class AssertTaxRateSuccessDeleteMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Text for verify.
     */
    const SUCCESS_DELETE_MESSAGE = 'The tax rate has been deleted.';

    /**
     * Assert that success delete message is displayed after tax rate has been deleted.
     *
     * @param TaxRateIndex $taxRateIndex
     * @return void
     */
    public function processAssert(TaxRateIndex $taxRateIndex)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_DELETE_MESSAGE,
            $taxRateIndex->getMessagesBlock()->getSuccessMessages()
        );
    }

    /**
     * Returns string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Tax rate success delete message is present.';
    }
}
