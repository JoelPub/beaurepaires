<?php

class ApdInteract_SalesRule_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getBundledPricesRules() { //Get all Bundled Price Rules
        $rules = Mage::getModel('salesrule/rule')->getCollection();
        $rules->addFieldToFilter('simple_action', 'bundled_price');
        $rules->addFieldToFilter('is_active', '1');
        $array = array();
        foreach ($rules as $rule) {
            $step = $rule->getDiscountStep();
            $bundle = $rule->getDiscountAmount();
            $conditions = $rule->getConditionsSerialized();
            $unserialized_conditions = unserialize($conditions);
            $unserialized_conditions_compact = array();
            foreach ($unserialized_conditions as $key => $value) {
                $unserialized_conditions_compact[] = compact('key', 'value');
            }
            $new = array();
            for ($i = 0; $i < count($unserialized_conditions_compact); $i++) {
                if (in_array("conditions", $unserialized_conditions_compact[$i])) {
                    foreach ($unserialized_conditions_compact[$i] as $key => $value) {
                        if (is_array($value)) {
                            $new = $this->_getBundleRuleDetails($value, $rule->getId(), $bundle, $step);
                        }
                        $array[$rule->getId()] = $new;
                    }
                }
            }
        }
        return $array;
    }

    private function _getBundleRuleDetails($value, $rule_id, $bundle, $step) {

        foreach ($value as $key1 => $value1) {
            $qty = $value1['value'];
            $condition_array = $value1['conditions'];
            $operator = $value1['operator'];
            $attribute = $condition_array[0]['attribute'];
            $values_array = $condition_array[0]['value'];
            if ($qty > 0) {
                $new[] = array(
                    "rule_id" => $rule_id,
                    "qty" => $qty,
                    "attribute" => $attribute,
                    "values" => $values_array,
                    'steps' => $step,
                    "bundle" => $bundle
                );
            }
        }

        return $new;
    }

    public function updateCartPrices($bundle_array) { //update qty and prices of qualified quote items

        $items_updated = 0;
        $quotes_to_ignore = Mage::getSingleton('core/session')->getIgnoreQuote(); // Array of quote items that has rules applied
        $items = Mage::getModel('checkout/cart')->getQuote();
        foreach ($items->getAllItems() as $item) {
            foreach ($bundle_array as $bundle) {
                $values = $bundle['value'];
                foreach ($values as $value) {
                    if ($value['quote_id'] == $item->getId()) {
                        $quotes_to_ignore[$value['quote_id']] = $value['quote_id'];
                        $bundle_price = $bundle['bundle'];
                        $orig_price = $bundle['orig_final'];
                        $discount = $bundle_price / ($orig_price);
                        if ($value['update'] == 1)
                            $final = round(($value['price'] * $discount), 2);
                        else
                            $final = round($value['price'], 2);
                        if ($item->getQty() != $value['qty'] || $final != $item->getPriceInclTax()) {
                            $item->setQty($value['qty']);
                            $item->setOriginalCustomPrice($final);
                            $item->setDiscountAmount(0);
                            $item->setBaseDiscountAmount(0);
                            $item->setDiscountPercent(0);
                            $item->save();
                            $items_updated++;
                        }
                    }
                }
            }
        }
        Mage::getSingleton('core/session')->setIgnoreQuote($quotes_to_ignore);
        Mage::getSingleton('core/session')->getMessages(true);
        return $items_updated;
    }

    public function addToCart($param) { //add qualified quote items
        Mage::getSingleton('core/session')->setToadd(1);
        $counter = 0;
        foreach ($param as $pr) {
            $params = $pr['params'];
            $size_id = $pr['size'];
            $sku = $pr['sku'];
            $quote_id = $pr['quote_id'];
            $product_id = $params['product'];

            $product = Mage::getModel('catalog/product')->load($product_id);



            if (!is_null($size_id)) {

                $count = $this->_getItemByProduct($product->getId(), $sku, 0, $quote_id);
            } else {

                $count = $this->_getItemByProduct($product->getId(), $sku, 1, $quote_id);
            }



            if ($count <= 1) {
                $counter++;
                if ($product->getData('type_id') == "configurable") {
                    // Tyres and wheels will have different "super" attributes
                    // ("super" attribute = the attribute used to select the child product from the configurable parent)
                    $config_attributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
                    // We assume product has only 1 configurable attribute
                    $attribute_id = $config_attributes[0]['attribute_id'];
                    $params['super_attribute'] = array(
                        $attribute_id => $size_id
                    );
                }
                $cart = Mage::getModel('checkout/cart');
                $cart->init();
                $cart->addProduct($product, $params);
                $cart->save();
                Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
            }
        }
        Mage::getSingleton('core/session')->setToadd('');
        Mage::getSingleton('core/session')->getMessages(true);
        return $counter;
    }

    public function backToOrigPrice($orig_data) {
        $items = Mage::getModel('checkout/cart')->getQuote();
        foreach ($items->getAllItems() as $item) {
            foreach ($orig_data as $bundle) {
                $values = $bundle['value'];
                foreach ($values as $value) {
                    if ($value['quote_id'] == $item->getId()) {
                        $item->setOriginalCustomPrice($value['price']);
                        $item->setDiscountAmount(0);
                        $item->setBaseDiscountAmount(0);
                        $item->setDiscountPercent(0);
                        $item->save();
                    }
                }
            }
        }
    }

    private function _getItemByProduct($id, $sku, $type, $quote_id) {
        $match_counter = 0;
        $cart = Mage::getSingleton('checkout/cart')->getQuote();
        foreach ($cart->getAllItems() as $item) {
            if ($item->getProductId() == $id && $item->getSku() == $sku && $type == 1) {
                $match_counter++;
            } else if ($type == 0 && $item->getParentItemId() == $quote_id && $sku == $item->getSku()) {
                $match_counter++;
            }
        }
        return $match_counter;
    }

    public function compileSkus($sku, $price) {
        $skus = Mage::getSingleton('core/session')->getSkuOrig();
        if (!is_array($skus))
            $skus = array();

        if (!isset($skus[$sku])) {
            $skus[$sku] = $price;
            Mage::getSingleton('core/session')->setSkuOrig($skus);
        }
    }

}
