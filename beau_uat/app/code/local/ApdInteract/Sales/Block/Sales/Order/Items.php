<?php

/**
 * Class ApdInteract_Sales_Block_Sales_Order_Items
 */
class ApdInteract_Sales_Block_Sales_Order_Items extends Mage_Sales_Block_Order_Items
{
    /**
     * @var
     */
    protected  $_store;

    /**
     * @var
     */
    protected $_fittingDetails;

    /**
     * Store details
     * @return mixed
     */
    public function getStoreLocation()
    {

        $_order = $this->getOrder();
        if($this->getOrder()){

            $storeId = $_order->getStorelocation();
            if($storeId){
                $store = Mage::getModel('storelocator/stores')->load($storeId);
                $region = Mage::getModel('directory/region')->load($store->getRegionId());

                $this->_store->_details =  $store;
                $this->_store->_region = $region;
            }

        }

        return $this->_store;
    }

    /**
     * VIR information
     * @return mixed
     */
    public function getFittingDetails()
    {
        $this->_fittingDetails->label = "";
        $this->_fittingDetails->data = "";

        if($this->getOrder()){
            $consumerVir = Mage::helper('apdinteract_vir')->getConsumerDetail($this->getOrder()->getRealOrderId());
            if(count($consumerVir)){
                $this->_fittingDetails->label = 'consumer';
                $this->_fittingDetails->data = $consumerVir->getFirstItem();
            }else{
                $commercialVir = Mage::helper('apdinteract_vir')->getCommercialDetail($this->getOrder()->getRealOrderId());
                if(count($commercialVir)){
                    $this->_fittingDetails->label = 'commercial';
                    $this->_fittingDetails->data = $commercialVir->getFirstItem();
                }
            }
        }

        return $this->_fittingDetails;
    }
    
}
			