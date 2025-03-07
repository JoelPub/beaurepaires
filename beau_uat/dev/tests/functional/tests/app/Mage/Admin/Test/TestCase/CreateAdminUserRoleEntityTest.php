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

namespace Mage\Admin\Test\TestCase;

use Mage\Admin\Test\Fixture\Role;
use Mage\Admin\Test\Page\Adminhtml\UserRoleEditRole;
use Mage\Admin\Test\Page\Adminhtml\UserRoleIndex;
use Magento\Mtf\TestCase\Injectable;

/**
 * Test Flow:
 * 1. Log in as default admin user.
 * 2. Go to System -> Permissions -> Roles.
 * 3. Press "Add new role" button to start create New Role.
 * 4. Fill in all data according to data set.
 * 5. Save role.
 * 6. Perform assertions.
 *
 * @group ACL_(MX)
 * @ZephyrId MPERF-7318
 */
class CreateAdminUserRoleEntityTest extends Injectable
{
    /**
     * User role index page.
     *
     * @var UserRoleIndex
     */
    protected $userRoleIndex;

    /**
     * User role edit page.
     *
     * @var UserRoleEditRole
     */
    protected $userRoleEditRole;

    /**
     * Injection data.
     *
     * @param UserRoleIndex $userRoleIndex
     * @param UserRoleEditRole $userRoleEditRole
     * @return void
     */
    public function __inject(UserRoleIndex $userRoleIndex, UserRoleEditRole $userRoleEditRole)
    {
        $this->userRoleIndex = $userRoleIndex;
        $this->userRoleEditRole = $userRoleEditRole;
    }

    /**
     * Run Create Admin User Role Entity test.
     *
     * @param Role $role
     * @return void
     */
    public function test(Role $role)
    {
        //Steps
        $this->userRoleIndex->open();
        $this->userRoleIndex->getRoleActions()->addNew();
        $this->userRoleEditRole->getRoleFormTabs()->fill($role);
        $this->userRoleEditRole->getPageActions()->save();
    }
}
