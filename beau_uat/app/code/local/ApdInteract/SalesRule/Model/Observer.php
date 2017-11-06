<?php

class ApdInteract_SalesRule_Model_Observer {

    public function addcart() {
        $request = Mage::app()->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $result = 0;



        if ($module == 'checkout' && $controller == 'cart' && ($action == 'updatePost' || $action == 'delete')) { // check if update or delete action
            Mage::getSingleton('checkout/session')->setUpdateCart(0);
        }


        if ($module == 'checkout' && $controller == 'cart' && $action == 'index') {
            $super_attr_orig = Mage::getSingleton('core/session')->getSuperAttrOrig();

            if (is_array($super_attr_orig)) {
                $lastItem = Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection()->getLastItem();
                $last_id = $lastItem->getId();
                $super_array = Mage::getSingleton('core/session')->getSuperAttr();

                if (!is_array($super_array)) {
                    $super_array = array();
                }

                $super_array[$last_id] = $super_attr_orig;
                Mage::getSingleton('core/session')->setSuperAttr($super_array);
                Mage::getSingleton('core/session')->setSuperAttrOrig('');
            }


            $items = Mage::getModel('checkout/cart')->getQuote();
            $rules = Mage::helper('apdinteract_salesrule')->getBundledPricesRules();
            $check_availability = Mage::getSingleton('core/session')->getIgnoreQuote();
            // array of all quotes that has been used for previous bundle rule
            $toValidate = array();
            $step_highest = 0;
            $categories = array();
            $category = array();
            foreach ($rules as $rule) {
                $to_add = array();
                $satisfiedCondition_sku = 0;
                // How many conditions has been satified?
                $conditionToSatify = 0;
                // How many condition to satisfy?
                $satisfiedCondition_category_ids = 0;
                // items for filter
                $g_base_qty = 0;
                // get all quantity
                foreach ($rule as $detail) {
                    $conditionToSatify++;
                    // /count number of condition inside a rule
                    $attribute = $detail['attribute'];
                    $base_qty = $detail['qty'];
                    // total quantity set per condition
                    $bundle = $detail['bundle'];
                    // fixed price for this bundle
                    $step = $detail['steps'];
                    // interval

                    if ($attribute == 'sku') { //Check if rule is based on SKU						
                        //$g_base_qty = 0;
                        $resultOnApplySkuBasedRules = $this->_applySkuBasedRules($detail['rule_id'], $detail['values'], $items, $g_base_qty, $to_add, $base_qty, $satisfiedCondition_sku, $step);
                        $satisfiedCondition_sku = $resultOnApplySkuBasedRules['satisfiedCondition_sku'];
                        $g_base_qty = $resultOnApplySkuBasedRules['g_base_qty'];
                        $to_add = $resultOnApplySkuBasedRules['to_add'];
                    } elseif ($attribute == 'category_ids') {
                        // Check if rule is based on category
                        $total_qty_cat = 0;
                        $resultOnApplyCategoryBasedRules = $this->_applyCategoryBasedRules($items, $detail['values'], $total_qty_cat, $toValidate, $detail['rule_id'], $base_qty, $categories);
                        $total_qty_cat = $resultOnApplyCategoryBasedRules['total_qty_cat'];
                        $toValidate = $resultOnApplyCategoryBasedRules['toValidate'];
                        $categories = $resultOnApplyCategoryBasedRules['categories'];

                       
                            if ($total_qty_cat >= $base_qty) {
                                $satisfiedCondition_category_ids++;
                                if($step>0)
                                    $step_1 = floor($total_qty_cat / $base_qty);
                                else
                                    $step_1 = 1;

                                if ($step_highest <= 0 || $step_highest >= $step_1)
                                    $step_highest = $step_1;
                            }
                        

                        $base = $step_highest * $base_qty;
                        $category[] = array("cat_id" => $detail['values'], "base" => $base_qty);
                    }
                }


                if ($satisfiedCondition_category_ids == $conditionToSatify) {
                    foreach ($category as $category_details) {
                        $categories[$category_details['cat_id']] = $category_details['base'] * $step_highest;
                    }


                    if (count($toValidate) > 0) {
                        $process_this_array = $this->_consolidateCateryProducts($toValidate, $base_qty, $step_highest, $total_qty_cat, $bundle, $categories);
                        // process valid quote data*/
                    }
                }

                $ultimate_listing = array();
                if ($conditionToSatify <= $satisfiedCondition_sku && $satisfiedCondition_sku > 0) {
                    // check if all conditions are met
                    $steps = floor($g_base_qty / $step);
                    if ($steps <= 0)
                        $steps = 1;
                    $bundle_price = $bundle * $steps;
                    // get the final bundle price for all qualified items
                    $ultimate_listing[] = array('bundle' => $bundle_price, 'steps' => $steps, 'values' => $to_add);
                    $result = $result + $this->_accumulateCart($ultimate_listing);
                }


                if (isset($process_this_array) && is_array($process_this_array)) {
                    $category_add = $this->_addForCategoryItems($process_this_array['add']);
                    $result = $result + $category_add;
                    $bundle_array = array();
                    $bundle_array[] = array('bundle' => $process_this_array['bundle'], 'orig_final' => $process_this_array['orig'], 'value' => $process_this_array['update']);
                    $result = $result + Mage::helper('apdinteract_salesrule')->updateCartPrices($bundle_array);
                    Mage::log('check update cart prices category' . $result, null, 'result.log');
                }
            }
        }

        if (Mage::getSingleton('checkout/session')->getUpdateCart() == '0') {
            $this->_updatecart();
            //reformulate cart items
        }

        if ($result > 0) {
            Mage::app()->getResponse()->setRedirect(Mage::getUrl("checkout/cart"));
        }
    }

    private function _addForCategoryItems($additionals) {
        $super_array = Mage::getSingleton('core/session')->getSuperAttr();
        $result = 0;
        foreach ($additionals as $additional) {
            $quote_id = $additional['quote_id'] + 1;
            $params = array('product' => $additional['product'], 'qty' => $additional['qty']);
            $size = $super_array[$quote_id]['attr_val'];
            $toAdd[] = array("params" => $params, "size" => $size, "sku" => $additional['sku'], "quote_id" => $additional['quote_id']);
        }

        $added = Mage::helper('apdinteract_salesrule')->addToCart($toAdd);

        if ($added > 0)
            Mage::getSingleton('checkout/session')->setApdRuleNotification('0');
        $result = $result + $added;

        return $result;
    }

    private function _consolidateCateryProducts($toValidate, $base_qty, $step, $g_base_qty, $bundle, $categories) { //generate final listing of valid items (array for items to add and for update items)
        $skus = Mage::getSingleton('core/session')->getSkuOrig();
        $count = count($toValidate) - 1;
        $to_add = array();
        $steps = floor($g_base_qty / $step);

        if ($steps <= 0)
            $steps = 1;
        $bundle_price = $bundle * $step;
        $orig = $base_qty * $steps;
        $remaining = $orig;
        $new = array();
        $update = array();
        $values = array();
        $sub = 0;
        $max = $step * $base_qty;
        $orig_data = array();
        $orig_data = $categories;
        for ($i = 0; $i <= $count; $i++) {
            $category_id = $toValidate[$i]['category_id'];
            $remaining = $categories[$category_id];
            $qty = $toValidate[$i]['qty'];


            $finalPrice = $skus[$toValidate[$i]['sku']];

            if ($remaining > 0) {

                if ($remaining < $qty) {
                    $categories[$category_id] = $categories[$category_id] - $remaining;

                    if ($categories[$category_id] <= 0) {

                        if ($remaining > 0) {
                            $sub = ($remaining * $finalPrice) + $sub;
                            $update[] = array("quote_id" => $toValidate[$i]['quote_id'], "qty" => $remaining, "product" => $toValidate[$i]['product_id'], "price" => $finalPrice, "update" => 1, "sku" => $toValidate[$i]['sku']);
                            // update qty
                            $newq = $qty - $remaining;

                            if ($newq > 0) {
                                $new[] = array("quote_id" => $toValidate[$i]['quote_id'], "qty" => $newq, "product" => $toValidate[$i]['product_id'], "sku" => $toValidate[$i]['sku']);
                                // add new product
                            }
                        } else {
                            $update[] = array("quote_id" => $toValidate[$i]['quote_id'], "qty" => $qty, "product" => $toValidate[$i]['product_id'], "price" => $finalPrice, "update" => 0, "sku" => $toValidate[$i]['sku']);
                            // update qty
                        }
                    }
                } else {

                    if ($qty > 0) {

                        if ($categories[$category_id] >= 0) {

                            if ($qty > 0) {
                                $sub = ($qty * $finalPrice) + $sub;
                                $update[] = array("quote_id" => $toValidate[$i]['quote_id'], "qty" => $qty, "product" => $toValidate[$i]['product_id'], "price" => $finalPrice, "update" => 1, "sku" => $toValidate[$i]['sku']);
                            }
                        }

                        // update qty
                    }
                }

                $categories[$category_id] = $categories[$category_id] - $qty;
            }
        }

        $values['update'] = $update;
        $values['add'] = $new;
        $values['bundle'] = $bundle_price;
        $values['steps'] = $steps;
        $values['orig'] = $sub;
        return $values;
    }

    private function _accumulateCart($ultimate_listing) { //generate final listing of valid items (array for items to add and for update items)
        $skus = Mage::getSingleton('core/session')->getSkuOrig();
        $result = 0;
        $bundle_array = array();
        $toAdd = array();
        $super_array = Mage::getSingleton('core/session')->getSuperAttr();

        foreach ($ultimate_listing as $list) {
            $bundle_price = $list['bundle'];
            $values = $list['values'];
            $real_steps = $list['steps'];
            $sub = 0;
            $add = array();
            $value_processed = array();
            foreach ($values as $value) {
                $quote_id = $value['quote_id'] + 1;
                $step = $value['step'];
                $qty = $value['qty'];
                $product_id = $value['product_id'];
                $rule_id = $value['rule_id'];
                $base_qty = $value['base_qty'];
                $remainder = $base_qty - $qty;
                $new_qty = $base_qty * $real_steps;
                $remainder = $qty - $new_qty;
                $sku = $value['sku'];
                $finalPrice = $skus[$sku];
                $n_sub = $finalPrice * $new_qty;
                $sub = $sub + $n_sub;

                if (!isset($value_processed[$sku]) && $value_processed[$sku] <= $new_qty) {

                    if ($new_qty > 0) {
                        $add[] = array("quote_id" => $value['quote_id'], "qty" => $new_qty, "price" => $finalPrice, "sku" => $value['sku'], "update" => 1);
                    }


                    if ($remainder > 0) {
                        $params = array('product' => $product_id, 'qty' => $remainder);
                        $size = $super_array[$quote_id]['attr_val'];
                        $toAdd[] = array("params" => $params, "size" => $size, "sku" => $value['sku'], "quote_id" => $value['quote_id']);
                    }
                }

                $value_processed[$sku] = $new_qty;
            }

            $bundle_array[] = array("bundle" => $bundle_price, "orig_final" => $sub, "value" => $add);
        }



        if (count($bundle_array) > 0) {
            $update = Mage::helper('apdinteract_salesrule')->updateCartPrices($bundle_array);
            $result = $result + $update;
        }

        if (count($toAdd) > 0) {
            $added = Mage::helper('apdinteract_salesrule')->addToCart($toAdd);
            $result = $result + $added;

            if ($added > 0)
                Mage::getSingleton('checkout/session')->setApdRuleNotification('0');
            // add qualified items
            Mage::getSingleton('core/session')->setToadd('');
        }

        return $result;
        // update prices of qualified items
    }

    private function _updatecart() {
        $skus = Mage::getSingleton('core/session')->getSkuOrig();
        Mage::getSingleton('checkout/session')->setUpdateCart('');
        $items = Mage::getModel('checkout/cart')->getQuote();
        $items2 = $items;
        $fordelete = array();
        $toupdate = array();
        foreach ($items->getAllVisibleItems() as $item) {
            $quote_id = $item->getId();
            $new = 0;
            foreach ($items2->getAllVisibleItems() as $item2) {

                if ($item2 && $item2->getId() != $quote_id && $item2->getproductId() == $item->getproductId()) {
                    $new = $item->getQty() + $item2->getQty();
                    $flag = $item2->getId();

                    if ($new > 0) {

                        if (!in_array($quote_id, $fordelete))
                            $toupdate[] = $quote_id;

                        if (!in_array($flag, $toupdate))
                            $fordelete[] = $flag;
                        $item->setQty($new);
                        $item->save();
                    }

                    break;
                }
                else {
                    $sku = $item->getSku();
                    if(isset($skus[$sku])) {
                        $item->setOriginalCustomPrice($skus[$sku]);
                        $item->save();
                    }
                }
            }
        }


        if (count($fordelete) > 0) {
            Mage::getSingleton('checkout/session')->setApdRuleNotification('0');
            $this->deleteThis($fordelete);
        }


        if ((count($fordelete) > 0 || count($toupdate) > 0) && Mage::getSingleton('checkout/session')->getUpdateCart() == '0') {
            Mage::getSingleton('core/session')->setIgnoreQuote('');
        }
    }

    public function deleteThis($fordelete) {
        $items = Mage::getModel('checkout/cart')->getQuote();
        $cartHelper = Mage::helper('checkout/cart');
        foreach ($items->getAllVisibleItems() as $item) {

            if (in_array($item->getId(), $fordelete, true)) {
                $cartHelper->getCart()->removeItem($item->getId())->save();
            }
        }
        Mage::getSingleton('core/session')->getMessages(true);
    }

    private function _applySkuBasedRules($rule_id, $values, $items, $g_base_qty, $to_add, $base_qty, $satisfiedCondition_sku, $step) {
        foreach ($items->getAllItems() as $item) {

            if ($item->getSku() == $values) {
                $qty = $item->getQty();
                $check_if_valid = $qty - $base_qty;

                if ($check_if_valid >= 0) { //check if quantity is valid
                    $satisfiedCondition_sku++;
                    $g_base_qty = $g_base_qty + $qty;
                    $to_add[] = array('quote_id' => $item->getId(), 'step' => $step, 'qty' => $qty, 'sku' => $item->getSku(), "product_id" => $item->getProductId(), "rule_id" => $rule_id, "base_qty" => $base_qty);
                    // add value to array if data is valid
                    Mage::helper('apdinteract_salesrule')->compileSkus($item->getSku(), $item->getPriceInclTax());
                }
            }

            // }
        }

        return array("satisfiedCondition_sku" => $satisfiedCondition_sku, "g_base_qty" => $g_base_qty, "to_add" => $to_add);
    }

    private function _applyCategoryBasedRules($items, $values, $total_qty_cat, $toValidate, $rule_id, $base_qty, $categories) {
        foreach ($items->getAllVisibleItems() as $item) {
            $product = $item->getProduct();
            $category_ids = $product->getCategoryIds();

            if (in_array($values, $category_ids, true)) {
                // check if quote item is under the category
                $qty = $item->getQty();
                $total_qty_cat = $total_qty_cat + $qty;
                $toValidate[] = array('quote_id' => $item->getId(), 'step' => $step, 'qty' => $qty, "product_id" => $item->getProductId(), "sku" => $item->getSku(), "rule_id" => $rule_id, "base_qty" => $base_qty, "category_id" => $values);
                // add data to array for qualified quote item
                $categories[] = $base_qty;
                Mage::helper('apdinteract_salesrule')->compileSkus($item->getSku(), $item->getPriceInclTax());
            }
        }

        return array("total_qty_cat" => $total_qty_cat, "toValidate" => $toValidate, "categories" => $categories);
    }

}

?>