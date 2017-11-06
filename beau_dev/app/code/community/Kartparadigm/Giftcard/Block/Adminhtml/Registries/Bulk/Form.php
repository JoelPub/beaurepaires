<?php

class Kartparadigm_Giftcard_Block_Adminhtml_Registries_Bulk_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/import', array()),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $form->setUseContainer(true);
        $this->setForm($form);
        if (Mage::getSingleton('adminhtml/session')->getFormData()) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData();
            Mage::getSingleton('adminhtml/session')->setFormData(null);
        }


        $fieldset = $form->addFieldset('bulk_form', array('legend' => Mage::helper('kartparadigm_giftcard')->__('Upload CSV')));

        $fieldset->addField('file', 'file', array(
            'label' => Mage::helper('kartparadigm_giftcard')->__('CSV'),
            'class' => 'disable',
            'required' => true,
            'name' => 'file',
            'after_element_html' =>"Please download sample csv <a href = '/skin/adminhtml/default/default/csv/gc-sample.csv'>here</a>"
        ));

       /* $customerGroupModel = new Mage_Customer_Model_Group();
        $customerGroups = array();
        $allCustomerGroups = $customerGroupModel->getCollection()->addFieldToFilter('customer_group_id', array(
                    'nin' => array(
                        0
                    )
                ))->toOptionHash();
        
       
        
        $result = array_merge(array('All Customer Group'),$allCustomerGroups);                 
        
        $fieldset->addField('customer_groups', 'select', array(
            'name' => 'customer_groups',
            'label' => Mage::helper('kartparadigm_giftcard')->__('Customer Group'),
            'class' => 'required-entry',
            'required' => true,
            'disabled' => false,
            'options' => $result,
        ));*/


        return parent::_prepareForm();
    }

}
