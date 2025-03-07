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

namespace Mage\Checkout\Test\Block\Multishipping\Shipping;

use Mage\Checkout\Test\Block\Multishipping\AbstractMultishipping\AbstractItems;
use Magento\Mtf\Client\Locator;
use Magento\Mtf\Fixture\InjectableFixture;
use Magento\Mtf\Client\ElementInterface;

/**
 * Items block on checkout with multishipping shipping page.
 */
class Items extends AbstractItems
{
    /**
     * Selector for item block.
     *
     * @var string
     */
    protected $itemBlock = '//div[@class="col2-set" and .//address[%s]]';

    /**
     * Address fields for selector.
     *
     * @var array
     */
    protected $addressFields = [
        'firstname',
        'lastname',
        'street',
        'city',
        'region_id',
        'postcode',
        'country_id'
    ];

    /**
     * Get element for item block.
     *
     * @param InjectableFixture $address
     * @param int $itemIndex
     * @return ElementInterface
     */
    protected function getItemBlockElement(InjectableFixture $address, $itemIndex)
    {
        $conditions = [];
        foreach ($this->addressFields as $field) {
            $conditions[] = "contains(., '{$address->getData($field)}')";
        }
        $itemBlockSelector = sprintf($this->itemBlock, implode(' and ', $conditions));
        return $this->_rootElement->find($itemBlockSelector, Locator::SELECTOR_XPATH);
    }

    /**
     * Get path for items class.
     *
     * @return string
     */
    protected function getItemBlockClass()
    {
        return 'Mage\Checkout\Test\Block\Multishipping\Shipping\Items\Item';
    }
}
