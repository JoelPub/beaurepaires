<?php

class ApdInteract_SearchTyre_IndexController extends Mage_Core_Controller_Front_Action {

    public function IndexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _getSession() {
        return Mage::getSingleton('checkout/session');
    }

    public function GettokenAction() {

        $token = Mage::getModel('searchtyre/searchtyre')->getToken();
        echo $token;
    }

    public function GetmakeAction() {


        $cacheId = 'make';
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $make = unserialize($data);
            #Mage::log('FROM CACHE', null, 'make.log');
        } else {

            $connect = Mage::helper('searchtyre')->connect('vehicles/makes');

            $makeCollectionResponse = $connect->request();
            $make = $makeCollectionResponse->getBody();
            Mage::app()->getCache()->save(serialize($make), $cacheId);
            #Mage::log('NEW DATA', null, 'make.log');
        }


        echo $make;
    }

    public function GetmakedetailsAction() {

        $id = $this->getRequest()->getParam('id');
        $cacheId = 'makedetail_' . $id;
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $makeDetails = unserialize($data);
            #Mage::log('FROM CACHE', null, 'makedetails.log');
        } else {

            $connect = Mage::helper('searchtyre')->connect('vehicles/makes/' . $id);
            $makeReponse = $connect->request();
            $makeDetails = $makeReponse->getBody();

            Mage::app()->getCache()->save(serialize($makeDetails), $cacheId);
            #Mage::log('NEW DATA', null, 'makedetails.log');
        }

        echo $makeDetails;
    }

    public function GetmodelAction() {

        $id = $this->getRequest()->getParam('id');
        $year = $this->getRequest()->getParam('year');
        $cacheId = 'model_' . $id . '_' . $year;
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $models = unserialize($data);
            #Mage::log('FROM CACHE', null, 'modeldetails.log');
        } else {

            $connect = Mage::helper('searchtyre')->connect('vehicles/models');
            $connect->setParameterGet('vehiclemakeids', $id);
            $connect->setParameterGet('year', $year);

            $modelResponse = $connect->request();
            $models = $modelResponse->getBody();

            Mage::app()->getCache()->save(serialize($models), $cacheId);
            #Mage::log('NEW DATA', null, 'modeldetails.log');
        }

        echo $models;
    }

    public function GetSeriesAction() {

        $id = $this->getRequest()->getParam('id');
        $year = $this->getRequest()->getParam('year');
        $cacheId = 'series_' . $id . '_' . $year;
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $series = unserialize($data);
            #Mage::log('FROM CACHE', null, 'series.log');
        } else {

            $connect = Mage::helper('searchtyre')->connect('vehicles/vehicles');
            $connect->setParameterGet('modelid', $id);
            $connect->setParameterGet('year', $year);

            $seriesResponse = $connect->request();
            $series = $seriesResponse->getBody();

            Mage::app()->getCache()->save(serialize($series), $cacheId);
            #Mage::log('NEW DATA', null, 'series.log');
        }

        echo $series;
    }

    public function GetWidthAction() {

        $cacheId = 'width';
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $width = unserialize($data);
            #Mage::log('FROM CACHE', null, 'width.log');
        } else {

            $connect = Mage::helper('searchtyre')->connect('tyres/sizes/sectionwidths');

            $widthResponse = $connect->request();
            $width = $widthResponse->getBody();

            Mage::app()->getCache()->save(serialize($width), $cacheId);
            #Mage::log('NEW DATA', null, 'width.log');
        }

        echo $width;
    }

    public function GetAspectratioAction() {

        $id = $this->getRequest()->getParam('id');
        $cacheId = 'ratio_' . $id;
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $ratio = unserialize($data);
            #Mage::log('FROM CACHE', null, 'aspect.log');
        } else {

            $connect = Mage::helper('searchtyre')->connect('tyres/sizes/aspectRatios');
            $connect->setParameterGet('sectionwidth', $id);

            $ratioResponse = $connect->request();
            $ratio = $ratioResponse->getBody();

            Mage::app()->getCache()->save(serialize($ratio), $cacheId);
            #Mage::log('NEW DATA', null, 'aspect.log');
        }

        echo $ratio;
    }

    public function GetRimDiameterAction() {

        $id = $this->getRequest()->getParam('id');
        $ratio = $this->getRequest()->getParam('ratio');
        $cacheId = 'rim_diameter_' . $id . '_' . $ratio;
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $rim = unserialize($data);
            #Mage::log('FROM CACHE', null, 'width.log');
        } else {

            $connect = Mage::helper('searchtyre')->connect('tyres/sizes/rimDiameters');
            $connect->setParameterGet('sectionwidth', $id);
            $connect->setParameterGet('ratio', $ratio);

            $ratioResponse = $connect->request();
            $rim = $ratioResponse->getBody();

            Mage::app()->getCache()->save(serialize($rim), $cacheId);
            #Mage::log('NEW DATA', null, 'width.log');
        }

        echo $rim;
    }

    public function GetTyreBrandsAction() {

        $cacheId = 'tyre_brand';
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $brands = unserialize($data);
            #Mage::log('FROM CACHE', null, 'tyre_brand.log');
        } else {

            $connect = Mage::helper('searchtyre')->connect('tyres/brands');
            $brandsCollectionResponse = $connect->request();
            $brands = $brandsCollectionResponse->getBody();

            Mage::app()->getCache()->save(serialize($brands), $cacheId);
            #Mage::log('NEW DATA', null, 'tyre_brand.log');
        }

        echo $brands;
    }

    public function GetWheelsBrandsAction() {

        $cacheId = 'wheel_brands';
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $wheels = unserialize($data);
            #Mage::log('FROM CACHE', null, 'wheels.log');
        } else {

            $connect = Mage::helper('searchtyre')->connect('wheels/brands');

            $wheelsCollectionResponse = $connect->request();
            $wheels = $wheelsCollectionResponse->getBody();

            Mage::app()->getCache()->save(serialize($wheels), $cacheId);
            #Mage::log('NEW DATA', null, 'wheels.log');
        }

        echo $wheels;
    }

    public function GetWheelSizesAction() {
        $model_id = $this->getRequest()->getParam('model');

        $year = $this->getRequest()->getParam('year');
        $cacheId = 'wheel_sizes_' . $model_id . '_' . $year;
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $a = unserialize($data);

            #Mage::log('FROM CACHE', null, 'wheels.log');
        } else {
            $connect = Mage::helper('searchtyre')->connect('vehicles/vehicles');
            $connect->setParameterGet('year', $year);
            $connect->setParameterGet('modelid', $model_id);
            $vehiclesresponse = $connect->request();

            $vehicles = $vehiclesresponse->getBody();

            $v = json_decode($vehicles);
            $a = array();

            foreach ($v->Items as $item) {
                $id = $item->Id;

                $connect = Mage::helper('searchtyre')->connect('vehicles/' . $id . '/wheels/sizes/rimDiameters');

                $wheelSizesCollectionResponse = $connect->request();
                $wheelSizes = json_decode($wheelSizesCollectionResponse->getBody());

                $i = 0;
                for ($i = 0; $i <= count($wheelSizes->Items) - 1; $i++) {

                    if (!in_array($wheelSizes->Items[$i], $a)) {
                        array_push($a, $wheelSizes->Items[$i]);
                    }
                }
            }

            $a = array_unique($a);
            $a = json_encode($a);
            Mage::app()->getCache()->save(serialize($a), $cacheId);
        }


        echo $a;


        #echo $wheelSizes;
    }

    public function GetWheelModelAction() {

        $year = $this->getRequest()->getParam('year');
        $q = $this->getRequest()->getParam('model');
        $cacheId = 'wheel_model_' . $year . '_' . $q;
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $wheelModel = unserialize($data);
            #Mage::log('FROM CACHE', null, 'wheelModel.log');
        } else {

            $connect = Mage::helper('searchtyre')->connect('vehicles/vehicles');
            $connect->setParameterGet('year', $year);
            $connect->setParameterGet('q', $q);

            $ratioResponse = $connect->request();
            $wheelModel = $ratioResponse->getBody();

            Mage::app()->getCache()->save(serialize($wheelModel), $cacheId);
            #Mage::log('NEW DATA', null, 'wheelModel.log');
        }
        echo $wheelModel;
    }

    public function GetMakemodelAction() {




        $q = $this->getRequest()->getParam('id');
        $cacheId = 'make_model_' . $q;
        if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
            $models = unserialize($data);
            #Mage::log('FROM CACHE', null, 'makemodel.log');
        } else {

            $connect = Mage::helper('searchtyre')->connect('vehicles/vehicles');
            $connect->setParameterGet('q', $q);

            $modelResponse = $connect->request();
            $models = $modelResponse->getBody();

            Mage::app()->getCache()->save(serialize($models), $cacheId);
            #Mage::log('NEW DATA', null, 'makemodel.log');
        }

        echo $models;
    }

    public function getSizeAction() {
        $series = $this->getRequest()->getParam('series');
        $series_ar = explode(',', Mage::helper('searchtyre')->GetPatern($series));
        echo 'Front: ' . $series_ar[0] . ', ' . 'Rear: ' . $series_ar[1];
    }

    public function resultAction() {


        $this->loadLayout();
        $this->renderLayout();
    }

    public function loadCartCountAction() {
        if (Mage::helper('checkout/cart')->getSummaryCount() > 0) {
            echo Mage::helper('checkout/cart')->getSummaryCount();
        } else {
            echo '0';
        }
    }

    public function getVehicleWheelsAction() {
        $series = $this->getRequest()->getParam('series');

        $wheels_data = Mage::helper('searchtyre')->getVehicleWheels($series);


        echo 'Front: ' . $wheels_data->Items[0]->WheelFitments[0]->FrontWheel->Size->Description . ', ' . 'Rear: ' . $wheels_data->Items[0]->WheelFitments[0]->RearWheel->Size->Description;
    }

    public function ajax_addcartAction() {
        $id = $this->_getPostedValues('productId');
        $size_id = $this->_getPostedValues('super_attribute');
        $qty = $this->_getPostedValues('qty');

        $product = Mage::getModel('catalog/product')->load($id);

        $params = array(
            'product' => $id,
            'qty' => $qty
        );


        // is this a configurable product?
        if ($product->getData('type_id') == "configurable") {
            // Tyres and wheels will have different "super" attributes
            // ("super" attribute = the attribute used to select the child product from the configurable parent)
            $config_attributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);

            // We assume product has only 1 configurable attribute
            $attribute_id = $config_attributes[0]['attribute_id'];

            $params['super_attribute'] = array(
                $attribute_id => $size_id
            );
            // $params['fprice'] = 999; // does nothing
        }

        try {
            $cart = Mage::getSingleton('checkout/cart');
            $cart->addProduct($product, $params);
            $cart->save();
            Mage::getSingleton('checkout/session')->setCartWasUpdated(true);

            echo $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
        } catch (Exception $e) {
            echo $message = $this->__('%s was not added. Please add required option.', Mage::helper('core')->escapeHtml($product->getName()));
        }
    }

    public function getpriceAction() {

        $product_id = $this->getRequest()->getParam('product_id');

        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $product_id);
        if ($product) {
            echo round($product->getFinalPrice(), 2);
        } else {
            echo 0.00;
        }
    }

    public function getConfigurableAction() {
        $model = Mage::getModel('searchtyre/searchtyre');
        $product_id = $this->getRequest()->getParam('product_id');
        $size = $this->getRequest()->getParam('size');
        $current_url = $this->getRequest()->getParam('c_url');

        $sizes = array();
        $size_ar = str_replace("-", "/", $size);
        if (strlen($size) > 2) {
            $sizes = explode(" ", $size_ar);
        } else {
            $sizes[] = $size;
        }




        $values = $model->getConfigurableOptions($product_id, $sizes, $current_url);
        $values = json_encode($values);
        echo $values;
    }

    public function addcartAction() {
        $id = $this->_getPostedValues('productId');
        $size_id = $this->_getPostedValues('super_attribute');
        $qty = $this->_getPostedValues('qty');

        $sameSegment = Mage::helper('searchtyre')->checkTypeofProduct($id);

        if ($sameSegment) {

            $product = Mage::getModel('catalog/product')->load($id);

            $params = array(
                'product' => $id,
                'qty' => $qty
            );


            // is this a configurable product?
            if ($product->getData('type_id') == "configurable") {
                // Tyres and wheels will have different "super" attributes
                // ("super" attribute = the attribute used to select the child product from the configurable parent)
                $config_attributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);

                // We assume product has only 1 configurable attribute
                $attribute_id = $config_attributes[0]['attribute_id'];

                $params['super_attribute'] = array(
                    $attribute_id => $size_id
                );
                // $params['fprice'] = 999; // does nothing
            }

            try {
                $cart = Mage::getSingleton('checkout/cart');
                $cart->addProduct($product, $params);
                $cart->save();
                Mage::getSingleton('checkout/session')->setCartWasUpdated(true);

                $message = $this->__('%s was added to your shopping cart.', Mage::helper('core')->escapeHtml($product->getName()));
                $this->_getSession()->addSuccess($message);

                $this->_redirect('checkout/cart/');
            } catch (Exception $e) {
                $message = $this->__('%s was not added. Please add required option.', Mage::helper('core')->escapeHtml($product->getName()));
                $this->_getSession()->addError($message);
                $this->_redirectReferer();
            }
        } else {
            $message = $this->__('Please choose either Consumer Products or Commercial Products.<br>You cannot proceed with both Categories of Items in the Cart');
            $this->_getSession()->addError($message);
            $this->_redirectReferer();
        }
    }

    private function _getPostedValues($field) {
        $value = Mage::app()->getRequest()->getPost($field);
        return intval($value);
    }

    public function vehicle_detalsAction() {

        $post = $this->getRequest()->getPost();
        $error = false;

        if (!Zend_Validate::is($post['email'], 'EmailAddress')) {
            $error = true;
        }
        if (!isset($post['rego']) || $post['rego'] == '') {
            $error = true;
        }

        if (!$error) {

            $vehicleCollection = Mage::helper('apdinteract_vehicle')->getVehicleCollection($post['email'], $post['rego']);
            $return = "";
            if ((bool) count($vehicleCollection)) {
                $return = array('error' => 0, 'value' => $vehicleCollection[0]['details']);
            } else {
                $return = array('error' => 1, 'value' => "");
            }
        } else {
            $return = array('error' => 1, 'value' => "");
        }

        echo $this->_jsonEncode($return);
    }

    /**
     * Encodes the $result into the JSON format
     *
     * @param array $result
     * @return string
     */
    protected function _jsonEncode($result) {
        return Mage::helper('core')->jsonEncode($result);
    }

    /**
     * Update Cart Ajax
     * @throws Exception
     * @throws Mage_Core_Exception
     */
    public function updatecartAction() {

        $row = 0;
        $product_id = $this->getRequest()->getParam('product_id');
        $qty = $this->getRequest()->getParam('qty');
        $max  = Mage::getStoreConfig('cataloginventory/item_options/max_sale_qty');

        $cart =  Mage::getSingleton('checkout/cart');
        $quoteItem = $cart->getQuote()->getItemById($product_id);
        
        if($max>0 && $qty>$max) {
            $message = $this->__('Unable to update cart. Maximum of quantity of '.$max.' per item has been reached.' );
            $this->_getSession()->addError($message);
            //$this->_redirect('checkout/cart');
        } else {    
        
            if (!$quoteItem) {
                Mage::throwException($this->__('Quote item is not found.'));
            }
            if ($qty == 0) {
                $cart->removeItem($product_id);
            } else {
                $quoteItem->setQty($qty)->save();
                $row = $quoteItem->getPriceInclTax() * $qty;
                $row = Mage::helper('core')->currency($row, true, false);
            }
            $cart->save();
            }
        echo $row;

    }

    public function browseAction() {

        Mage::getSingleton('core/session')->setVehicleType(true);
        Mage::getSingleton('core/session')->setTyreSize(true);
        Mage::getSingleton('core/session')->setSizeF('');
        Mage::getSingleton('core/session')->setSize1F('');
        Mage::getSingleton('core/session')->setSeriesF('');
        Mage::getSingleton('core/session')->setSizeWF('');
        Mage::getSingleton('core/session')->setTMakeF('');
        Mage::getSingleton('core/session')->setTModelF('');
        Mage::getSingleton('core/session')->setTYearF('');
        Mage::getSingleton('core/session')->setTSeriesNameF('');
        Mage::getSingleton('core/session')->setTMakeModelF('');
        Mage::getSingleton('core/session')->setSizeF('');
        Mage::getSingleton('core/session')->setWSeriesF(''); 
        Mage::getSingleton('core/session')->setTMakeF('');
        Mage::getSingleton('core/session')->setTModelF('');
        Mage::getSingleton('core/session')->setTYearF('');
        Mage::getSingleton('core/session')->setTSeriesNameF('');
        Mage::getSingleton('core/session')->setTMakeModelF('');

        $uris = explode('/',Mage::app()->getRequest()->getRequestUri());
        $this->_redirectUrl('/'.$uris[5]);
    }

    public function clearAction() {

        Mage::getSingleton('core/session')->unsVehicleType();
        Mage::getSingleton('core/session')->unsTyreSize();
        Mage::getSingleton('core/session')->setSizeF('');
        Mage::getSingleton('core/session')->setSize1F('');
        Mage::getSingleton('core/session')->setSeriesF('');
        Mage::getSingleton('core/session')->setSizeWF('');
        Mage::getSingleton('core/session')->setTMakeF('');
        Mage::getSingleton('core/session')->setTModelF('');
        Mage::getSingleton('core/session')->setTYearF('');
        Mage::getSingleton('core/session')->setTSeriesNameF('');
        Mage::getSingleton('core/session')->setTMakeModelF('');
        Mage::getSingleton('core/session')->setSizeF('');
        Mage::getSingleton('core/session')->setWSeriesF('');
        Mage::getSingleton('core/session')->setTMakeF('');
        Mage::getSingleton('core/session')->setTModelF('');
        Mage::getSingleton('core/session')->setTYearF('');
        Mage::getSingleton('core/session')->setTSeriesNameF('');
        Mage::getSingleton('core/session')->setTMakeModelF('');

        $uris = explode('/',Mage::app()->getRequest()->getRequestUri());
        $this->_redirectUrl('/'.$uris[5]);  
    }

}
