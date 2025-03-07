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

namespace Mage\Install\Test\Block;

use Magento\Mtf\Block\Block;

/**
 * Continue block.
 */
class ContinueDownloadBlock extends Block
{
    /**
     * Continue button selector.
     *
     * @var string
     */
    protected $continueValidation = '#button-validate';

    /**
     * Continue button selector.
     *
     * @var string
     */
    protected $continueDeploy = '#button-deploy';

    /**
     * Continue button selector.
     *
     * @var string
     */
    protected $continueDownload = '#button-downloader';

    /**
     * Continue button selector.
     *
     * @var string
     */
    protected $startDownload = '#install_all button[type="submit"]';

    /**
     * Continue Magento installaltion button selector.
     *
     * @var string
     */
    protected $continueMagentoInstallation = '#connect_iframe_success button[type="button"]';

    /**
     * Continue installation.
     *
     * @return void
     */
    public function continueDeploy()
    {
        $this->_rootElement->find($this->continueDeploy)->click();
    }

    /**
     * Continue installation.
     *
     * @return void
     */
    public function continueValidation()
    {
        $this->_rootElement->find($this->continueValidation)->click();
    }

    /**
     * Continue installation.
     *
     * @return void
     */
    public function continueDownload()
    {
        $this->waitForElementVisible($this->continueDownload);
        $this->_rootElement->find($this->continueDownload)->click();
    }

    /**
     * Continue installation.
     *
     * @return void
     */
    public function startDownload()
    {
        $this->_rootElement->find($this->startDownload)->click();
    }

    /**
     * Continue installation.
     *
     * @return void
     */
    public function continueMagentoInstallation()
    {
        $this->waitForElementVisible($this->continueMagentoInstallation);
        $this->_rootElement->find($this->continueMagentoInstallation)->click();
    }
}
