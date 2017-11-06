<?php
class ApdInteract_Pricefilter_Model_Cron {

    /**
     * Remove null price from configurable product.
     */
    public function UpdateConfigurablePrice() {

        $productCollection = $this->_productCollection('configurable')
                ->addAttributeToSelect(array('entity_id'))
                ->addAttributeToSort('updated_at', 'ASC');

        foreach ($productCollection as $prod) {
//echo $prod->getName()."\n";

            $childProducts = $this->_getUsedProducts($prod);
            if ($childProducts) {

                $price_array = array();
                foreach ($childProducts as $child) {

                    $_child = Mage::getModel('catalog/product')->load($child->getId());
#echo $_child->getName().'='.$_child->getPrice().'='.$_child->getFinalPrice()."x\n";
                    if ($_child->getFinalPrice() > 0) {
                        $price_array[] = $_child->getFinalPrice();
                    }
                }
            }

            try {

                if (count($price_array) > 0) {
//echo $_child->getName().'='.$_child->getPrice().'='.$_child->getFinalPrice()."x\n";

                    $product = Mage::getModel('catalog/product')->load($prod->getId());
                    $pr = min($price_array);
                    echo $product->getName() . '=' . $product->getId() . '=' . $pr . "\n";
                    $product->setPrice($pr); //set The lowest price
                    $product->save();
                }
            } catch (Exception $e) {
                Mage::log('Error On Update -  ID:' . $prod->getId(), null, 'update_configurable_price.log');
            }
        }
    }

    /**
     * @param $prod
     * @return mixed
     */
    private function _getUsedProducts($prod) {
        $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $prod);

        return $childProducts;
    }

    /**
     * @param $type
     * @return mixed
     */
    private function _productCollection($type) {

        $category = Mage::getModel('catalog/category')->load(41); //load tyres
        $collection = Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToFilter('type_id', array('eq' => $type));
        //->addCategoryFilter($category);
        //->addAttributeToFilter('price', array('null' => TRUE));

        return $collection;
    }

}
