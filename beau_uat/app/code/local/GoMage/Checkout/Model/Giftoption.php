<?php

/**
 * Class Advintage_OneStepCheckout_Model_GiftOption
 */
class Gomage_Checkout_Model_Giftoption extends Mage_Core_Model_Abstract
{
    public function captureBeforeShippingMethod()
    {
        $this->updateSession();
    }

    public function captureBeforeShipping()
    {
        $this->updateSession();
    }

    public function captureBeforePaymentInfo()
    {
        $session = Mage::getSingleton('checkout/session');
        /** @var $cartHelper Mage_Checkout_Helper_Cart */
        $cartHelper = Mage::helper('checkout/cart');
        $items = $cartHelper->getCart()->getItems();
        $customgiftoption = Mage::app()->getRequest()->getParam('customgiftmessage');
        $allow_gift_messages_for_order = Mage::app()->getRequest()->getParam('allow_gift_messages_for_order');
        $allow_gift_options = Mage::app()->getRequest()->getParam('allow_gift_options');
        $boxOldData = Mage::getSingleton('core/session')->getGiftBox();
        $gift_comments = '';
        if ($allow_gift_messages_for_order && $allow_gift_options) {
            $stylish_card = isset($customgiftoption['stylish-card']);
            Mage::getSingleton('core/session')->setStylishCard($stylish_card);
            $gift_box = isset($customgiftoption['packaging'])
                && $customgiftoption['packaging'] != 0
                && $customgiftoption['gift-box'] != '';
            Mage::getSingleton('core/session')->setGiftBox(
                ($gift_box) ? $customgiftoption['gift-box'] : false
            );
            $gift_comments = isset($customgiftoption['gift_comments']) ? $customgiftoption['gift_comments'] : false;
        } else {
            Mage::getSingleton('core/session')->setStylishCard(false);
            Mage::getSingleton('core/session')->setGiftBox(false);
            $gift_comments = '';
        }

        //add product 3113
        $p3113 = Mage::getModel('catalog/product')->loadByAttribute('sku', '3113');
        if (Mage::getSingleton('core/session')->getStylishCard()) {
            if (!$session->getQuote()->hasProductId($p3113->getId())) {
                $qty = '1'; // Replace qty with your qty
                $_product = Mage::getModel('catalog/product')->load($p3113->getId());
                $cart = Mage::getModel('checkout/cart');
                $cart->init();
                $cart->addProduct($_product, array('qty' => $qty));
                $session->setCartWasUpdated(false);
                $cart->save();
            }
        } else {
            /** @var $session Mage_Checkout_Model_Session */
            if (is_object($p3113) && $session->getQuote()->hasProductId($p3113->getId())) {
                foreach ($items as $item) {
                    if ($item->getProduct()->getId() == $p3113->getId()) {
                        $itemId = $item->getItemId();
                        $session->setCartWasUpdated(false);
                        $cartHelper->getCart()->removeItem($itemId)->save();
                        break;
                    }
                }
            }
        }
        //add gift box
//        $category = Mage::getResourceModel("catalog/category_collection")->addFieldToFilter("name", "Gift box");
//        $cat_det = $category->getData();
//        $category_id = $cat_det[0]["entity_id"];
        $category_id = (int) Mage::getStoreConfig('advintage_category/category_general/giftBoxesId');
        $products = Mage::getModel('catalog/category')->load($category_id)
            ->getProductCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 1);
        $productsArray = array();
        foreach ($products as $p) {
            $productsArray[] = $p->getId();
        }
        if (Mage::getSingleton('core/session')->getGiftBox()) {
            if ($boxOldData != Mage::getSingleton('core/session')->getGiftBox()) {
                foreach ($items as $item) {
                    if (in_array($item->getProduct()->getId(), $productsArray)) {
                        $itemId = $item->getItemId();
                        $session->setCartWasUpdated(false);
                        $cartHelper->getCart()->removeItem($itemId)->save();
                    }
                }
                $pgb = Mage::getModel('catalog/product')->loadByAttribute('sku', Mage::getSingleton('core/session')->getGiftBox());
                $qty = '1'; // Replace qty with your qty
                Mage::log($pgb->getId());
                $_product = Mage::getModel('catalog/product')->load($pgb->getId());
                $cart = Mage::getModel('checkout/cart');
                $cart->init();
                $cart->addProduct($_product, array('qty' => $qty));
                $session->setCartWasUpdated(false);
                $cart->save();
            }
        } else {
            foreach ($items as $item) {
                if (in_array($item->getProduct()->getId(), $productsArray)) {
                    $itemId = $item->getItemId();
                    $session->setCartWasUpdated(false);
                    $cartHelper->getCart()->removeItem($itemId)->save();
                }
            }
        }
        $session->getQuote()->setGiftComments($gift_comments)->save();
    }

    public function updateSession()
    {
        $session = Mage::getSingleton('checkout/session');
        Mage::getSingleton('core/session')->setStylishCard(false);
        Mage::getSingleton('core/session')->setGiftBox(false);

        $category_id = (int) Mage::getStoreConfig('advintage_category/category_general/giftBoxesId');
        $products = Mage::getModel('catalog/category')->load($category_id)
            ->getProductCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', 1);

        foreach ($products as $p) {
            if ($session->getQuote()->hasProductId($p->getId())) {
                Mage::getSingleton('core/session')->setGiftBox($p->getSku());
            }
        }

        $p3113 = Mage::getModel('catalog/product')->loadByAttribute('sku', '3113');
        if (is_object($p3113) && $session->getQuote()->hasProductId($p3113->getEntityId()) ) {
            Mage::getSingleton('core/session')->setStylishCard(true);
        }

        Mage::getSingleton('core/session')->setMessageGiftCard(Mage::app()->getRequest()->getParam('giftmessage'));
    }
}