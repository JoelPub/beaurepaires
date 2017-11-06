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
 * Customer Vehicle forms
 *
 * @category   Mage
 * @package    Apdinteract_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class ApdInteract_Adminhtml_Block_Customer_Edit_Tab_Vehicle extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('customer/tab/vehicle.phtml');
    }

    public function getCustomer()
    {
        if (!$this->_customer) {
            $this->_customer = Mage::registry('current_customer');
        }
        return $this->_customer;
    }


    protected function _prepareLayout()
    {
        $this->setChild('grid',
            $this->getLayout()->createBlock('apdinteract_adminhtml/customer_edit_tab_vehicle_grid','vehicle.grid')
        );
        return parent::_prepareLayout();
    }

    /**
     * Check block is readonly.
     *
     * @return boolean
     */
    public function isReadonly()
    {
        $customer = Mage::registry('current_customer');
        return $customer->isReadonly();
    }

    /**
     * Initialize form object
     *
     * @return Mage_Adminhtml_Block_Customer_Edit_Tab_Addresses
     */
    public function initForm()
    {
        /* @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::registry('current_customer');

        $form = new Varien_Data_Form();
        $fieldset = $form->addFieldset('address_fieldset', array(
                'legend'    => Mage::helper('customer')->__("Edit Customer's Address"))
        );

        $addressModel = Mage::getModel('customer/address');
        $addressModel->setCountryId(Mage::helper('core')->getDefaultCountry($customer->getStore()));
        /** @var $addressForm Mage_Customer_Model_Form */
        $addressForm = Mage::getModel('customer/form');
        $addressForm->setFormCode('adminhtml_customer_address')
            ->setEntity($addressModel)
            ->initDefaultValues();

        $attributes = $addressForm->getAttributes();
        if(isset($attributes['street'])) {
            Mage::helper('adminhtml/addresses')
                ->processStreetAttribute($attributes['street']);
        }
        foreach ($attributes as $attribute) {
            /* @var $attribute Mage_Eav_Model_Entity_Attribute */
            $attribute->setFrontendLabel(Mage::helper('customer')->__($attribute->getFrontend()->getLabel()));
            $attribute->unsIsVisible();
        }
        $this->_setFieldset($attributes, $fieldset);

        $regionElement = $form->getElement('region');
        if ($regionElement) {
            $isRequired = Mage::helper('directory')->isRegionRequired($addressModel->getCountryId());
            $regionElement->setRequired($isRequired);
            $regionElement->setRenderer(Mage::getModel('adminhtml/customer_renderer_region'));
        }

        $regionElement = $form->getElement('region_id');
        if ($regionElement) {
            $regionElement->setNoDisplay(true);
        }

        $country = $form->getElement('country_id');
        if ($country) {
            $country->addClass('countries');
        }

        if ($this->isReadonly()) {
            foreach ($addressModel->getAttributes() as $attribute) {
                $element = $form->getElement($attribute->getAttributeCode());
                if ($element) {
                    $element->setReadonly(true, true);
                }
            }
        }

        $customerStoreId = null;
        if ($customer->getId()) {
            $customerStoreId = Mage::app()->getWebsite($customer->getWebsiteId())->getDefaultStore()->getId();
        }

        $prefixElement = $form->getElement('prefix');
        if ($prefixElement) {
            $prefixOptions = $this->helper('customer')->getNamePrefixOptions($customerStoreId);
            if (!empty($prefixOptions)) {
                $fieldset->removeField($prefixElement->getId());
                $prefixField = $fieldset->addField($prefixElement->getId(),
                    'select',
                    $prefixElement->getData(),
                    '^'
                );
                $prefixField->setValues($prefixOptions);
            }
        }

        $suffixElement = $form->getElement('suffix');
        if ($suffixElement) {
            $suffixOptions = $this->helper('customer')->getNameSuffixOptions($customerStoreId);
            if (!empty($suffixOptions)) {
                $fieldset->removeField($suffixElement->getId());
                $suffixField = $fieldset->addField($suffixElement->getId(),
                    'select',
                    $suffixElement->getData(),
                    $form->getElement('lastname')->getId()
                );
                $suffixField->setValues($suffixOptions);
            }
        }

        $addressCollection = $customer->getAddresses();
        $this->assign('customer', $customer);
        $this->assign('addressCollection', $addressCollection);
        $form->setValues($addressModel->getData());
        $this->setForm($form);

        return $this;
    }

}
