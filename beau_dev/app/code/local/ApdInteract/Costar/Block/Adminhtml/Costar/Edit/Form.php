<?php

class ApdInteract_Costar_Block_Adminhtml_Costar_Edit_Form extends Mage_Adminhtml_Block_Widget_Form {

    /**    
    * Form for stock inventory file upload
    *   
    */
    protected function _prepareForm() {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
                )
        );

        $helper = Mage::helper('costar/costar');
        $fieldset = $form->addFieldset('display', array(
            'legend' => $helper->__('Import Data'),
            'class' => 'fieldset-wide'
        ));

        $fieldset->addField('select', 'select', array(
            'name' => 'type',
            'label' => $helper->__('Type'),
            'required' => true,
            'values' => Mage::getModel('apdinteract_costar/log')->toOptionArray()
        ));

        $fieldset->addField('file', 'file', array(
            'label' => Mage::helper('costar/costar')->__('CSV'),
            'class' => 'disable',
            'required' => true,
            'name' => 'file',
        ));

        if (Mage::registry('apdinteract_costar')) {
            $form->setValues(Mage::registry('apdinteract_costar')->getData());
        }

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
