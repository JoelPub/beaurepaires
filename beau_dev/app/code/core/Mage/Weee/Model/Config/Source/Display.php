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
 * @package     Mage_Weee
 * @copyright Copyright (c) 2006-2017 X.commerce, Inc. and affiliates (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */
class Mage_Weee_Model_Config_Source_Display
{

    public function toOptionArray()
    {
        /**
         * VAT is not applicable to FPT separately (we can't have FPT incl/excl VAT)
         */
        return array(
            array(
                'value' => 0,
                'label' => Mage::helper('weee')->__('Including FPT only')
            ),
            array(
                'value' => 1,
                'label' => Mage::helper('weee')->__('Including FPT and FPT description')
            ),
            //array('value'=>4, 'label'=>Mage::helper('weee')->__('Including FPT and FPT description [incl. FPT VAT]')),
            array(
                'value' => 2,
                'label' => Mage::helper('weee')->__('Excluding FPT, FPT description, final price')
            ),
            array(
                'value' => 3,
                'label' => Mage::helper('weee')->__('Excluding FPT')
            ),
        );
    }

}
