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

namespace Mage\CurrencySymbol\Test\Constraint;

use Mage\CurrencySymbol\Test\Page\Adminhtml\SystemCurrencySymbolIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that after clicking on 'Save Currency Symbols' button success message appears.
 */
class AssertCurrencySymbolSuccessSaveMessage extends AbstractConstraint
{
    /**
     * Text value to be checked.
     */
    const SUCCESS_SAVE_MESSAGE = 'Custom currency symbols were applied successfully.';

    /**
     * Constraint severeness.
     *
     * @var string
     */
    protected $severeness = 'low';

    /**
     * Assert that after clicking on 'Save Currency Symbols' button success message appears.
     *
     * @param SystemCurrencySymbolIndex $currencySymbolIndex
     * @return void
     */
    public function processAssert(SystemCurrencySymbolIndex $currencySymbolIndex)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            self::SUCCESS_SAVE_MESSAGE,
            $currencySymbolIndex->getMessagesBlock()->getSuccessMessages(),
            'Wrong success message is displayed.'
        );
    }

    /**
     * Returns a string representation of successful assertion.
     *
     * @return string
     */
    public function toString()
    {
        return 'Currency Symbol success save message is correct.';
    }
}
