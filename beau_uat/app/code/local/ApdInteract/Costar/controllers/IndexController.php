<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ApdInteract_Costar_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function stockLevelStatusAction() {
        $storeId = $this->_getParam('store_id');
        $storeDetails = $this->_getCostarDetails($storeId);
        $costarCode = $storeDetails[0];
        $cartActive = $storeDetails[1];
        $array_sku = $this->_getParam('sku');


        $mergeSku = array();
        foreach ($array_sku as $sku) {
            // trim SKUs e.g. stock-status-562920 to 562920
            $trimSku = substr($sku, 13);
            $mergeSku[] = $trimSku;
        }

        $message = '';
        $status = '';
        $response = array();
        foreach ($mergeSku as $sku) {
            $sku_item_id_array = explode("-", $sku);
            $product = Mage::helper('costar/sftp')->getStockQty($costarCode, $sku_item_id_array[0]);

            if($cartActive==0):
                if ($product['qty'] > 3) {
                    $message = 'In Stock';
                    $status = '';
                    $error = '';
                } elseif (($product['qty'] < 4) && ($product['qty'] > 0 )) {
                    $message = 'Low Stock';
                    $status = '';
                    $error = '';
                } elseif (($product['qty'] == 0) && ($product['is_active'] == 0 )) {
                    $message = 'Out of Stock';
                    $status = Mage::helper('apdinteract_tooltip')->getOutofStockTooltip();
                    $error = 'Order cannot be completed with out of stock products. Please select another store to find available stock or call us on 13 23 81 for assistance.';
                } elseif (($product['qty'] == 0) && ($product['is_active'] == 1 ) && ($this->_isTyreProduct($sku_item_id_array[0]))) {
                    $message = '1-3 days to arrive';
                    $status = Mage::helper('apdinteract_tooltip')->getXdaysToArriveTooltip();
                    $error = '';
                } elseif (($product['qty'] == 0) && ($product['is_active'] == 1 ) && (!$this->_isTyreProduct($sku_item_id_array[0]))) {
                    $message = '3-5 days to arrive';
                    $status = Mage::helper('apdinteract_tooltip')->getXdaysToArriveTooltip();
                    $error = '';
                } else {
                    $message = 'Stock Unknown';
                }
            else:
                 $message = 'Out of Stock';
                 $status = Mage::helper('apdinteract_tooltip')->getNotAvailableTooltip();
                 $error = 'The product(s) are not available at the store you have selected. Please search for an alternative store.';
            endif;

            $result = [];
            if($storeId != ""){
                $storeInfo = $this->getStoreInfoById($storeId);
                $state = Mage::Helper('sync')->getRegionById($storeInfo->getRegionId());
                $result = $this->_getPublicHolidayByState($state);
            }

            $response[] = array(
                'message' => $message,
                'status' => $status,
                'sku' => $sku_item_id_array[0],
                'error' =>$error,
                'item' => $sku_item_id_array[1]
            );
        }

        $response['publicHoliday'] = json_decode($result,true);

        echo json_encode($response);
    }

    private function _getParam($param) {
        return Mage::app()->getRequest()->getParam($param);
    }

    private function _getCostarDetails($id) {
        $store = Mage::getModel('storelocator/stores')->load($id);
        $details = array($store->getCostarStoreCode(),$store->getExcludeFromCart());
        return $details;
    }

    private function _isTyreProduct($sku) {
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
        $category = $product->getCategoryIds();
        $categoryId = isset($category[0]) ? $category[0] : '';

        if ($categoryId == '41') {
            return true;
        } else {
            return false;
        }
    }


    /**
     *
     * Check Stock from Costar API
     * @return string json
     */
    function checkStockAction() {

        $storeId = $this->_getParam('store_id');
        $cartItems = $this->_getParam('sku');

        //getStore Info
        $storeInfo = $this->getStoreInfoById($storeId);

        $response = array("success"=>true,"error"=>"");
        $scenario = 0;
        $status = $error = '';
        foreach ($cartItems as $item) {

            $item = substr($item, 13); // trim SKUs e.g. stock-status-562920 to 562920

            $itemArray = explode("__",$item); //explode sku,itemId and Qty
            
            if($itemArray[0]!='wheel' && $itemArray[0]!='AS_6639996' && $itemArray[0]!='AS_6540008' && $itemArray[0]!='AS_6666997' && $itemArray[0]!='AS_6666971' && $itemArray[0]!='AS_6969991') {
            

            $stockQueryFieldsArray = array("costarLiveId" => $storeInfo->getPCostarLiveId(),
                "branchPassword" => $storeInfo->getPBranchPassword(),
                "branchCode" => $storeInfo->getCostarStoreCode(),
                "itemSku" => $itemArray[3]);

            try {
                //Make Costar API Call
                $costarItemStockArray = Mage::getModel('apdinteract_costar/api')->stockQuery($stockQueryFieldsArray);
                $product = Mage::getModel('catalog/product')->load($itemArray[0],'sku');
                if($product->getIsInStock()) {
                    if ($costarItemStockArray['error']) {
                        $error = 'We are currently experiencing a problem with the internet connection. Please try again or contact us to arrange your purchase.';
                        $message = 'Stock Unknown';
                        $status = Mage::helper('apdinteract_tooltip')->getNotAvailableTooltip();
                        $scenario = 3;
                    } elseif ($itemArray[2] <= $costarItemStockArray['costarQty']) {
                        if ($costarItemStockArray['costarQty'] < 4) {
                            $message = 'Low Stock';
                        } else {
                            $message = "In Stock";
                        }
                        $scenario = 0;
                    } elseif ($costarItemStockArray['costarQty'] == 0 && ($this->_isTyreProduct($itemArray[0]))) {
                        $message = "1-3 days to arrive";
                        $status = Mage::helper('apdinteract_tooltip')->getXdaysToArriveTooltip();
                        $scenario = 0;
                    } else {
                        $message = '3-5 days to arrive';
                        $status = Mage::helper('apdinteract_tooltip')->getXdaysToArriveTooltip();
                        $scenario = 0;
                    }
                } else {
                    $message = 'This product is not in stock';
                    $status = 'Out of Stock';
                    $scenario = 2;
                }
            }catch (Exception $e) {

                $error = 'We are currently experiencing a problem with the internet connection. Please try again or contact us to arrange your purchase.';
                $message = 'Stock Unknown';
                $scenario = 1;
                $status = Mage::helper('apdinteract_tooltip')->getNotAvailableTooltip();

                Mage::helper('costar/api')->log("Exception Error : ");
                Mage::helper('costar/api')->log($e->getMessage());
            }

            $result = [];
            if($storeId != ""){
                $state = Mage::Helper('sync')->getRegionById($storeInfo->getRegionId());
                $result = $this->_getPublicHolidayByState($state);
            }
                
            } else {
                $message = "In Stock";
            }

            $response['data'][] = array(
                'message' => $message,
                'status'  => $status,
                'sku'     => $itemArray[0], //sku
                //'error'   => $error,
                'item'    => $itemArray[1], //item Id
                'qty'     => $itemArray[2],
                'sapcode' => $itemArray[3],
                'response' => $scenario
            );
        }

        $response['publicHoliday'] = json_decode($result,true);

        if(!empty($error)){
            $response['success'] = false;
            $response['error'] = $error;
        }

        Mage::getSingleton('core/session')->setStorelocation($storeId);
        Mage::helper('costar/api')->log("Final Output Response : ");
        Mage::helper('costar/api')->log($response);

        echo json_encode($response);

    }

    private function _getPublicHolidayByState($state){

        $method = 'get_public_holiday';
        $client         = Mage::Helper('sync')->connect_api($method);
        $client->setParameterPost('state', $state);
        $response = $client->request(Zend_Http_Client::POST);

        return $response->getBody();
    }


    /**
     * Get Store Information Using Store ID
     *
     * @param $id
     * @return mixed
     */

    private function getStoreInfoById($id) {
        $store = Mage::getModel('storelocator/stores')->load($id);
        return $store;
    }


}
