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

namespace Enterprise\Install\Test\TestCase;

use Mage\Admin\Test\Fixture\User;

/**
 * PLEASE ADD NECESSARY INFO BEFORE RUNNING TEST TO ../dev/tests/functional/etc/config.xml
 *
 * Preconditions:
 * 1. Uninstall Magento.
 *
 * Steps:
 * 1. Open Magento install page.
 * 2. Check license agreement text.
 * 3. Accept license and click continue button.
 * 4. Fill DB configuration settings according to dataSet and click continue button.
 * 5. Fill admin user info and click continue button.
 * 6. Perform assertions.
 *
 * @group Installer_and_Upgrade/Downgrade_(PS)
 * @ZephyrId MPERF-7483
 */
class InstallTest4 extends InstallTest
{
    //
}
