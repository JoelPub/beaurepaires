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

namespace Mage\SalesRule\Test\TestStep;

use Mage\SalesRule\Test\Fixture\SalesRule;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Creating sales rule.
 */
class CreateSalesRuleStep implements TestStepInterface
{
    /**
     * Sales Rule coupon.
     *
     * @var string
     */
    protected $salesRule;

    /**
     * Factory for Fixture.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param string $salesRule [optional]
     */
    public function __construct(FixtureFactory $fixtureFactory, $salesRule = '-')
    {
        $this->fixtureFactory = $fixtureFactory;
        $this->salesRule = $salesRule;
    }

    /**
     * Run create sales rule step.
     *
     * @return array
     */
    public function run()
    {
        return ['salesRule' => $this->salesRule != '-' ? $this->createSalesRule() : null];
    }

    /**
     * Create sales rule.
     *
     * @return SalesRule
     */
    protected function createSalesRule()
    {
        $salesRule = $this->fixtureFactory->createByCode('salesRule', ['dataset' => $this->salesRule]);
        $salesRule->persist();
        return $salesRule;
    }
}
