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

namespace Mage\Sitemap\Test\Constraint;

use Magento\Mtf\Constraint\AbstractConstraint;
use Mage\Sitemap\Test\Page\Adminhtml\SitemapIndex;
use Mage\Sitemap\Test\Fixture\Sitemap;

/**
 * Assert that error message is displayed after creating sitemap with wrong folder.
 */
class AssertSitemapFailFolderSaveMessage extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'low';
    /* end tags */

    /**
     * Wrong folder error message.
     */
    const FAIL_FOLDER_MESSAGE = 'Please create the specified folder "%s" before saving the sitemap.';

    /**
     * Assert that error message is displayed after creating sitemap with wrong folder.
     *
     * @param SitemapIndex $sitemapIndex
     * @param Sitemap $sitemap
     * @return void
     */
    public function processAssert(SitemapIndex $sitemapIndex, Sitemap $sitemap)
    {
        \PHPUnit_Framework_Assert::assertEquals(
            sprintf(self::FAIL_FOLDER_MESSAGE, $sitemap->getSitemapPath()),
            $sitemapIndex->getMessagesBlock()->getErrorMessages()
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Error message after creating sitemap with wrong folder is present.';
    }
}
