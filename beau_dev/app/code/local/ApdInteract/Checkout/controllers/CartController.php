<?php

/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */
/**
 * Shopping cart controller
 */
require 'Mage/Checkout/controllers/CartController.php';

class ApdInteract_Checkout_CartController extends Mage_Checkout_CartController {

    private $_params;

    private function _getRimConfig($product_id) {
        $prod = Mage::getModel('catalog/product')->load($product_id);
        return $prod->getRimDiameterConfigurable();
    }

    private function _getAttributeValue($product_id, $attribute_code) {
        $prod = Mage::getModel('catalog/product')->load($product_id);
        return $prod->getData($attribute_code);
    }

    private function _addConfigProd($parent_product_id, $child_product_id, $qty, $options, $alignment = false) {
        if (!$qty) {
            return;
        }

        $product = Mage::getModel('catalog/product')->load($parent_product_id);
        $cart = $this->_getCart();
        $cart->init();

        $superAttributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);

        $params = array(
            'product' => $parent_product_id,
            'super_attribute' =>
                array(
                    $superAttributes[0]['attribute_id'] =>
                        $this->_getAttributeValue($child_product_id, $superAttributes[0]['attribute_code'])
                ),
            'qty' => $qty,
            'options' => $options
        );
        $cart->addProduct($product, $params);

        if ($alignment)
            $this->_addWheelAlignmentProduct($cart);



        $cart->save();
        Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
        return true;
    }

    public function frontrearaddAction($isAjax) {
        
        $params = $this->_getParams();
        $isAjax = ((isset($params['xhr']) && $params['xhr']) || $isAjax); 

        try {

            $frontProduct = Mage::getModel('catalog/product')->load($params['front_id']);
            $rearProduct = Mage::getModel('catalog/product')->load($params['rear_id']);

            $wheelAlignAdd = true;
            $paramsOptions = isset($params['options']) ? $params['options'] : '';
            if ($params['rear_qty'] >0 && $params['rear_id']>0 && ($rearProduct->getFinalPrice() > 0 || $rearProduct->getFreeProduct())):
                $wheelAlignAdd = false;
                $this->_addConfigProd($params['configurable_id'], $params['rear_id'], $params['rear_qty'], $paramsOptions, true);
            endif;

            if ($params['front_qty'] >0 && $params['front_id']>0 && ($frontProduct->getFinalPrice() > 0 || $frontProduct->getFreeProduct())):
                $wheelAlignAdd = $params['rear_qty'] < 1 ? true : false;
                $this->_addConfigProd($params['configurable_id'], $params['front_id'], $params['front_qty'], $paramsOptions, $wheelAlignAdd);
            endif;

            $cartUrl = Mage::helper('checkout/cart')->getCartUrl();

            if(!$isAjax) {
                Mage::getSingleton('core/session')->addSuccess('Product added successfully');
                $this->getResponse()->setRedirect($cartUrl);
            } else {
                return array(
                    'status' => 200,
                    'msg' => 'Product added successfully',
                    'redirect_url' => $cartUrl,
                    'count' =>  Mage::getSingleton('checkout/session')->getQuote()->getItemsSummaryQty()
                );
            }

            return;
        } catch (Mage_Core_Exception $e) {
            $errMsg = $e->getMessage();
            if(!$isAjax) {
                $this->_getSession()->addException($e, $this->__($errMsg));
                Mage::logException($e);
                $this->_goBack();
            } else {
                return array(
                    'status' => 503,
                    'msg' => $errMsg,
                    'count' =>  Mage::helper('checkout/cart')->getSummaryCount()
                );
            }
        }
    }

    private function _getParams() {
        if (!isset($this->_params)) {
            $this->_params = $this->getRequest()->getParams();
        }
        return $this->_params;
    }

    public function addAction() {

        $errMsg = array();
        $params = $this->_getParams();
        $isAjax = ((isset($params['xhr']) && $params['xhr']) || $isAjax);

        if (!$this->_validateFormKey()) {
            if(!$isAjax) {
                $this->_goBack();
                return;
            } else {
                $errMsg[] = 'Invalid form key';
            }
        }

        // See if it's front/rear type
        if (isset($params['rear_id']) || isset($params['front_id']) && !count($errMsg)) {
            if(!$isAjax) {
                return $this->frontrearaddAction();
            } else {
                $r = $this->frontrearaddAction($isAjax);
                $this->getResponse()->setHeader('Content-type', 'application/json');
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($r));
                return;
            }
            
            
        }

        try {
            $cart = $this->_getCart();

            // Replace add logic with something like this:
            $parent_product_id = $params['product'];
            $child_product_id = reset($params['super_attribute']); // first element of array.

            if ($parent_product_id && $child_product_id) {
                $this->_addConfigProd($parent_product_id, $child_product_id, $params['qty'], $params['options'], true);
            } else {
                // Add other product types

                if (isset($params['qty'])) {
                    $filter = new Zend_Filter_LocalizedToNormalized(
                            array('locale' => Mage::app()->getLocale()->getLocaleCode())
                    );
                    $params['qty'] = $filter->filter($params['qty']);
                }

                $product = $this->_initProduct();

//            /**
//             * Check product availability
//             */
                if (!$product && !$isAjax) {
                    $this->_goBack();
                    return;
                } 

                if(!$product && $isAjax) {
                    $errMsg[] = 'Invalid Product';
                    return;
                }

                $cart->addProduct($product, $params);
                //$this->_addWheelAlignmentProduct($cart);
            }

            $related = $this->getRequest()->getParam('related_product');
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $this->_addWheelAlignmentProduct($cart);

            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);

            /**
             * @todo remove wishlist observer processAddToCart
             */
            Mage::dispatchEvent('checkout_cart_add_product_complete', array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );

            if (!$this->_getSession()->getNoCartRedirect(true)) {
//                if (!$cart->getQuote()->getHasError()) {
//                    $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
//                    $this->_getSession()->addSuccess($message);
//                }
                if(!$isAjax) {
                    $this->_goBack();
                }
            }

            if($isAjax) {
                $hasError = count($errMsg);
                $this->getResponse()->setHeader('Content-type', 'application/json');
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(
                    array(
                        'status' => !$hasError ? 200 : 503,
                        'msg' => !$hasError ? $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName())) : $errMsg,
                        'count' =>  $hasError ? Mage::helper('checkout/cart')->getSummaryCount() : Mage::getSingleton('checkout/session')->getQuote()->getItemsSummaryQty()
                    )
                ));
            }

        } catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice(Mage::helper('core')->escapeHtml($e->getMessage()));
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError(Mage::helper('core')->escapeHtml($message));
                }
            }

            $url = $this->_getSession()->getRedirectUrl(true);

            if(!$isAjax) {
                if ($url) {
                    $this->getResponse()->setRedirect($url);
                } else {
                    $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
                }
            } else {
                $this->getResponse()->setHeader('Content-type', 'application/json');
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(
                    array(
                        'status' => 503,
                        'msg' => $messages,
                        'redirect_url' => ($url) ? $url : Mage::helper('checkout/cart')->getCartUrl(),
                        'count' =>  Mage::helper('checkout/cart')->getSummaryCount()
                    )
                ));
            }
         
        } catch (Exception $e) {
            if(!$isAjax) {
                $this->_getSession()->addException($e, $this->__('Cannot add the item to shopping cart.'));
                Mage::logException($e);
                $this->_goBack();
            } else {
                $this->getResponse()->setHeader('Content-type', 'application/json');
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(
                    array(
                        'status' => 503,
                        'msg' => 'Cannot add the item to shopping cart.',
                        'count' =>  Mage::helper('checkout/cart')->getSummaryCount()
                    )
                ));
            }
        }
    }

    private function _addWheelAlignmentProduct($cart) {
        $ids = Mage::app()->getRequest()->getPost('extra-product');
        if(!is_array($ids)){
            return;
        }
        foreach ($ids as $id):
            $value = explode('_', $id);
            $_id = $value[0];
            $proceed = true;
            $product = Mage::getModel('catalog/product')->load($_id);
            if ($value[1] == 'no'):
                $qty = 1;
                $proceed = $this->checkIfWheelAlignment($product);
            else:
                $qty = Mage::app()->getRequest()->getPost('front_qty') + Mage::app()->getRequest()->getPost('rear_qty') + Mage::app()->getRequest()->getPost('qty');
            endif;
            if ($_id && $proceed):
                try {
                    
                    $param = array(
                        'product' => $_id,
                        'qty' => $qty
                    );
                    
                    $cart->addProduct($product, $param);
                } catch (Exception $e) {
                    Mage::logException($e);
                }
            endif;
        endforeach;
    }
    
     private function checkIfWheelAlignment() {
        
        $skus = array('AS_6639996','AS_6540008'); 
        
        $quote = Mage::getSingleton('checkout/session')->getQuote();

        $foundInCart = true;
        foreach($quote->getAllVisibleItems() as $item) {
            if (in_array($item->getData('sku'),$skus)) {
                $foundInCart = false;
                break;
            }
        }
        
        return $foundInCart;                 
    }
    
    /**
     * Delete shoping cart item action
     */
    public function deleteAction()
    {
        if ($this->_validateFormKey()) {
            $id = (int)$this->getRequest()->getParam('id');
            if ($id) {
                try {
                    $this->_getCart()->removeItem($id)
                        ->save();
                } catch (Exception $e) {
                    $this->_getSession()->addError($this->__('Cannot remove the item.'));
                    Mage::logException($e);
                }
            }
        } else {
            $this->_getSession()->addError($this->__('Cannot remove the item.'));
        }

       $this->_redirect('checkout/cart');
    }
    
    /**
     * Update customer's shopping cart
     */
    protected function _updateShoppingCart()
    {
        $max  = Mage::getStoreConfig('cataloginventory/item_options/max_sale_qty');
        $update = true; 
        try {
            $cartData = $this->getRequest()->getParam('cart');
            if (is_array($cartData)) {
                $filter = new Zend_Filter_LocalizedToNormalized(
                    array('locale' => Mage::app()->getLocale()->getLocaleCode())
                );
                foreach ($cartData as $index => $data) {
                    if (isset($data['qty'])) {
                        if($max>0  &&  $data['qty'] > $max)
                            $update = false; //set to false if qty exceeds max sales qty per item on  System -> Configuration -> Catalog -> Inventory -> Product Stock Options
                        
                        $cartData[$index]['qty'] = $filter->filter(trim($data['qty']));
                    }
                }
                $cart = $this->_getCart();
                if (! $cart->getCustomerSession()->getCustomer()->getId() && $cart->getQuote()->getCustomerId()) {
                    $cart->getQuote()->setCustomerId(null);
                }
                if($update) {
                    $cartData = $cart->suggestItemsQty($cartData);
                    $cart->updateItems($cartData)
                        ->save();
                } else {
                    $message = $this->__('Unable to update cart. Maximum of quantity of '.$max.' per item has been reached.' );
                    $this->_getSession()->addError($message);
                    $this->_redirect('checkout/cart');
                }
            }
            $this->_getSession()->setCartWasUpdated(true);
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError(Mage::helper('core')->escapeHtml($e->getMessage()));
        } catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot update shopping cart.'));
            Mage::logException($e);
        }
    }

    /**
     * Get Store Location
     * @return array
     */
    protected function _getStoreLocation(){

       $storeId = Mage::getSingleton('core/session')->getStorelocation();
        if($storeId){
            $model  = Mage::getModel('storelocator/stores')->load($storeId);
            if($model->getId()){
                $region = Mage::getModel('directory/region')->load($model->getData('region_id'));

                $location = array(
                    $model->getData('street'),
                    $model->getData('city'),
                    $region->getName(),
                    $model->getData('postal_code')
                );

                return array(
                    "id" =>$model->getData('entity_id'),
                    "name" => $model->getData('title'),
                    "address" => implode(" ",$location),
                );
            }
        }

    }
    public function ajaxDisplayAction(){

        $result = array();
        $result['qty'] = $this->_getCart()->getSummaryQty();

        $this->loadLayout();
        $blockHtml = preg_replace('/<!--(.*)-->/Uis', '', $this->getLayout()->getBlock('minicart_ajax')->toHtml());
        $result['store_location'] = $this->_getStoreLocation();
        $result['content'] = json_decode($blockHtml);
        $result['success'] = 1;
        $result['form_key'] = Mage::getSingleton('core/session')->getFormKey();

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Override Minicart ajax update qty action
     */
    public function ajaxUpdateAction()
    {
        if (!$this->_validateFormKey()) {
            Mage::throwException('Invalid form key');
        }

        $id = (int)$this->getRequest()->getParam('id');
        $qty = $this->getRequest()->getParam('qty');
        $result = array();
        if ($id) {
            try {
                $cart = $this->_getCart();
                if (isset($qty)) {
                    $filter = new Zend_Filter_LocalizedToNormalized(
                        array('locale' => Mage::app()->getLocale()->getLocaleCode())
                    );
                    $qty = $filter->filter($qty);
                }

                $quoteItem = $cart->getQuote()->getItemById($id);
                if (!$quoteItem) {
                    Mage::throwException($this->__('Quote item is not found.'));
                }
                if ($qty == 0) {
                    $cart->removeItem($id);
                } else {
                    $quoteItem->setQty($qty)->save();
                }
                $this->_getCart()->save();

                $this->loadLayout();
                $blockHtml = preg_replace('/<!--(.*)-->/Uis', '', $this->getLayout()->getBlock('minicart_ajax')->toHtml());
                $result['content'] = json_decode($blockHtml);

                $result['qty'] = $this->_getCart()->getSummaryQty();

                if (!$quoteItem->getHasError()) {
                    $result['message'] = $this->__('Item was updated successfully.');
                } else {
                    $result['notice'] = $quoteItem->getMessage();
                }
                $result['success'] = 1;
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['error'] = $this->__('Can not save item.');
            }
        }

        $result['form_key'] = Mage::getSingleton('core/session')->getFormKey();
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    /**
     * Override Minicart delete action
     */
    public function ajaxDeleteAction()
    {
        if (!$this->_validateFormKey()) {
            Mage::throwException('Invalid form key');
        }
        $id = (int) $this->getRequest()->getParam('id');
        $result = array();
        if ($id) {
            try {
                $this->_getCart()->removeItem($id)->save();

                $result['qty'] = $this->_getCart()->getSummaryQty();

                $this->loadLayout();
                $blockHtml = preg_replace('/<!--(.*)-->/Uis', '', $this->getLayout()->getBlock('minicart_ajax')->toHtml());
                $result['content'] = json_decode($blockHtml);

                $result['success'] = 1;
                $result['message'] = $this->__('Item was removed successfully.');
                Mage::dispatchEvent('ajax_cart_remove_item_success', array('id' => $id));
            } catch (Exception $e) {
                $result['success'] = 0;
                $result['error'] = $this->__('Can not remove the item.');
            }
        }

        $result['form_key'] = Mage::getSingleton('core/session')->getFormKey();
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }


}
