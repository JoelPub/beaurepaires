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
 * @category    Mage
 * @package     Mage_Paypal
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Source model for available settlement report fetching intervals
 */
class Mage_Paypal_Model_System_Config_Source_FetchingSchedule
{
    public function toOptionArray()
    {
        return array (
            1 => Mage::helper('paypal')->__("Daily"),
            3 => Mage::helper('paypal')->__("Every 3 days"),
            7 => Mage::helper('paypal')->__("Every 7 days"),
            10 => Mage::helper('paypal')->__("Every 10 days"),
            14 => Mage::helper('paypal')->__("Every 14 days"),
            30 => Mage::helper('paypal')->__("Every 30 days"),
            40 => Mage::helper('paypal')->__("Every 40 days"),
        );
    }
}
