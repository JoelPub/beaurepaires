<?php
/**
 * Override sales order controller
 */
require 'Mage/Sales/controllers/OrderController.php';
class ApdInteract_Sales_OrderController extends Mage_Sales_OrderController
{
    protected  $_attachment = false;

    /**
     *  Override Print Invoice Action
     */
    public function printInvoiceAction()
    {	
	 $this->getResponse()->clearHeaders()->setHeader(
            'Content-type',
            'application/pdf'
        ); 
		
        $this->_convertPdf();
    }

    /**
     *  Download Invoice Action
     */
    public function DownloadInvoiceAction()
    {
        $this->_attachment = true;
        $this->_convertPdf();
    }

    /**
     * html to pdf
     */
    protected function _convertPdf()
    {
        $invoiceId = (int) $this->getRequest()->getParam('invoice_id');
        if ($invoiceId) {
            $invoice = Mage::getModel('sales/order_invoice')->load($invoiceId);
            $order = $invoice->getOrder();
        } else {
            $orderId = (int) $this->getRequest()->getParam('order_id');
            $order = Mage::getModel('sales/order')->load($orderId);
        }

        if ($this->_canViewOrder($order)) {
            Mage::register('current_order', $order);
            if (isset($invoice)) {
                Mage::register('current_invoice', $invoice);
            }

            $block = $this->getLayout()->createBlock('pdf/pdf');
            $block->setTemplate('pdf/pdf_tax_invoice.phtml');

            Mage::getModel('apdinteract_pdf/pdf')
                ->setTitle($block->getTitle())
                ->sendToPdf($block->toHtml(),array("Attachment" => $this->_attachment));

        } else {
            if (Mage::getSingleton('customer/session')->isLoggedIn()) {
                $this->_redirect('*/*/history');
            } else {
                $this->_redirect('sales/guest/form');
            }
        }
    }

}
