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

namespace Mage\Paypal\Test\Fixture\PaypalCustomer;

use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\Fixture\FixtureInterface;
use Mage\Paypal\Test\Fixture\PaypalAddress as AddressFixture;

/**
 * Pay Pal Addresses source for customer fixture.
 */
class Address implements FixtureInterface
{
    /**
     * Source data.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Source parameters.
     *
     * @var array
     */
    protected $params;

    /**
     * Customer addresses fixture.
     *
     * @var array
     */
    protected $addressesFixture;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $params [optional]
     * @param array $data [optional]
     */
    public function __construct(FixtureFactory $fixtureFactory, array $params = [], array $data = [])
    {
        $this->params = $params;

        if (isset($data['presets'])) {
            $data['presets'] = explode(',', $data['presets']);
            foreach ($data['presets'] as $value) {
                /** @var AddressFixture $fixture */
                $addresses = $fixtureFactory->create(
                    'Mage\Paypal\Test\Fixture\PaypalAddress',
                    ['dataset' => $value]
                );
                $this->data[] = $addresses->getData();
                $this->addressesFixture[] = $addresses;
            }
        } elseif (empty($data['presets']) && !empty($data['addresses'])) {
            foreach ($data['addresses'] as $addresses) {
                /** @var AddressFixture $addresses */
                $this->data[] = $addresses->getData();
                $this->addressesFixture[] = $addresses;
            }
        }
    }

    /**
     * Persists prepared data into application.
     *
     * @return void
     */
    public function persist()
    {
        //
    }

    /**
     * Return prepared data set.
     *
     * @param int|null $key [optional]
     * @return array
     */
    public function getData($key = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $this->data;
    }

    /**
     * Return data set configuration settings.
     *
     * @return array
     */
    public function getDataConfig()
    {
        return $this->params;
    }

    /**
     * Getting addresses fixture.
     *
     * @return array
     */
    public function getAddresses()
    {
        return $this->addressesFixture;
    }
}
