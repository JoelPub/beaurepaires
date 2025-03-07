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

namespace Enterprise\Cms\Test\Block\Adminhtml\Cms\Page\Revision\Edit;

use Mage\Adminhtml\Test\Block\Widget\FormTabs;

/**
 * Block revision content form.
 */
class Form extends FormTabs
{
    /**
     * Content editor toggle button selector.
     *
     * @var string
     */
    protected $toggleButton = "#togglepage_content";

    /**
     * Content editor form.
     *
     * @var string
     */
    protected $contentForm = "#page_content";

    /**
     * Content Show/Hide Editor toggle button.
     *
     * @return void
     */
    public function toggleEditor()
    {
        $content = $this->_rootElement->find($this->contentForm);
        $toggleButton = $this->_rootElement->find($this->toggleButton);
        if (!$content->isVisible() && $toggleButton->isVisible()) {
            $toggleButton->click();
        }
    }
}
