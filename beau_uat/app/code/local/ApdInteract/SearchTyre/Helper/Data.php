<?php

class ApdInteract_SearchTyre_Helper_Data extends Mage_Core_Helper_Abstract {

    public function connect($request) {
        $CollectionRequest = Mage::getModel('searchtyre/searchtyre')->fetchFromApi($request);
        return $CollectionRequest;
    }

    public function getToken() {
        $token = Mage::getModel('searchtyre/searchtyre')->getToken();
        return $token;
    }

    public function getAvailableOrders() {
        return array(
            // attribute name => label to be used
            'name' => $this->__('Name'),
            'price' => $this->__('Price')
        );
    }

    /**
     * Return product collection to displayed by our list block

     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProductCollection() {
        $q = $_POST['type'];
        $s = $_POST['section'];
        $c = $_POST['category'];
        $collection = Mage::getModel('catalog/product')->getCollection();
        #$collection->setStoreId(1)->addCategoryFilter($c);
        /* $_category = Mage::getModel('catalog/category')->load(43);
          $collection = Mage::getModel('catalog/product')
          ->getCollection()
          ->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')
          ->addAttributeToSelect('*')
          ->addCategoryFilter($_category)
          ->addAttributeToSort('name', 'asc'); */
        if ($q == 'tyres') {
            if ($s == 'vehicles') {
                $size = $this->GetPatern($_POST['series-tyres']);
                $collection->addAttributeToFilter('searchfilter', array(
                    'like' => "%$size%"
                ));
                Mage::getSingleton('core/session')->setTitleF('Tyre Size:' . $size . ' Front and Rear');
            } elseif ($s == 'brand') {
                $brand = $_POST['brand'];
                $collection->addAttributeToFilter('brand_value', array(
                    'eq' => $brand
                ));
                Mage::getSingleton('core/session')->setTitleF('Tyre Brand:' . $brand);
            } elseif ($s == 'size') {
                $width = $_POST['width-tyres'];
                $profile = $_POST['profile-tyres'];
                $diameter = $_POST['diameter-tyres'];
                $size = $this->GetTyrePattern($width, $profile, $diameter);
                Mage::getSingleton('core/session')->setTitleF('Tyre Size:' . $size . ' Front and Rear');
                $collection->addAttributeToFilter('searchfilter', array(
                    'like' => "%$size%"
                ));
            }
        } else {
            if ($s == 'vehicles') {
                $size = $this->GetPatern($_POST['series-wheels']);
            }
        }
        echo $collection->getSelect()->__toString();
        return $collection;
    }

    public function getMakes() {
        $make = array(
            "140" => "Abarth",
            "7" => "Alfa Romeo",
            "8",
            "Aston Martin",
            '9' => 'Audi',
            '10' => 'Bentley',
            "11" => 'BMW',
            "139" => "Chery",
            "14" => "Chrysler",
            "15" => "Citroen",
            "16" => "Daewoo",
            "17" => "Daihatsu",
            "18" => "Dodge",
            "19" => "Ferrari",
            "20" => "Fiat",
            "21" => "Ford",
            "97" => "FPV",
            "134" => "Great Wall",
            "23" => "Holden",
            "29" => "Honda",
            "110" => "HSV",
            "127" => "Hummer",
            "30" => "Hyundai",
            "141" => "Infiniti",
            "75" => "Isuzu Ute",
            "30" => "Hyundai",
            "141" => "Infiniti",
            "31" => "Jaguar",
            "74" => "Jeep",
            "32" => "Kia",
            "33" => "Lamborghini",
            "76" => "Land Rover",
            "35" => "Lexus",
            "36" => "Lotus",
            "37" => "Maserati",
            "38" => "Mazda",
            "39" => "Mercedes-Benz",
            "113" => "Mini",
            "40" => "Mitsubishi",
            "43" => "Nissan",
            "142" => "Opel",
            "44" => "Peugeot",
            "45" => "Porsche",
            "46" => "Proton",
            "47" => "Renault",
            "48" => "Rolls-Royce",
            "50" => "SAAB",
            "51" => "SEAT",
            "117" => "Skoda",
            "112" => "Smart",
            "53" => "Ssangyong",
            "54" => "Subaru",
            "55" => "Suzuki",
            "147" => "Tesla",
            "56" => "Toyota",
            "57" => "Volkswagen",
            "58" => "Volvo"
        );
        asort($make);
        return $make;
    }

    public function getWheelBrandId($brandname) {
        $cacheId = 'wheel_brands_id_' . $brandname;
        /* if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
          $brand = unserialize($data);
          #Mage::log('FROM CACHE', null, 'makemodel.log');
          } else { */
        $connect = $this->connect('/wheels/brands');
        $connect->setParameterGet('q', $brandname);
        $modelResponse = $connect->request();
        $brand = $modelResponse->getBody();
        $response = json_decode($brand);
        $brand_id = $response->Items[0]->Id;
        #Mage::app()->getCache()->save(serialize($brand_id), $cacheId);
        //}
        #Mage::log($brand_id.'-'.$brandname, null, 'brand-tyres.log');
        return $brand_id;
    }

    public function getTyreBrandId($brandname) {
        $cacheId = 'wheel_brands_id_' . $brandname;
        /* if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
          $brand = unserialize($data);
          #Mage::log('FROM CACHE', null, 'makemodel.log');
          } else { */
        $connect = $this->connect('/tyres/brands');
        $connect->setParameterGet('q', $brandname);
        $modelResponse = $connect->request();
        $brand = $modelResponse->getBody();
        $response = json_decode($brand);
        $brand_id = $response->Items[0]->Id;
        #Mage::app()->getCache()->save(serialize($brand_id), $cacheId);
        //}
        #Mage::log($brand_id.'-'.$brandname, null, 'brand.log');
        return $brand_id;
    }

    public function getWheelFinishId($finish) {
        /* 	$cacheId = 'wheel_finishes_id_' . $finish;
          if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
          $finish = unserialize($data);
          #Mage::log('FROM CACHE', null, 'makemodel.log');
          } else { */
        $connect = $this->connect('/wheels/wheels/finishes');
        $connect->setParameterGet('q', $finish);
        $modelResponse = $connect->request();
        $finish = $modelResponse->getBody();
        $response = json_decode($brand);
        $finish_id = $response->Items[0]->Id;
        # Mage::app()->getCache()->save(serialize($finish_id), $cacheId);
        // }
        return $finish_id;
    }

    public function getWheelStyleId($style) {
        /* $cacheId = 'wheel_styles_id_' . $style;
          if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
          $style = unserialize($data);
          #Mage::log('FROM CACHE', null, 'makemodel.log');
          } else { */
        $connect = $this->connect('/wheels/wheels/styles');
        $connect->setParameterGet('q', $style);
        $modelResponse = $connect->request();
        $styles = $modelResponse->getBody();
        $response = json_decode($styles);
        $style_id = $response->Items[0]->Id;
        #Mage::app()->getCache()->save(serialize($style_id), $cacheId);
        // }
        #Mage::log($style_id.'-'.$style, null, 'style.log');
        return $style_id;
    }

    private function _getImageUrls($images_object, $filter = 'HighRes') {
        $images = null;

        for ($j = 0; $j < count($images_object->Images); $j++) {
            if (isset($images_object->Images[$j]->Url) && strpos($images_object->Images[$j]->Type, $filter) !== false) { // HighRes
                $images[] = $images_object->Images[$j]->Url;
            }
        }
        return $images;
    }

    private function _lowercaseAndNoSpaces($string) {
        return strtolower(str_replace(' ', '', $string));
    }

    private function _fuzzyCompare($string1, $string2) {
        $string1 = $this->_lowercaseAndNoSpaces($string1);
        $string2 = $this->_lowercaseAndNoSpaces($string2);
        return ($string1 == $string2);
    }
       
    private function _stringsAreSimilar($string1, $string2) {
        $string1 = $this->_lowercaseAndNoSpaces($string1);
        $string2 = $this->_lowercaseAndNoSpaces($string2);        
        return (
            strpos($string1, $string2) !== false || 
            strpos($string2, $string1) !== false
            );
    }

    private function _getApiImages($connect, $product_name, $filter = 'HighRes') {
        $modelResponse = $connect->request();
        $image = $modelResponse->getBody();
        $response = json_decode($image);
        if (isset($response->Items[0])) {
            $array = $response->Items[0];
            $total = $response->Total;
            for ($i = 0; $i < $total; $i++) {
                if ($this->_fuzzyCompare($response->Items[$i]->Name, $product_name)) {
                    $images = $this->_getImageUrls($response->Items[$i], $filter);
                    break;
                }
            }
        } else {
            $images = array();
        }
        return $images;
    }

    public function getWheelImage($style_id, $brand_id, $product_name) {
        // $cacheId = 'wheel_images_' . $style_id . '_' . $finish_id . '_' . $brand_id;
        /* if (false !== ($data = Mage::app()->getCache()->load($cacheId))) {
          $image = unserialize($data);
          #Mage::log('FROM CACHE', null, 'makemodel.log');
          } else { */
        // To save:
        // /* Mage::app()->getCache()->save(serialize($images), $cacheId);

        $connect = $this->connect('/wheels/wheels');
        $connect->setParameterGet('wheelstyleids', $style_id);
        $connect->setParameterGet('wheelbrandids', $brand_id);
        return $this->_getApiImages($connect, $product_name, 'HighRes');
    }

    public function getTyreImage($brand_id, $product_name) {
        $connect = $this->connect('/tyres/patterns');
        $connect->setParameterGet('q', $product_name);
        $connect->setParameterGet('tyrebrandid', $brand_id);
        return $this->_getApiImages($connect, $product_name, 'Large');
    }

    public function GetPatern($id) {
        $response = $this->GetPaternRaw($id);
        $pattern = $response->Items[0]->TyreFitments[0]->FrontTyre->Size->Description . ',' . $response->Items[0]->TyreFitments[0]->RearTyre->Size->Description;

        return $pattern;
    }

    public function GetPaternRaw($id) {
        $model = Mage::getModel('searchtyre/searchtyre');
        $r = '/vehicles/' . $id . '/tyres/patterns';
        $t = null;
        $test = $model->connect($r, $t);
        $pattern = $model->_fetchDataFromRemoteRaw();
        $response = json_decode($pattern);

        return $response;
    }

    public function getCacheTyreData($id) {        
        $model = Mage::getModel('searchtyre/searchtyre');
        $r = '/vehicles/vehicles/details/' . $id;
        $t = null;
        $test = $model->connect($r, $t);
        $pattern = $model->_fetchDataFromRemoteRaw();
        $response = json_decode($pattern);
        return $response;
    }
    
    public function getVehicleWheelOptions($parent_product, $vehicle_id = false) {
        // Given a vehicle id and the parent product
        // return the suitable child products (ie sizes)
        if (!$vehicle_id) {
            $vehicle_id = Mage::getSingleton('core/session')->getSeriesF(); 
        }
        // But maybe that's null too.
        if (!$vehicle_id) {
            return false;
        }
        
        $response = $this->getVehicleWheels($vehicle_id);
        foreach ($response->Items as $item) {
            if ($this->_fuzzyCompare($item->Name, $parent_product)) { 
            // or $item->Id ... 703 == Assurance Triplemax ... maybe this is SKU?
            // see also _fuzzyCompare($s1, $s2);
                
                return $item->WheelFitments;
            }
        }
        
        // No match? The last size is probably fine.
        return $item->WheelFitments;
        
    }
    
    public function getVehicleTyreOptions($parent_product, $vehicle_id = false) {
        // Given a vehicle id and the parent product
        // return the suitable child products (ie sizes)
        if (!$vehicle_id) {
            $vehicle_id = Mage::getSingleton('core/session')->getSeriesF(); 
        }
        // But maybe that's null too.
        if (!$vehicle_id) {
            return false;
        }
        
        $response = $this->GetPaternRaw($vehicle_id);
        foreach ($response->Items as $item) {
            if ($this->_fuzzyCompare($item->Name, $parent_product)) { // or $item->Id ... 703 == Assurance Triplemax ... maybe this is SKU?
                return $item->TyreFitments;
            }
        }
        
        // No match? The last size is probably fine.
        return $item->TyreFitments;
    }

    public function getVehicleWheels($id) {

        $model = Mage::getModel('searchtyre/searchtyre');
        $r = '/vehicles/' . $id . '/wheels?perpage=5000';
        $t = null;
        $test = $model->connect($r, $t);
        $pattern = $model->_fetchDataFromRemoteRaw();
        $response = json_decode($pattern);

        return $response;
    }
    
    public function checkTypeofProduct($productIdtoCheck) {

        $QuoteCustomerSegment = "";
        $productTocheck = Mage::getModel('catalog/product')->load($productIdtoCheck);
        $productTocheckSegment = $productTocheck->getAttributeText('customer_segment');
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $cartItems = $quote->getAllVisibleItems();
        foreach ($cartItems as $item) {
            $productId = $item->getProductId();
            $_product = Mage::getModel('catalog/product')->load($productId);
            $QuoteCustomerSegment = $_product->getAttributeText('customer_segment'); //get first Item
            break;
        }

        return ($QuoteCustomerSegment =="" || $productTocheckSegment == $QuoteCustomerSegment) ? true : false;
    }


    /**Check Customer Segment
     * @return array
     */
    public function checkCustomerSegment(){

        $label_required = array('0' => '', '1'=>'*');
        $class_required = array('0' =>'', '1'=>'required-entry');
        $is_consumer = false;

        $cart = Mage::getModel('checkout/cart')->getQuote();
        foreach ($cart->getAllVisibleItems() as $item) {
            $productId = $item->getProductId();
            $product = Mage::getModel('catalog/product')->load($productId);
            if($product->getCustomerSegment() == 250){
                $is_consumer = true;
                break;
            }
        }
        Mage::getSingleton("core/session")->setIsConsumer($is_consumer);
        return array('label' => $label_required[$is_consumer],'class'=>$class_required[$is_consumer],'is_consumer'=>$is_consumer);
    }
    
     public function checkIfApplicable() {
         $flag = false;
         if(Mage::getSingleton('customer/session')->isLoggedIn()):            
             if($this->getAllVehicleByUser()->count()>0):
                 $flag = true;
             endif;
         endif;
             
         
        return $flag;        
    }
    
    /*
     * Get all vehicle per customer
     *
     * @param 
     * @return object
     */
    public function getAllVehicleByUser(){

            $customerData = Mage::getSingleton('customer/session')->getCustomer();
            $collection = Mage::getModel('apdinteract_vehicle/vehicle')->getCollection()
                ->addFieldToSelect('*')
                ->addFieldToFilter('details',array('neq'=>''))
                ->addFieldToFilter('customer_id',$customerData->getId())
                ->addFieldToFilter('website_id',$customerData->getWebsiteId());

            return $collection;
        

    }


    /**
     * Send Vehicle Logic API error email
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function sendEmail($subject = "", $message = "", $email = "") {

        if(empty($email) || empty($subject) || empty($message)){
            return;
        }

        $mail = Mage::getModel('core/email');
        $mail->setSubject($subject);
        $mail->setBody($message);
        $mail->setFromName(Mage::getStoreConfig('trans_email/ident_general/name', 0));
        $mail->setFromEmail(Mage::getStoreConfig('trans_email/ident_general/email', 0));
        $mail->setType('html');

        try {
            $mail->setToEmail($email);
            $mail->send();
        } catch (Exception $e) {
            $this->log($e->getMessage());
        }
    }

    /**
     * Check if API error email trigger based on following condition
     * Send 1 email per hour.
     */
    public function canSendApiErrorEmail() {

        $time = $this->getEmailTriggerTime();
        if(!empty($time)){
            $currentTIme = time();
            $emailIntervalTime = $this->getEmailIntervalTime();
            $diff = abs( $currentTIme - $time);
            if($diff <= $emailIntervalTime){
                return false;
            }
        }

        return true;
    }

    /**
     * Get Email Interval Time in Seconds
     * @return int
     */
    public function getEmailIntervalTime() {

        $timeInSeconds = 3600;
        $emailIntervalTime = Mage::getStoreConfig('apimonitoring/vlapi/email_interval_time');
        if(!empty($emailIntervalTime) || is_numeric($emailIntervalTime)){
            $timeInSeconds = $emailIntervalTime * 60;
        }

        return $timeInSeconds;

    }


    /**
     * Get Email Last trigger time
     * Used direct query in DB because if use getConfig fucntion then its return value from cache.
     *
     * @return mixed
     *
     */
    public function getEmailTriggerTime() {

        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $query = "SELECT value FROM core_config_data WHERE path = 'apimonitoring/vlapi/email_trigger_time'";
        $time  = $connection->fetchOne($query);
        return $time;

    }

    /**
     * Set Email trigger time
     *
     * @return mixed
     *
     */
    public function setEmailTriggerTime() {

        Mage::getConfig()->saveConfig('apimonitoring/vlapi/email_trigger_time', time());
    }

}
