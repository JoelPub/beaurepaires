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

namespace Mage\Checkout\Test\Block\Onepage;

use Magento\Mtf\Client\Locator;
use Magento\Mtf\Block\Block;

/**
 * One page checkout success block.
 */
class Success extends Block
{
    /**
     * Determine order id if checkout was performed by guest.
     *
     * @var string
     */
    protected $orderIdGuest = '//div[contains(@class, "col-main")]//p[1][contains(text(), "Your order")]';

    /**
     * Determine order id if checkout was performed by registered customer.
     *
     * @var string
     */
    protected $orderId = 'a[href*="view/order_id"]';

    /**
     * Get Id of placed order for guest checkout.
     *
     * @return string
     */
    public function getGuestOrderId()
    {
        $this->waitForElementVisible($this->orderIdGuest, Locator::SELECTOR_XPATH);
        $orderString = $this->_rootElement->find($this->orderIdGuest, Locator::SELECTOR_XPATH)->getText();
        preg_match('/[\d]+/', $orderString, $orderId);
        return end($orderId);
    }

    /**
     * Click order id link.
     *
     * @return void
     */
    public function openOrder()
    {
        $this->_rootElement->find($this->orderId, Locator::SELECTOR_CSS)->click();
    }
}
