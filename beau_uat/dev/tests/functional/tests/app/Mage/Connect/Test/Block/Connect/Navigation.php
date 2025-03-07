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

namespace Mage\Connect\Test\Block\Connect;

use Magento\Mtf\Block\Form;
use Mage\Admin\Test\Fixture\User;

class Navigation extends Form
{
     /**
     * Locator of 'Settings' tab
     *
     * @var string
     */
    protected $settingsTab = 'a[href*="settings"]';

    /**
     * locator of 'Extensions' tab
     * 
     * @var string
     */
    protected $extensionsTab = 'a[href*="connectPackages"]';

    /**
     * Open 'Settings' tab
     */
    public function openSettingsTabs()
    {
        $this->_rootElement->find($this->settingsTab)->click();
    }

    /**
     * Open 'Extensions' tab
     */
    public function openExtensionsTabs()
    {
        $this->_rootElement->find($this->extensionsTab)->click();
    }
}
