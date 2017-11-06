<?php
class ApdInteract_Pdf_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Format Price
     * @param $price
     * @return mixed
     */
    public function formatPrice($price)
    {
        return Mage::helper('core')->currency($price, true, false);
    }

    /**
     * @return string
     */
    public function getLogoSrc()
    {
        return  Mage::getBaseDir() . '/media/catalog/product/watermark/' . Mage::getStoreConfig('apdinteract_invoice_pdf/options/logo');
    }

    /**
     * @return string
     */
    public function getPhoneSrc()
    {
        return  Mage::getBaseDir() . '/skin/frontend/polar/default/images/telephone.jpg';
    }

    /**
     * @return mixed
     */
    public function getStoreTitle()
    {
        return  Mage::getStoreConfig('apdinteract_invoice_pdf/options/title');
    }

    /**
     * @return mixed
     */
    public function getStoreAbn()
    {
        return  Mage::getStoreConfig('apdinteract_invoice_pdf/options/abn');
    }

    /**
     * @return mixed
     */
    public function getStorePhone()
    {
        return  Mage::getStoreConfig('apdinteract_invoice_pdf/options/phone');
    }

    /**
     *
     * @param $order
     * @return string
     */
    public function checkOrderStatus($order){

        $status = "";
        $consumer = Mage::helper('apdinteract_vir')->getConsumerDetail($order->getRealOrderId());
        if((bool)count($consumer)){
            $status = $consumer->getFirstItem()->getData('status');
        }else{
            $commercial = Mage::helper('apdinteract_vir')->getCommercialDetail($order->getRealOrderId());
            $status =  $commercial->getFirstItem()->getData('status');
        }

        return strtolower($status);
    }


    /**
     * Calculate GST
     *
     * @param $taxPercent
     * @return float
     */
    public function getTaxPercentage($taxPercent)
    {
        $gst = $taxPercent / 100 + 1;

        return $gst;
    }
}
	 