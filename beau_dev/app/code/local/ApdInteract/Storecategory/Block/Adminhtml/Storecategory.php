<?php
class ApdInteract_Storecategory_Block_Adminhtml_Storecategory extends Mage_Adminhtml_Block_Widget_Grid_Container {

    public function __construct() {

        $this->_controller = "adminhtml_storecategory";
        $this->_blockGroup = "storecategory";
        $this->_headerText = Mage::helper("storecategory")->__("Store Category Manager");
        $this->_addButtonLabel = Mage::helper("storecategory")->__("Add New Category");
        parent::__construct();
    }

}
