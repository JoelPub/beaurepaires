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

use Mage\Admin\Test\Fixture\User;
use Mage\Cms\Test\Page\CmsIndex;
use Mage\Install\Test\Page\DownloaderDeployEnd;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Check that Magento successfully deployed.
 */
class AssertSuccessDeploy extends AbstractConstraint
{
    /**
     * Part of license agreement text.
     */
    const SUCCESSFULLY_DEPLOY_TEXT = 'Magento has been downloaded successfully.';

    /**
     * Assert that Magento successfully installed.
     *
     * @param DownloaderDeployEnd $downloaderDeployEnd
     * @param CmsIndex $cmsIndex
     * @param string $successDeployMessage
     * @return void
     */
    public function processAssert(DownloaderDeployEnd $downloaderDeployEnd)
    {
        // Check DeployWizardEnd page title text.
        \PHPUnit_Framework_Assert::assertContains(self::SUCCESSFULLY_DEPLOY_TEXT, $downloaderDeployEnd->getMainBlock()->getDeployStatus());
    }

    /**
     * Returns a string representation of successful assertion.
     *
     * @return string
     */
    public function toString()
    {
        return "Downloading";
    }
}
