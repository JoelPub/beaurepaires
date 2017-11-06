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
 * @package     Mage_Adminhtml
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * Customer Vehicle Information Tab
 *
 * @category   Mage
 * @package    Apdinteract_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class ApdInteract_Adminhtml_Block_Customer_Edit_Tabs extends Mage_Adminhtml_Block_Customer_Edit_Tabs
{

    protected function _beforeToHtml()
    {
        $this->addTabAfter('vehicle', array(
            'label'     => Mage::helper('customer')->__('Vehicle Information'),
            'content'   => $this->getLayout()->createBlock('apdinteract_adminhtml/customer_edit_tab_vehicle')->initForm()->toHtml(),
        ), 'account');

        $this->_updateActiveTab();
        Varien_Profiler::stop('customer/tabs');
        return parent::_beforeToHtml();
    }

    /*
     * Update active tab when vehicles will be modify.
     */

    protected function _updateActiveTab()
    {
        $update = $this->getRequest()->getParam('update_vehicle');
        if($update){
            $this->setActiveTab('vehicle');
        }

    }

}
