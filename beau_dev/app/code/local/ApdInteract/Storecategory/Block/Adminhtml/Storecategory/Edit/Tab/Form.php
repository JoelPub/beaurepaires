<?php
class ApdInteract_Storecategory_Block_Adminhtml_Storecategory_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form {

    protected function _prepareForm() {

        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset("storecategory_form", array("legend" => Mage::helper("storecategory")->__("Category information")));


        $fieldset->addField("name", "text", array(
            "label" => Mage::helper("storecategory")->__("Name"),
            "name" => "name",
        ));

        $fieldset->addField("code", "text", array(
            "label" => Mage::helper("storecategory")->__("Code"),
            "name" => "code",
        ));

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('storecategory')->__('Image'),
            'name' => 'image',
            'note' => '60px x 58px (*.jpg, *.png, *.gif)',
        ));
        $fieldset->addField('icon', 'image', array(
            'label' => Mage::helper('storecategory')->__('Icon'),
            'name' => 'icon',
            'note' => '28px x 27px(*.jpg, *.png, *.gif)',
        ));

        if (Mage::getSingleton("adminhtml/session")->getStorecategoryData()) {
            $form->setValues(Mage::getSingleton("adminhtml/session")->getStorecategoryData());
            Mage::getSingleton("adminhtml/session")->setStorecategoryData(null);
        } elseif (Mage::registry("storecategory_data")) {
            $form->setValues(Mage::registry("storecategory_data")->getData());
        }
        return parent::_prepareForm();
    }

}
