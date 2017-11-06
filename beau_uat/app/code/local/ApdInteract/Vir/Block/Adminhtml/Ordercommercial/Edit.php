<?php
class ApdInteract_Vir_Block_Adminhtml_Ordercommercial_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected function _construct()
    {
        $this->setTemplate('apdinteract/vir/ordercommercial/edit/ordercommercial.phtml');
        $this->_blockGroup = 'apdinteract_vir_adminhtml';
        $this->_controller = 'ordercommercial';
        $this->_template = 'apdinteract/vir/ordercommercial/edit/ordercommercial.phtml';
        $this->_data["template"] = 'apdinteract/vir/ordercommercial/edit/ordercommercial.phtml';
        

        /**
         * The $_mode property tells Magento which folder to use
         * to locate the related form blocks to be displayed in
         * this form container. In our example, this corresponds
         * to Vir/Block/Adminhtml/Ordercommercial/Edit/.
         */
        $this->_mode = 'edit';

        $newOrEdit = $this->getRequest()->getParam('id')
            ? $this->__('Edit')
            : $this->__('New');
        //$this->_headerText =  $newOrEdit . ' ' . $this->__('Ordercommercial');
        $this->_headerText =  'Commercial - Vehicle Inspection Report (VIR In-Store)';
    }
}

