<?php
class ApdInteract_Exportpanel_Adminhtml_ExportpanelbackendController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Export Panel"));
        $this->_addLeft($this->getLayout()->createBlock('exportpanel/adminhtml_form_edit_tabs'));
	   $this->renderLayout();
    }

    /**
     * Download action
     * @param string
     */
    public function downloadAction()
    {
        if (Mage::app()->getRequest()->getParam('file'))
        {
            $file = Mage::app()->getRequest()->getParam('file');
            $type = Mage::app()->getRequest()->getParam('type');

            header('Content-disposition: attachment; filename='.$file);
            header('Content-type: text/csv');
            header('Content-type: application/ms-excel');
            readfile(Mage::getBaseDir('var') . DS .  'export' . DS . $type . DS . $file);
            exit;

        } else {

            Mage::getSingleton('core/session')->addError(Mage::helper('exportpanel')->__('Unable to find download file'));
            $this->_redirect('*/*/');

        }
    }

}