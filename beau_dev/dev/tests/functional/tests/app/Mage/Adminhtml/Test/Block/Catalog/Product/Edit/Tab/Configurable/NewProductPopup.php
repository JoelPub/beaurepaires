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

namespace Mage\Adminhtml\Test\Block\Catalog\Product\Edit\Tab\Configurable;

use Mage\Adminhtml\Test\Block\Catalog\Product\ProductForm;
use Mage\Adminhtml\Test\Block\Catalog\Product\FormPageActions;

/**
 * Product form on new product popup.
 */
class NewProductPopup extends ProductForm
{
    /**
     * Selector for form page actions block.
     *
     * @var string
     */
    protected $formPageActions = '.content-header';

    /**
     * Selector for success save message.
     *
     * @var string
     */
    protected $saveMessage = '.success-msg';

    /**
     * Get form page actions block.
     *
     * @return FormPageActions
     */
    public function getFormPageActions()
    {
        return $this->blockFactory->create(
            'Mage\Adminhtml\Test\Block\Catalog\Product\FormPageActions',
            ['element' => $this->_rootElement->find($this->formPageActions)]
        );
    }

    /**
     * Close popup.
     *
     * @return void
     */
    public function close()
    {
        $this->waitForElementVisible($this->saveMessage);
        $this->browser->closeWindow();
        $this->browser->selectWindow();
    }
}
