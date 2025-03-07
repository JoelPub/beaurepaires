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

namespace Mage\Install\Test\Constraint;

use Mage\Install\Test\Page\downloaderWelcome;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that agreement text present on Terms & Agreement page during install.
 */
class AssertWelcomeWizardTextPresent extends AbstractConstraint
{
    /**
     * Part of license agreement text.
     */
    const WELCOME_WIZARD_TEXT = 'Welcome to Magento Downloader!';

    /**
     * Assert that part of license agreement text is present on Terms & Agreement page.
     *
     * @param downloaderWelcome $downloaderWelcome
     * @return void
     */
    public function processAssert(downloaderWelcome $downloaderWelcome)
    {
        \PHPUnit_Framework_Assert::assertContains(
            self::WELCOME_WIZARD_TEXT,
            $downloaderWelcome->getWelcomeBlock()->getWizardTitle(),
            'This wrong page'
        );
    }

    /**
     * Returns a string representation of successful assertion.
     *
     * @return string
     */
    public function toString()
    {
        return "License agreement text is present on Terms & Agreement page.";
    }
}
