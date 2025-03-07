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

namespace Mage\Adminhtml\Test\Block\Checkout\Agreement\Edit;

use Magento\Mtf\Client\Element\SimpleElement as Element;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\FixtureInterface;

/**
 * Form for creation of the term.
 */
class Form extends \Magento\Mtf\Block\Form
{
    /**
     * Selector for store view field.
     *
     * @var string
     */
    protected $storeView = '#store_id';

    /**
     * Fill form.
     *
     * @param FixtureInterface $checkoutAgreement
     * @param Element|null $element
     * @return $this
     */
    public function fill(FixtureInterface $checkoutAgreement, Element $element = null)
    {
        parent::fill($checkoutAgreement, $element);
        $this->fillWebsite();

        return $this;
    }

    /**
     * Fill website field.
     *
     * @return void
     */
    protected function fillWebsite()
    {
        $storeViewField = $this->_rootElement->find($this->storeView, Locator::SELECTOR_CSS, 'multiselectgrouplist');
        if ($storeViewField->isVisible() && !$storeViewField->getValue()) {
            $storeViewField->setValue('All Store Views');
        }
    }
}
