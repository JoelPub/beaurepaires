<?php
class ApdInteract_Pdf_Block_pdf extends Mage_Core_Block_Template{

    /**
     * @var int
     */
    protected $_invoiceNumber = 0;

    /**
     * @var string
     */
    protected $_title = '';

    /**
     * @var
     */
    protected $_invoice;

    /**
     * @var
     */
    protected $_order;


    protected function _construct()
    {
        $this->_loadCollection();
    }


    protected function _loadCollection()
    {
        if(!Mage::registry('current_invoice')){
            return;
        }

        $invoice = Mage::registry('current_invoice');
        if($invoice->getId()){

            $invoiceDate = date('Ymd',strtotime($invoice->getCreatedAt()));
            $this->_title = "beaurepaires-{$invoice->getIncrementId()}-{$invoiceDate}.pdf";

            //invoice order
            $this->_order = $invoice->getOrder();
            $this->_invoice = $invoice;

        }

        return $this;
    }

    public function getInvoice()
    {
        return $this->_invoice;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Display payment type
     * @param $order
     * @return string
     */
    public function displayPayment($order){

        $payment = "";

        if(!empty($order->getPayment()->getCcType())){

            $ccInfo = $order->getPayment()->getAdditionalInformation();
            $payment =  "{$ccInfo['card_type']} **** **** {$order->getPayment()->getCcLast4()}";

        }else{
            $payment = $order->getPayment()->getMethodInstance()->getCode();
        }


        return $payment;
    }  

    /**
     * @param $blockId
     * @return mixed|string
     */
    public function getStaticBlock($blockId)
    {
        $blockIdentifier =  Mage::getStoreConfig('apdinteract_invoice_pdf/staticblock_addons/' . $blockId);
        $html = "";
        $block = Mage::getModel('cms/block')->getCollection()
            ->addFieldToFilter('identifier', array('eq' => $blockIdentifier))
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter(
                array('customer_group_id', 'customer_group_id'), array(array('eq' => 0), array('eq' => $this->_getCustomerGroupId()),)
            )->getFirstItem();

        if(count($block)){
            $html = $this->getLayout()->createBlock('cms/block')->setBlockId($block->getIdentifier())->toHtml();

            $baseMediaUrl = 'src="'. Mage::getBaseUrl();
            $dirMediaUrl = 'src="'. Mage::getBaseDir() . DS;

            $html = str_replace($baseMediaUrl,$dirMediaUrl,$html);
        }

        return $html;
    }


    /**
     * Get customer group ID
     * @return int
     */
    private function _getCustomerGroupId() {

        $customer = Mage::getModel("customer/customer")
                    ->setWebsiteId(Mage::app()->getWebsite()->getId())
                    ->loadByEmail($this->_order->getCustomerEmail());

        return (int)$customer->getGroupId();
    }


    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function _invoiceModel()
    {
        return  Mage::getModel('sales/order_invoice');
    }



}