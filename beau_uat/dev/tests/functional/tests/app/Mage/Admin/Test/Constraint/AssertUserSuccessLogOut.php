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

namespace Mage\Admin\Test\Constraint;

use Mage\Adminhtml\Test\Page\Adminhtml\Dashboard;
use Magento\Mtf\Constraint\AbstractConstraint;
use Mage\Adminhtml\Test\Page\AdminAuthLogin;

/**
 * Asserts that login form is present on page.
 */
class AssertUserSuccessLogOut extends AbstractConstraint
{
    /**
     * Constraint severeness.
     *
     * @var string
     */
    protected $severeness = 'low';

    /**
     * Asserts that login form is present on page.
     *
     * @param AdminAuthLogin $adminAuth
     * @param Dashboard $dashboard
     * @return void
     */
    public function processAssert(
        AdminAuthLogin $adminAuth,
        Dashboard $dashboard
    ) {
        $dashboard->getAdminPanelHeader()->logOut();
        $isLoginBlockVisible = $adminAuth->getLoginBlock()->isVisible();
        \PHPUnit_Framework_Assert::assertTrue($isLoginBlockVisible, 'Admin user was not logged out.');
    }

    /**
     * Return string representation of object.
     *
     * @return string
     */
    public function toString()
    {
        return 'User had successfully logged out.';
    }
}
