<?php
class ApdInteract_Storecategory_Block_Adminhtml_Storecategory_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId("storecategory_tabs");
        $this->setDestElementId("edit_form");
        $this->setTitle(Mage::helper("storecategory")->__("Item Information"));
    }

    protected function _beforeToHtml() {
        $this->addTab("form_section", array(
            "label" => Mage::helper("storecategory")->__("Item Information"),
            "title" => Mage::helper("storecategory")->__("Item Information"),
            "content" => $this->getLayout()->createBlock("storecategory/adminhtml_storecategory_edit_tab_form")->toHtml(),
        ));
        return parent::_beforeToHtml();
    }

}
