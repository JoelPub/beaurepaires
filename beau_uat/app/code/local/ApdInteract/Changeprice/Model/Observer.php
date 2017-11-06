<?php

class ApdInteract_Changeprice_Model_Observer {

    private $_messageIsSet;

    public function addprice(Varien_Event_Observer $observer) {
        $item = $observer->getQuoteItem();
        $this->_setParentPriceToChildPrice($item);
        $this->_setOptions($item);
    }

    public function _setOptions($item) {
        $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
        $super_attr = array();
        if (isset($options['info_buyRequest']['super_attribute'])) {
            $super = $options['info_buyRequest']['super_attribute'];
            foreach ($super as $key => $value) {
                $super_attr['attr_id'] = $key;
                $super_attr['attr_val'] = $value;
            }
        } else {
            $super_attr = '';
        }


        $super_array = Mage::getSingleton('core/session')->getSuperAttrOrig();
        if (is_array($super_attr) && trim($super_attr['attr_val']) != '') {
            $super_array = array(
                "product_id" => $item->getProductId(),
                "super" => $super_attr
            );
            Mage::getSingleton('core/session')->setSuperAttrOrig($super_attr);
        }
    }

    public function modifyprice(Varien_Event_Observer $observer) {
        $parent_item = $observer->getItem();
        if ($parent_item->getData('has_children')) {
            $child_item = $parent_item->getOptionByCode('simple_product');
        }
        $this->_setParentPriceToChildPrice($parent_item, $child_item);
        $this->_addUpdateMessage($parent_item);
    }

    public function removeItemFromCart(Varien_Event_Observer $observer) {
        // Add a message saying "xxx" was removed from cart
        $product = $observer->getQuoteItem()->getProduct();
        $message = Mage::helper('catalog')->__('%s was removed from your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
        $this->_addMessage($message);
    }

    private function _addUpdateMessage($item) {
        // Add a "XXX was updated successfully" message
        // For some reason, this is triggered twice.
        // And the clear messages routine below doesn't stop it.
        if ($this->_messageIsSet) {
            $product = $item->getProduct();
            $message = Mage::helper('catalog')->__('%s was updated in your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
            $this->_addMessage($message);
        }
        $this->_messageIsSet = true;
    }

    protected function _getSession() {
        return Mage::getSingleton('checkout/session');
    }

    private function _addMessage($message) {
        if ($this->_messageIsSet) {
            Mage::getSingleton('core/session')->getMessages(true); // Clear messages        
        }
        $this->_messageIsSet = true;
        $this->_getSession()->addSuccess($message);
        // $this->_redirect('checkout/cart/'); 
    }

    private function _setParentPriceToChildPrice($item, $child_item = false) {
        /* @var $item Mage_Sales_Model_Quote_Item */
        // If configurable, get the price of the child product
        // otherwise just use the original price.

        $parent_item = $item->getParentItem();

        if ($parent_item || $child_item) {

            $save = true;

            if (!$child_item) {
                $child_item = $item;
                $item = $parent_item;
                $save = false;
            }

            // $specialPrice = $child_item->getProduct()->getFinalPrice(); // not enough, we need to load the product again            
            $childProduct = Mage::getModel('catalog/product')->load($child_item->getProduct()->getId());
            $specialPrice = $childProduct->getFinalPrice();

            // Make sure we don't have a negative
            if ($specialPrice > 0) {
                $item->setCustomPrice($specialPrice);
                $item->setOriginalCustomPrice($specialPrice);
                $item->getProduct()->setIsSuperMode(true);
                if ($save) {
                    $item->save();
                }
            }
        }
    }

}
