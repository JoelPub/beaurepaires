<?php
class ApdInteract_Costar_Block_Adminhtml_Form_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

    public function __construct() {


        $this->_blockGroup = 'costar';
        $this->_controller = 'adminhtml_costar';
        $this->_headerText = Mage::helper('costar/costar')->__('Import Data');

        parent::__construct();
    }

}
