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

namespace Mage\Customer\Test\TestStep;

use Mage\Customer\Test\Fixture\Customer;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Create customer step.
 */
class CreateCustomerStep implements TestStepInterface
{
    /**
     * Customer fixture.
     *
     * @var Customer
     */
    protected $customer;

    /**
     * Flag for customer creation.
     *
     * @var bool
     */
    protected $persistCustomer = true;

    /**
     * @constructor
     * @param Customer $customer
     * @param string $customerPersist [optional]
     */
    public function __construct(Customer $customer, $customerPersist = 'yes')
    {
        $this->customer = $customer;
        $this->persistCustomer = $customerPersist;
    }

    /**
     * Create customer.
     *
     * @return array
     */
    public function run()
    {
        if ($this->persistCustomer == 'yes') {
            $this->customer->persist();
        }

        return ['customer' => $this->customer];
    }
}
