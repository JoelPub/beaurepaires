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

namespace Mage\Adminhtml\Test\Block\Sales\Order\Create;

use Mage\SalesRule\Test\Fixture\SalesRule;
use Magento\Mtf\Block\Form;
use Magento\Mtf\Client\Locator;
use Mage\Adminhtml\Test\Block\Template;

/**
 * Adminhtml sales order coupons block.
 */
class Coupons extends Form
{
    /**
     * Backend abstract block.
     *
     * @var string
     */
    protected $templateBlock = './ancestor::body';

    /**
     * Click apply button selector.
     *
     * @var string
     */
    protected $applyButton = '//*[@id="coupons:code"]/following-sibling::button';

    /**
     * Get backend abstract block.
     *
     * @return Template
     */
    public function getTemplateBlock()
    {
        return $this->blockFactory->create(
            'Mage\Adminhtml\Test\Block\Template',
            ['element' => $this->_rootElement->find($this->templateBlock, Locator::SELECTOR_XPATH)]
        );
    }

    /**
     * Apply Sales rule coupon.
     *
     * @param SalesRule $salesRule
     * @return void
     */
    public function applyCouponsCode(SalesRule $salesRule)
    {
        parent::fill($salesRule);
        $this->_rootElement->find($this->applyButton, Locator::SELECTOR_XPATH)->click();
        $this->getTemplateBlock()->waitLoader();
    }
}
