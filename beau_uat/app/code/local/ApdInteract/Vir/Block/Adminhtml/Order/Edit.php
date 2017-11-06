<?php
class ApdInteract_Vir_Block_Adminhtml_Order_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected function _construct()
    {
        //parent::__construct();
        $this->setTemplate('apdinteract/vir/order/edit/order.phtml');
        //$this->_headerText = Mage::helper('apdinteract_vir')->__('Vehicle Inspection Report');
        $this->_blockGroup = 'apdinteract_vir_adminhtml';
        $this->_controller = 'order';
        $this->_template = 'apdinteract/vir/order/edit/order.phtml';
        $this->_data["template"] = 'apdinteract/vir/order/edit/order.phtml';
        //$this->setScriptPath('/static');
        
        //$this->addJs('http://bft-tyres.vr1.net.au/js/adpinteract/vir.js');
        
        //$headBlock = $this->getLayout()->createBlock('core/text', 'parent-id');
        //$headBlock->addJs('prototype/prototype.js');
        //$headBlock->addJs('mage/adminhtml/loader.js');
        
        //$block = $this->getLayout()
        //->createBlock('core/text', 'parent-id')
        //->setText('<script type="text/javascript" src="/js/jquery-2.1.4.min.js"></script>'
        //        .'<script type="text/javascript" src="/js/adpinteract/vir.js"></script>'
        //        .'<link rel="stylesheet" type="text/css" href="/skin/adminhtml/default/default/css/apdinteract/vir.css" media="screen, projection">');

        //$this->_addContent($block);

        //echo $headBlock->getCssJsHtml();

        /**
         * The $_mode property tells Magento which folder to use
         * to locate the related form blocks to be displayed in
         * this form container. In our example, this corresponds
         * to Vir/Block/Adminhtml/Order/Edit/.
         */
        $this->_mode = 'edit';

        //$newOrEdit = $this->getRequest()->getParam('id')
        //    ? $this->__('Edit')
        //    : $this->__('New');
        //$this->_headerText =  $newOrEdit . ' ' . $this->__('Order');
        $this->_headerText =  'Consumer - Vehicle Inspection Report (VIR)';
    }
    
    protected function _prepareLayout() 
    {
        //$this->_removeButton('add');        
        return parent::_prepareLayout();
    }
}

