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

namespace Mage\Adminhtml\Test\Block\CatalogRule\Promo;

use Mage\Adminhtml\Test\Block\Widget\Grid;

/**
 * Backend catalog price rule grid.
 */
class Catalog extends Grid
{
    /**
     * An element locator which allows to select first entity in grid.
     *
     * @var string
     */
    protected $editLink = 'tbody tr:first-child td';

    /**
     * Filters array mapping.
     *
     * @var array
     */
    protected $filters = [
        'rule_id' => [
            'selector' => '#promo_catalog_grid_filter_rule_id',
        ],
        'name' => [
            'selector' => '#promo_catalog_grid_filter_name',
        ],
        'from_date' => [
            'selector' => '[name="from_date[from]"]',
        ],
        'to_date' => [
            'selector' => '[name="from_date[to]"]',
        ],
        'is_active' => [
            'selector' => '#promo_catalog_grid_filter_is_active',
            'input' => 'select',
        ],
        'rule_website' => [
            'selector' => '#promo_catalog_grid_filter_rule_website',
            'input' => 'select',
        ],
    ];
}
