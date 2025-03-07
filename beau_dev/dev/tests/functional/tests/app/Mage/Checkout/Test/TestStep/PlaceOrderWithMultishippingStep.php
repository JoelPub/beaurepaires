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

namespace Mage\Checkout\Test\TestStep;

use Mage\Checkout\Test\Page\CheckoutMultishippingOverview;
use Mage\Checkout\Test\Page\CheckoutMultishippingSuccess;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Place order in checkout multishipping overview page.
 */
class PlaceOrderWithMultishippingStep implements TestStepInterface
{
    /**
     * Checkout multishipping overview page.
     *
     * @var CheckoutMultishippingOverview
     */
    protected $checkoutMultishippingOverview;

    /**
     * Checkout multishipping success page.
     *
     * @var CheckoutMultishippingSuccess
     */
    protected $checkoutMultishippingSuccess;

    /**
     * @constructor
     * @param CheckoutMultishippingOverview $checkoutMultishippingOverview
     * @param CheckoutMultishippingSuccess $checkoutMultishippingSuccess
     */
    public function __construct(
        CheckoutMultishippingOverview $checkoutMultishippingOverview,
        CheckoutMultishippingSuccess $checkoutMultishippingSuccess
    ) {
        $this->checkoutMultishippingOverview = $checkoutMultishippingOverview;
        $this->checkoutMultishippingSuccess = $checkoutMultishippingSuccess;
    }

    /**
     * Place order with multishipping.
     *
     * @return mixed
     */
    public function run()
    {
        $this->checkoutMultishippingOverview->getOverviewBlock()->clickPlaceOrder();
        $ordersIds = $this->checkoutMultishippingSuccess->getSuccessBlock()->getOrdersIds();

        return ['ordersIds' => $ordersIds];
    }
}
