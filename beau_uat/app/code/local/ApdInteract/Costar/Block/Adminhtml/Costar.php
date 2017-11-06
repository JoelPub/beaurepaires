<?php
class ApdInteract_Costar_Block_Adminhtml_Costar extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**    
    * Added Import button for upload form
    *   
    */
    public function __construct()
    {
        $this->_blockGroup = 'costar';
        $this->_controller = 'adminhtml_costar';
        $this->_headerText = Mage::helper('costar/costar')->__('Costar Import History');
        
        parent::__construct();
        
        $this->_removeButton('add');
        
        $this->_addButton('adminhtml_costar', array(
        'label' => $this->__('Import Data'),
        'onclick' => "setLocation('{$this->getUrl('*/adminhtml_costarbackend/new')}')",
    ));
    }
}