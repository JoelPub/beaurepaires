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

namespace Mage\Sales\Test\Constraint;

use Mage\Sales\Test\Fixture\Order;
use Mage\Adminhtml\Test\Block\Widget\Grid;
use Mage\Shipping\Test\Page\Adminhtml\SalesShipment;

/**
 * Abstract assert sales entity with corresponding filter is present in 'Sales Entity' with correct data.
 */
abstract class AbstractAssertSalesEntityInSalesEntityGrid extends AbstractAssertSalesEntityInGrid
{
    /**
     * Sales entity index page.
     *
     * @var SalesShipment
     */
    protected $salesEntityIndexPage;

    /**
     * Open page for assert.
     *
     * @return void
     */
    protected function openPage()
    {
        $this->salesEntityIndexPage->open();
    }

    /**
     * Get grid for assert.
     *
     * @return Grid
     */
    protected function getGrid()
    {
        return $this->salesEntityIndexPage->getGrid();
    }

    /**
     * Get default filter.
     *
     * @param string $entityId
     * @return array
     */
    protected function getDefaultFilter($entityId)
    {
        return ['id' => $entityId, 'order_id' => $this->orderId];
    }
}
