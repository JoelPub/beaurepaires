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
 * @package     Mage_Core
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */


/**
 * Design package collection
 *
 * @category    Mage
 * @package     Mage_Core
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Core_Model_Resource_Design_Package_Collection extends Varien_Object
{
    /**
     * Load design package collection
     *
     * @return Mage_Core_Model_Resource_Design_Package_Collection
     */
    public function load()
    {
        $packages = $this->getData('packages');
        if (is_null($packages)) {
            $packages = Mage::getModel('core/design_package')->getPackageList();
            $this->setData('packages', $packages);
        }

        return $this;
    }

    /**
     * Convert to option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();
        $packages = $this->getData('packages');
        foreach ($packages as $package) {
            $options[] = array('value' => $package, 'label' => $package);
        }
        array_unshift($options, array('value' => '', 'label' => ''));

        return $options;
    }
}
