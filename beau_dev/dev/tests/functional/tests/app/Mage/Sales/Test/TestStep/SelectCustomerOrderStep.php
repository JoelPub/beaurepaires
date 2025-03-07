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

namespace Mage\Sales\Test\TestStep;

use Mage\Customer\Test\Fixture\Customer;
use Mage\Sales\Test\Page\Adminhtml\SalesOrderCreateIndex;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Select Customer for Order.
 */
class SelectCustomerOrderStep implements TestStepInterface
{
    /**
     * Sales order create index page.
     *
     * @var SalesOrderCreateIndex
     */
    protected $orderCreateIndex;

    /**
     * Customer fixture.
     *
     * @var Customer
     */
    protected $customer;

    /**
     * @constructor
     * @param SalesOrderCreateIndex $orderCreateIndex
     * @param Customer $customer
     */
    public function __construct(SalesOrderCreateIndex $orderCreateIndex, Customer $customer)
    {
        $this->orderCreateIndex = $orderCreateIndex;
        $this->customer = $customer;
    }

    /**
     * Select Customer for Order.
     *
     * @return void
     */
    public function run()
    {
        $this->orderCreateIndex->getCustomerGrid()->selectCustomer($this->customer);
    }
}
