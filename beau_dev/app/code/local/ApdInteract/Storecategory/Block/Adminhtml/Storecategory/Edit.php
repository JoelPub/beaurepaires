<?php

class ApdInteract_Storecategory_Block_Adminhtml_Storecategory_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {

        parent::__construct();
        $this->_objectId = "id";
        $this->_blockGroup = "storecategory";
        $this->_controller = "adminhtml_storecategory";
        $this->_updateButton("save", "label", Mage::helper("storecategory")->__("Save Item"));
        $this->_updateButton("delete", "label", Mage::helper("storecategory")->__("Delete Item"));

        $this->_addButton("saveandcontinue", array(
            "label" => Mage::helper("storecategory")->__("Save And Continue Edit"),
            "onclick" => "saveAndContinueEdit()",
            "class" => "save",
                ), -100);



        $this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
    }

    public function getHeaderText() {
        if (Mage::registry("storecategory_data") && Mage::registry("storecategory_data")->getId()) {

            return Mage::helper("storecategory")->__("Edit '%s'", $this->htmlEscape(Mage::registry("storecategory_data")->getName()));
        } else {

            return Mage::helper("storecategory")->__("Add Category");
        }
    }

}
