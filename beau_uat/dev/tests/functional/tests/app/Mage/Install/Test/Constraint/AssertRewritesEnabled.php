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

use Mage\Cms\Test\Page\CmsIndex;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Client\BrowserInterface;
use Mage\Catalog\Test\Fixture\CatalogCategory;

/**
 * Assert that apache redirect correct works.
 */
class AssertRewritesEnabled extends AbstractConstraint
{
    /**
     * Assert that apache redirect works by opening category page and asserting index.php in its url.
     *
     * @param CatalogCategory $category
     * @param CmsIndex $homePage
     * @param BrowserInterface $browser
     */
    public function processAssert(CatalogCategory $category, CmsIndex $homePage, BrowserInterface $browser)
    {
        $category->persist();
        $homePage->open();
        $homePage->getTopmenu()->selectCategory($category->getName());
        \PHPUnit_Framework_Assert::assertTrue(
            strpos($browser->getUrl(), 'index.php') === false,
            'Apache redirect for category does not work.'
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Apache redirect works correct.';
    }
}
