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

namespace Mage\Paypal\Test\TestCase;

/**
 * Preconditions:
 * 1. Create product.
 * 2. Apply configuration for test.
 *
 * Steps:
 * 1. Go to Frontend.
 * 2. Add products to the cart.
 * 3. Apply discount coupon or add gift card if necessary, click "Checkout with PayPal" button.
 * 4. Login to PayPal.
 * 5. Process checkout via PayPal.
 * 6. Go to Sales > Orders.
 * 7. Select created order in the grid and open it.
 * 8. Click 'Ship' button.
 * 9. Fill data according to dataset.
 * 10. Click 'Submit Shipment' button.
 * 11. Perform asserts.
 *
 * @group Payment_Methods_(CS), PayPal_(CS)
 * @ZephyrId MPERF-7154
 */
class CreateShipmentForPaypalExpressCheckoutTest extends AbstractCreateSalesEntityForPaypalExpressCheckoutTest
{
    /* tags */
    const TEST_TYPE = '3rd_party_test';
    /* end tags */
}
