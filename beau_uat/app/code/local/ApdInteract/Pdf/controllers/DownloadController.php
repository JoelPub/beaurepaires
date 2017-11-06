<?php
class ApdInteract_Pdf_DownloadController extends Mage_Core_Controller_Front_Action{


    public function TaxInvoiceAction() {

       /* $block = $this->getLayout()->createBlock('pdf/pdf');
        $block->setTemplate('pdf/pdf_tax_invoice.phtml');

        $this->_domPdf()->setTitle($block->getTitle())->sendToPdf($block->toHtml(),array("Attachment" => false));
       */
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function _domPdf(){ 
        return Mage::getModel('apdinteract_pdf/pdf');
    }

}