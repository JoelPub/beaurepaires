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

namespace Mage\Widget\Test\Constraint;

use Mage\Widget\Test\Fixture\Widget;
use Mage\Widget\Test\Page\Adminhtml\WidgetInstanceIndex;
use Magento\Mtf\Constraint\AbstractConstraint;

/**
 * Assert widget availability in widget grid.
 */
class AssertWidgetInGrid extends AbstractConstraint
{
    /* tags */
    const SEVERITY = 'high';
    /* end tags */

    /**
     * Assert widget availability in widget grid.
     *
     * @param Widget $widget
     * @param WidgetInstanceIndex $widgetInstanceIndex
     * @return void
     */
    public function processAssert(Widget $widget, WidgetInstanceIndex $widgetInstanceIndex)
    {
        $widgetInstanceIndex->open();
        $widgetTitle = $widget->getTitle();
        \PHPUnit_Framework_Assert::assertTrue(
            $widgetInstanceIndex->getWidgetGrid()->isRowVisible(['title' => $widgetTitle]),
            "Widget with title $widgetTitle is absent in Widget grid."
        );
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return 'Widget is present in widget grid.';
    }
}
