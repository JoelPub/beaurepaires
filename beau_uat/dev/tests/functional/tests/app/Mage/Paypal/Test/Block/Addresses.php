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

namespace Mage\Paypal\Test\Block;

use Mage\Paypal\Test\Fixture\PaypalAddress;
use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Pay Pal sandbox addresses block.
 */
class Addresses extends Block
{
    /**
     * Address selector.
     *
     * @var string
     */
    protected $addressSelector = '//li[//div[contains(@class, "adr")]]/a[contains(.,"%s")]';

    /**
     * Select address by criteria.
     *
     * @param PaypalAddress $address
     * @param string $criteria [optional]
     * @throws \Exception
     * @return void
     */
    public function selectAddress(PaypalAddress $address, $criteria = 'street')
    {
        if ($address->hasData($criteria)) {
            $addressSelector = sprintf($this->addressSelector, $address->getData($criteria));
            $this->_rootElement->find($addressSelector, Locator::SELECTOR_XPATH)->click();
        } else {
            throw new \Exception("$criteria field is absent in provided address fixture.");
        }
    }
}
