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

namespace Mage\Adminhtml\Test\Block\Catalog\Product\Edit\Tab;

use Mage\Adminhtml\Test\Block\Catalog\Product\Edit\Tab\Related\Grid as UpsellGrid;
use Magento\Mtf\Client\Element\SimpleElement as Element;

/**
 * Upsell Tab.
 */
class Upsell extends AbstractAppurtenant
{
    /**
     * Upsell products type.
     *
     * @var string
     */
    protected $type = 'up_sell_products';

    /**
     * Locator for Upsell products grid.
     *
     * @var string
     */
    protected $upsellGrid = '#up_sell_product_grid';

    /**
     * Get Upsell grid.
     *
     * @param Element|null $element [optional]
     * @return UpsellGrid
     */
    protected function getGrid(Element $element = null)
    {
        $element = $element ? $element : $this->_rootElement;
        return $this->blockFactory->create(
            '\Mage\Adminhtml\Test\Block\Catalog\Product\Edit\Tab\Upsell\Grid',
            ['element' => $element->find($this->upsellGrid)]
        );
    }
}
