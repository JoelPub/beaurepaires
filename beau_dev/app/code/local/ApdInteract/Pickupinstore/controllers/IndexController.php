<?php
class ApdInteract_Pickupinstore_IndexController extends Mage_Core_Controller_Front_Action
{
    public function storesbylatlngAction()
    {
        $this->loadLayout(false);
        $this->renderLayout();
        //                Zend_Debug::dump($this->getLayout()->getUpdate()->getHandles());
    }

    public function getStoreInformationAction(){

        $storeId = Mage::app()->getRequest()->getParam('store_id');
        $data = array("result" => 0,"data"=>array());

        if($storeId){
            $model  = Mage::getModel('storelocator/stores')->load($storeId);

            if(count($model)){
                $region = Mage::getModel('directory/region')->load($model->getData('region_id'));
                $data = array(
                    "result" => 1,
                    "data" =>
                    array(
                        "entity_id" => $model->getData('entity_id'),
                        "title" => $model->getData('title'),
                        "is_active" => $model->getData('is_active'),
                        "phone" => $model->getData('phone'),
                        "email" => $model->getData('email'),
                        "country_id" => $model->getData('country_id'),
                        "region_id" => $model->getData('region_id'),
                        "region" => $region->getName(),
                        "street" => $model->getData('street'),
                        "city" => $model->getData('city'),
                        "postal_code" => $model->getData('postal_code'),
                    ),
                );
            }
        }

        echo json_encode($data);
    }
    
    public function saveAction()
    {

        $post          = Mage::app()->getRequest()->getParams();
        // get the address rather than the id, and save that value
        $store_id      = $post['storeloc'];
        $duration      = $post['duration'];
        $dtime         = $post['dtime'];
        $response = 0;
        Mage::getSingleton('core/session')->unsStorelocation(); //unset

        if($store_id != ""){
            Mage::getSingleton('core/session')->setStorelocation($store_id);
            $response = 1;
        }

        Mage::getSingleton('core/session')->setDeliveryDate($post['ddate']);
        Mage::getSingleton('core/session')->setDtime($dtime);
        Mage::getSingleton('core/session')->setDuration($duration);

        echo $response;
    }
    
    public function checkavailabilityAction()
    {
        $post = Mage::app()->getFrontController()->getRequest()->getPost();
        
        $store_id = $post['storeloc'];
        $date     = $_POST['ddate'];
        $date_ar  = explode("/", $date);
        $time     = $_POST['dtime'];
        $time_ar  = explode(":", $time);
        $duration = $_POST['duration'];
        
        $datetime_from = mktime($time_ar[0], $time_ar[1], 0, $date_ar[1], $date_ar[0], $date_ar[2]);
        $datetime_to   = mktime($time_ar[0], $time_ar[1] + $duration, 0, $date_ar[1], $date_ar[0], $date_ar[2]);
        
        $method = "ajax_get_busy_times";
        $client = Mage::Helper('sync')->connect_api($method);
        $client->setParameterGet("store_id", $store_id);
        $client->setParameterGet("date", $date_ar[0] . $date_ar[1] . $date_ar[2]);
        $client->setParameterGet("for_minutes", $duration);
        $response = $client->request();
        $data     = $response->getBody();
        
        //echo date("m-d-Y h:i:s",$datetime_from);
        $json_array       = json_decode($data);
        $busy_times       = $json_array->unavailable;
        $count_busy_sched = count($busy_times);
        
        
        $response = 'ok';
        if ($count_busy_sched > 0) {
            for ($i = 0; $i <= $count_busy_sched; $i++) {
                
                if ($busy_times[$i] != '') {
                    
                    
                    $busy_time                = explode("-", $busy_times[$i]);
                    $ftime1                   = sprintf('%04d', $busy_time[0]);
                    $ftime2                   = sprintf('%04d', $busy_time[1]);
                    $from                     = str_split($ftime1, 2);
                    $to                       = str_split($ftime2, 2);
                    $formulated_datetime_from = mktime($from[0], $from[1], 0, $date_ar[1], $date_ar[0], $date_ar[2]);
                    $formulated_datetime_to   = mktime($to[0], $to[1], 0, $date_ar[1], $date_ar[0], $date_ar[2]);
                    
                    for ($j = $datetime_from; $j <= $datetime_to; $j += 900) {
                        if ($j > $formulated_datetime_from && $j < $formulated_datetime_to) {
                            $response = "Selected booking schedule is unavailable. Please choose another schedule";
                            break 2;
                        }
                    }
                    
                }
            }
        }
        echo $response;
    }
    
    public function couponPostAction()
    {
        $result = array();
        if (!Mage::getSingleton('checkout/cart')->getQuote()->getItemsCount()) {
            $result['valid']   = 0;
            $result['message'] = 'YOUR ERROR Message';
            
        }
        
        $couponCode = (string) $this->getRequest()->getParam('coupon_code');
        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }
        $oldCouponCode = Mage::getSingleton('checkout/cart')->getQuote()->getCouponCode();
        
        if (!strlen($couponCode) && !strlen($oldCouponCode)) {
            
        }

        try {
            $codeLength        = strlen($couponCode);
            $isCodeLengthValid = $codeLength && $codeLength <= Mage_Checkout_Helper_Cart::COUPON_CODE_MAX_LENGTH;
            
            Mage::getSingleton('checkout/cart')->getQuote()->getShippingAddress()->setCollectShippingRates(true);
            Mage::getSingleton('checkout/cart')->getQuote()->setCouponCode($isCodeLengthValid ? $couponCode : '')->collectTotals()->save();
            if ($codeLength) {
                if ($isCodeLengthValid && $couponCode == Mage::getSingleton('checkout/cart')->getQuote()->getCouponCode()) {
                    Mage::getSingleton('core/session')->setMsg($this->__('Coupon code "%s" was applied.', Mage::helper('core')->escapeHtml($couponCode)));
                    $result['valid']   = 'success';
                    $result['message'] = $this->__('Coupon code "%s" was applied.', Mage::helper('core')->escapeHtml($couponCode));
                } else {
                    $result['valid']   = 'failed';
                    $result['message'] = $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->escapeHtml($couponCode));
                    
                }
            } else {

                $result['valid']   = 'remove';
                $result['message'] = $this->__('Coupon code was canceled.');
                Mage::getSingleton('core/session')->setMsg($this->__('Coupon code was canceled.'));
                $this->_redirect('checkout/cart/index/msg/success/');
            }
            
        }
        catch (Mage_Core_Exception $e) {
            $e->getMessage();
            $result['valid']   = 'failed';
            $result['message'] = $this->__('Cannot apply the coupon code');
            
        }
        catch (Exception $e) {
            $result['valid']   = 'failed';
            $result['message'] = $this->__('Cannot apply the coupon code.');
        
            Mage::logException($e);
        
        }
        
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}
