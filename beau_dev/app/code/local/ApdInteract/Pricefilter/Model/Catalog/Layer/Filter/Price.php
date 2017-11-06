<?php

class ApdInteract_Pricefilter_Model_Catalog_Layer_Filter_Price extends Mage_Catalog_Model_Layer_Filter_Price
{

    /**
     * @param float|string $fromPrice
     * @param float|string $toPrice
     * @return string
     */
    protected function _renderRangeLabel($fromPrice, $toPrice)
    {
        $store      = Mage::app()->getStore();
        $formattedFromPrice  = $store->formatPrice($fromPrice);

        if ($toPrice === '') {
            return Mage::helper('catalog')->__('Above %s', $this->_removeDecemal($formattedFromPrice));
        } elseif ($fromPrice == $toPrice && Mage::app()->getStore()->getConfig(self::XML_PATH_ONE_PRICE_INTERVAL)) {
            return $formattedFromPrice;
        }elseif($fromPrice == ''){

            return Mage::helper('catalog')->__('Under %s', $this->_removeDecemal($store->formatPrice($toPrice)));

        } else {
            if ($fromPrice != $toPrice) {
                $toPrice -= .01;
            }
            return Mage::helper('catalog')->__('%s to %s', $this->_removeDecemal($formattedFromPrice), $this->_removeDecemal($store->formatPrice($toPrice)));
        }
    }

    /**
     * @param $price
     * @return mixed
     *
     */
    protected function  _removeDecemal($price){

        $noise = array('.99','.00');
        return str_replace($noise,'',$price);
    }
}
