<?php
class ApdInteract_Wheels_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getChildProducts($product){

        $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts( null, $product);
        $childProduct = array();
        foreach ( $childProducts as $child ) {
            $_child = Mage::getModel('catalog/product')->load( $child->getId() );
            if ($_child->getFinalPrice() > 0) {

                $sizeArray = explode("|",$_child->getAttributeText('rim_diameter_configurable'));
                $sizeIndex = trim($sizeArray[0]);
                $childProduct[$sizeIndex][] = array(
                    'Id' =>$_child->getRimDiameterConfigurable(),
                    'Sku' => $_child->getSku(),
                    'Title'  => $_child->getAttributeText('rim_diameter_configurable'),
                    'OnlinePrice'  => $_child->getFinalPrice(),
                    'RRP' => $_child->getPrice(),
                    'SpecialPrice' => $_child->getSpecialPrice(),
                    'IsLowest' => 0
                );

            }
        }

        return $childProduct;
    }
}
