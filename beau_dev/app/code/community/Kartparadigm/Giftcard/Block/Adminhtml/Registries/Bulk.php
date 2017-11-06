<?php
class Kartparadigm_Giftcard_Block_Adminhtml_Registries_Bulk extends Mage_Adminhtml_Block_Widget_Form_Container
{
public function __construct(){
parent::__construct();

$this->_controller = 'adminhtml_registries';
$this->_blockGroup = 'kartparadigm_giftcard';

$this->_mode = 'bulk';
$this->_updateButton('save', 'label', Mage::helper('kartparadigm_giftcard')->__('Start Import'));

 
}
    public function getHeaderText()
    {        
        return Mage::helper('kartparadigm_giftcard')->__('Import Giftcard');
    }
}

