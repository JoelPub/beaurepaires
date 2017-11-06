<?php

class IWD_StoreLocator_Helper_Data extends Mage_Core_Helper_Abstract {

    const XML_PATH_HIDE_RADIUS = 'storelocator/global/hide_radius';
    const XML_PATH_METRIC = 'storelocator/gmaps/metric';
    const XML_PATH_RADIUS = 'storelocator/gmaps/radius';
    const XML_PATH_LIMIT = 'storelocator/global/limit_result';
    const XML_PATH_SEARCH_LOAD = 'storelocator/global/search_load';
    const XML_PATH_FULL_DISABLED = 'storelocator/global/full';
    const XML_PATH_PAGINATION = 'storelocator/global/pagination';

    public function prepareRegion($controller) {
        $countryCode = $controller->getRequest()->getParam('country_id', false);

        if (!$countryCode) {
            $response = array('error' => true, 'action' => 'clear');
            $controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        } else {


            $states = Mage::getModel('directory/region_api')->items($countryCode);

            if (count($states) == 0 || !$states) {
                $response = array('error' => true, 'action' => 'showRegion');
                $controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
                return;
            }

            //get all states
            $collection = Mage::getModel('storelocator/stores')->getCollection()->addFieldToFilter('country_id', $countryCode)->addFieldToFilter('is_active', array('eq' => '1'));

            foreach ($collection as $item) {
                $stateIds[] = $item->getRegionId();
            }

            $result = array();
            foreach ($states as $state) {
                if (in_array($state['region_id'], $stateIds)) {
                    $result[] = $state;
                }
            }

            $response = array('error' => false, 'action' => 'updateStates', 'result' => $result);
            $controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
            return;
        }
    }

    public function convertCountry($code) {
        $countryList = Mage::getResourceModel('directory/country_collection')
                ->loadData()
                ->toOptionArray(false);
        foreach ($countryList as $item) {
            if ($item['value'] == $code) {
                return $item['label'];
            }
        }
        return $code;
    }

    private function _getRadiusEarth() {
        // in either Miles or Km, depending on config
        $option = Mage::getStoreConfig(self::XML_PATH_METRIC);
        $radiusEarth = 6371; // if ($option == 1){

        if ($option == 2) {
            $radiusEarth = 3959;
        }
        return $radiusEarth;
    }

    public function getStoresByLatLng($lat, $lng, $area) {
        
        $lat = floatval($lat);
        $lng = floatval($lng);
        
            

        if ($lat == 0 || $lng == 0) {
            return false;
        }

        $radiusEarth = $this->_getRadiusEarth();
        $radius = 500; // within 500km // Mage::getStoreConfig(self::XML_PATH_RADIUS);
        $collection = Mage::getModel('storelocator/stores')->getCollection()
                ->addFieldToFilter('is_active', array('eq' => '1'))
                ->addFieldToFilter('latitude', array('neq' => '0'))
                ->addFieldToFilter('longitude', array('neq' => '0'))
                ->addFieldToSelect('entity_id')
                ->addFieldToSelect('title'); // just show entity id and title
        
        if($area!='cart')
        $collection->addFieldToFilter('exclude_from_cart', array('eq' => '0'));
        
                
        $collection->getSelect()->joinLeft(array('apdinteract_store_category' => 'apdinteract_store_category'), 'apdinteract_store_category.id = main_table.type_id', array('apdinteract_store_category.icon'));
        $collection->getSelect()->columns('ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( ' . $lat . ' ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( ' . $lat . ' )) * COS( RADIANS( `longitude` ) - RADIANS( ' . $lng . ' )) ) * ' . $radiusEarth . ' AS distance');
        //$collection->getSelect()->where('ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( ' . $lat . ' ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( ' . $lat . ' )) * COS( RADIANS( `longitude` ) - RADIANS( ' . $lng . ' )) ) * ' . $radiusEarth . ' < ' . $radius);
        $distanceField = true;               
        $collection->getSelect()->order('distance ASC');
        $collection->addStoreFilter(Mage::app()->getStore());
        $collection->setPageSize(5)->setCurPage(1); // Get closest 5 stores                   
        return $collection;
    }

    public function prepareResultSearch($controller,$campaign=false) {
        $raduisEarth = $this->_getRadiusEarth();

        $defaultRadius = Mage::getStoreConfig(self::XML_PATH_RADIUS);

        $data = $controller->getRequest()->getParams();

        $page = $controller->getRequest()->getParam('page', 1);

        $store = Mage::app()->getStore()->getId();



        $collection = Mage::getModel('storelocator/stores')->getCollection()
                ->addFieldToFilter('is_active', array('eq' => '1'))
                ->addFieldToFilter('latitude', array('neq' => '0'))
                ->addFieldToFilter('longitude', array('neq' => '0'));

        if (isset($data['filters'])) :
            foreach ($data['filters'] as $filter):
                $collection->addFieldToFilter($filter, array('eq' => '1'));
            endforeach;
        endif;

        //check current
        if ($data['latitude'] == 'null') {
            $data['latitude'] = 1;
        }

        if ($data['latitude'] == 'null') {
            $data['longitude'] = 1;
        }



        if (isset($data['country']) && !empty($data['country']) && empty($data['address'])) {
            $country = $this->convertCountry($data['country']);
            $collection->addFieldToFilter('country_id', array('eq' => $data['country']));
        }



        if (!empty($data['address']) || !empty($data['country'])) {

            // See if we've got a lat lng already
            if ($data['latitude'] < 2 || $data['longitude'] < 2) {

                if (isset($data['country']) && !empty($data['country'])) {
                    $country = $this->convertCountry($data['country']);
                    $response = $this->getCoordinate($data['address'] . ', ' . $country);
                } else {
                    $response = $this->getCoordinate($data['address']);
                }

                if ($response && $response['status'] == 'OK') {
                    $geometry = $response['results'][0]['geometry']['location'];

                    $latitude = $geometry['lat'];
                    $longitude = $geometry['lng'];
                }
            }

            if (!isset($data['radius']) || empty($data['radius'])) {
                $data['radius'] = $defaultRadius;
            }
        }


        if (empty($data['address']) && empty($data['country'])) {
            if (isset($data['latitude']) && !empty($data['latitude']) && $data['latitude'] != 'null') {
                $latitude = $data['latitude'];
            }

            if (isset($data['longitude']) && !empty($data['longitude']) && $data['longitude'] != 'null') {
                $longitude = $data['longitude'];
                Mage::register('storelocator_address', $this->getAddressByCoordinate($latitude, $longitude));
            }


            if (!isset($data['radius']) || empty($data['radius'])) {
                $data['radius'] = $defaultRadius;
            }
        }



        if (isset($data['current'])) {
            $latitude = $data['latitude'];
            $longitude = $data['longitude'];

            //if empty radius set default to 25
            if (!isset($data['radius']) || empty($data['radius'])) {
                $data['radius'] = $defaultRadius;
            }
            Mage::unregister('storelocator_address');
            if (empty($data['address'])) {
                $data['address'] = $this->getAddressByCoordinate($latitude, $longitude);
            }
            Mage::register('storelocator_address', $data['address']);
        }
        $distanceField = false;
        if (isset($latitude) && isset($longitude)) {
            if (isset($data['radius']) && !empty($data['radius'])) {
                $radius = (int) $data['radius'];
            } else {
                $radius = $defaultRadius;
            }


            /*if($campaign) { //disabled for testing purposes
               $radius = 500;   
               $collection->getSelect()->where('ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( ' . $latitude . ' ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( ' . $latitude . ' )) * COS( RADIANS( `longitude` ) - RADIANS( ' . $longitude . ' )) ) * ' . $raduisEarth . ' < ' . $radius);    
            } else*/
                $collection->getSelect()->columns('ACOS( SIN( RADIANS( `latitude` ) ) * SIN( RADIANS( ' . $latitude . ' ) ) + COS( RADIANS( `latitude` ) ) * COS( RADIANS( ' . $latitude . ' )) * COS( RADIANS( `longitude` ) - RADIANS( ' . $longitude . ' )) ) * ' . $raduisEarth . ' AS distance');
            
            $distanceField = true;
        }



        $collection->getSelect()->joinLeft(array('apdinteract_store_category' => 'apdinteract_store_category'), 'apdinteract_store_category.id = main_table.type_id', array('apdinteract_store_category.icon as map_icon'));

        //if(!$campaign)     {
            $collection->getSelect()->order($this->_getOrderField($distanceField));
        //}
                

        $collection->addStoreFilter(Mage::app()->getStore());

        $limit = Mage::getStoreConfig(self::XML_PATH_LIMIT);

        if ($campaign) { //disable for testing purposes
            $collection->getSelect()->limit(10);
        } else {
            if ($limit > 0) {

                $collection->setPageSize($limit)
                        ->setCurPage($page);
            }
        }



        $lastPage = $collection->getLastPageNumber();
        //Mage::log($collection->getSelect()->__toString(), null, 'query.log');

        if ($page >= $lastPage) {
            $stopLoad = true;
        } else {
            $stopLoad = false;
        }


        $result = $collection->toArray();

        if ($page > 1) {
            $pagination = true;
        } else {
            $pagination = false;
        }


        //CHECK STOP LOAD IF SETTINGS ENABLED
        $usePagination = Mage::getStoreConfig(self::XML_PATH_PAGINATION);

        if (!$usePagination) {
            $stopLoad = true;
            $pagination = false;
        }


        $blockResultBar = Mage::app()->getLayout()->createBlock('storelocator/search_result')
                        ->setData('page', $page + 1)
                        ->setData('load', $pagination)
                        ->setTemplate('storelocator/result.phtml')->setCollection($collection);

        $blockResultBar = $blockResultBar->toHtml();


        foreach ($result['items'] as &$item) {
            if (isset($data['address'])) {
                $block = Mage::app()->getLayout()->createBlock('storelocator/search_result')->setTemplate('storelocator/item.phtml')->setItem($item)->setAddress(urlencode($data['address']));
                $item['content'] = $block->toHtml();
            }
        }
        unset($item);


        
        
        $response = array(
            'error' => false,
            'action' => 'viewresult',
            'result' => $blockResultBar,
            'maps' => $result,
            'pagination' => $pagination,
            'stopLoad' => $stopLoad,
            'lat' => isset($latitude) ? $latitude : null,
            'lng' => isset($longitude) ? $longitude : null
        );

        $controller->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
        return;
    }

    private function _getOrderField($distance = false) {
        $order = Mage::getStoreConfig('storelocator/search_result/sort');

        switch ($order) {
            case 1:
                return 'position ASC';
                break;
            case 2:
                return 'title ASC';
                break;
            case 3:
                if ($distance == true) {
                    return 'distance ASC';
                }
                return 'position ASC';
                break;
            default:
                return 'position ASC';
        }
    }

    private function getCoordinate($address) {
        $prepareAddress = trim($address);
        $prepareAddress = str_replace(' ', '+', $address);

        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $prepareAddress . '&sensor=false';
        try {
            $http = new Varien_Http_Adapter_Curl ();
            $config = array(
                'timeout' => 5
            );


            $config ['header'] = false;

            $http->setConfig($config);
            $http->write(Zend_Http_Client::POST, $url, '1.1');

            $response = $http->read();

            if ($response === false) {
                return false;
            }

            $json = $this->parseResponse($response);
        } catch (Exception $e) {
            return false;
        }


        return $json;
    }

    public function getAddressByCoordinate($latitude, $longitude) {
        $key = Mage::getStoreConfig('storelocator/gmaps/serverkey');

        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$key}";

        try {
            $http = new Varien_Http_Adapter_Curl ();
            $config = array(
                'timeout' => 5
            );


            $config ['header'] = false;

            $http->setConfig($config);
            $http->write(Zend_Http_Client::POST, $url, '1.1');

            $response = $http->read();

            if ($response === false) {
                return false;
            }

            $json = $this->parseResponse($response);
        } catch (Exception $e) {
            return false;
        }


        if ($json && $json['status'] == 'OK') {
            $address = $json['results'][0]['formatted_address'];

            return $address;
        }
        return $json;
    }

    private function parseResponse($response) {

        $p = strpos($response, "\r\n\r\n");
        if ($p !== false) {
            $rawHeades = substr($response, 0, $p);
            $response = substr($response, $p + 4);
        }

        $json = json_decode($response, true);

        if (isset($json->error)) {
            throw new Exception($json->message);
        }

        return $json;
    }

    public function getLocatorUrl() {
        return $this->_getUrl($this->getRoute());
    }

    public function getRoute() {
        return Mage::getStoreConfig('storelocator/global/route');
    }

    public function isHideRadius() {
        return Mage::getStoreConfig(self::XML_PATH_HIDE_RADIUS);
    }

    public function isLoadInitialDisabled() {
        return Mage::getStoreConfig(self::XML_PATH_SEARCH_LOAD);
    }

    public function isDisabledFullWidth() {
        return Mage::getStoreConfig(self::XML_PATH_FULL_DISABLED);
    }

    public function recursiveReplace($search, $replace, $subject) {
        if (!is_array($subject)) {
            return $subject;
        }

        foreach ($subject as $key => $value) {
            if (is_string($value)) {
                $subject[$key] = str_replace($search, $replace, $value);
            } elseif (is_array($value)) {
                $subject[$key] = self::recursiveReplace($search, $replace, $value);
            }
        }
        return $subject;
    }

    public function tradingHours($trading_hours = array(),$postcode = null,$store_tz){

        if($postcode == null){
            $postcode = Mage::registry('storelocator_address');
        }

        $user_timezone = $this->getUserLocalTime($postcode);
        date_default_timezone_set($user_timezone);
        $offset = $this->timezoneoffset($user_timezone,$this->findTimezone($store_tz[0],$store_tz[1]));
        $date =  date('w');
        $time =  strtotime(date('H:i:s'))+ $offset;

        $prefix = "";
        $tomorrow = 0;
        foreach($trading_hours as $no_day => $trading){

            $tomorrow++;
            $tomorrow = ($no_day == 6) ? 0 : $tomorrow;
            if($no_day == $date && $time >= strtotime($trading['o']) && $time <= strtotime($trading['c'])){
                $display =  "Closes at {$trading['c']} today";
                break;
            }elseif($no_day == $date && $time < strtotime($trading['o'])){
                $display =  "Opens at {$trading['o']}";
                break;
            }elseif(!empty(strtotime($trading_hours[$tomorrow]['o'])) && $no_day >= $date){
                $prefix = $trading_hours[$tomorrow]['label'] != "" ? "on" : "";
                $display =  "Opens at {$trading_hours[$tomorrow]['o']} {$prefix} {$trading_hours[$tomorrow]['label']}";
                break;
            }
        }

        return str_replace('.',':',$display);

    }

    /**
     * Get User Local Time from Location
     *
     * @param string $location postcode
     * @return bool|string
     */
    private function _locatePostcode($address) {

        $result = preg_replace("/[^0-9]/", "", $address);
        if ($result) {
            return $result;
        }
    }

    public function getUserLocalTime($location = '') {
        if (empty($location)) {
            return false;
        }
        $locationArray = $this->_locatePostcode($location);
        $store_TZ = $this->getTimeZoneByPostcode($locationArray);
        if ($store_TZ == 'UTC') {
            return false;
        }
        $TZ_offset = Mage::getModel('core/date')->calculateOffset($store_TZ);
        $_tn = new DateTime('now', new DateTimeZone($store_TZ));
        $tn = $_tn->format('U');
        $hour = $_tn->format('H:i:s');

        return $store_TZ;
    }

    public function getTimeZoneByPostcode($postcode) {

        $postcode = (int) $postcode;
        if ($postcode == 0) {
            return 'UTC';
        }
        $tzs = array(
            'Australia/NSW' => array(array('1000', '1999'), array('2000', '2599'), array('2619', '2898'), array('2921', '2999')),
            'Australia/ACT' => array(array('0200', '0299'), array('2600', '2618'), array('2900', '2920')),
            'Australia/Victoria' => array(array('3000', '3999'), array('8000', '8999')),
            'Australia/Queensland' => array(array('4000', '4999'), array('9000', '9999')),
            'Australia/South' => array(array('5000', '5799'), array('5800', '5999')),
            'Australia/West' => array(array('6000', '6797'), array('6800', '6999')),
            'Australia/Tasmania' => array(array('7000', '7799'), array('7800', '7999')),
            'Australia/North' => array(array('0800', '0899'), array('0900', '0999')),
        );

        foreach ($tzs as $tz => $ranges) {
            foreach ($ranges as $range) {
                if ((int) $range[0] <= $postcode && $postcode <= (int) $range[1])
                    return $tz;
            }
        }
        return 'UTC';
    }

    public function findNearestStore() {


        $arr_result = array();
        $store_id = Mage::getModel('core/cookie')->get('store_id');
        $default_data_store = Mage::getSingleton("core/session")->getdefaultStoreId();
        if ($store_id == '' && $default_data_store == '') {

            $location_by_browser = Mage::app()->getRequest()->getParam('loc');
            if ($location_by_browser != "") {
                $loc = $location_by_browser;
            } else {
                $loc = $this->ipinfo($this->getCurrentIP());
            }


            if (count($loc) > 0) {

                $coordinates = explode(",", $loc);
                $lat = $coordinates[0];
                $lng = $coordinates[1];
                $resource = Mage::getSingleton('core/resource');
                $readConnection = $resource->getConnection('core_read');

                $id = Mage::app()->getStore()->getStoreId();
                $query = 'SELECT *, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( latitude ) ) *'
                        . ' cos( radians( longitude ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * '
                        . 'sin( radians( latitude ) ) ) ) AS distance FROM ' . $resource->getTableName('storelocator/stores')
                        . ' as main JOIN iwd_storelocator_store as sub ON main.`entity_id` = sub.locatorstore WHERE (sub.store_id=0 OR sub.store_id=' . $id . ') AND is_active = 1  ORDER BY distance LIMIT 0 , 1';

                $results = $readConnection->fetchRow($query);

                if (isset($results['locatorstore'])) {

                    $store_id = $results['locatorstore'];
                    //$store_url = Mage::getBaseUrl() . Mage::helper('storelocator')->getRoute() . '/' . $results['url'];
                    $store_url = Mage::getBaseUrl() . 'store-locator';
                    Mage::getSingleton("core/session")->setStorelocation($store_id);
                    Mage::getSingleton("core/session")->setdefaultStoreId($store_id);

                    $arr_result = array('title' => $this->_cleanName($results['city']), 'url' => $store_url, 'distance' => $results['distance'], 'error' => 0);
                } else {
                    $arr_result = array('error' => 1);
                }
            } else {
                $arr_result = array('error' => 1);
            }
        } else {

            $id = '';
            if ($store_id == '') {
                $id = $default_data_store;
            } else {
                $id = $store_id;
            }

            $store = Mage::getModel('storelocator/stores')->load($id);
            if ($store->getIsActive()) {
                //$store_url = Mage::getBaseUrl() . Mage::helper('storelocator')->getRoute() . '/' . $store->getUrl();
                $store_url = Mage::getBaseUrl() . 'store-locator';
                $arr_result = array('title' => $this->_cleanName($store->getCity()), 'url' => $store_url, 'error' => 0);
            } else {
                $arr_result = array('error' => 1);
            }
        }
        echo json_encode($arr_result);
    }

    private function _cleanName($var) {

        $store_name = trim(ucwords(strtolower($var)));
        //$store_name_ellipsis = strlen($store_name) > 20 ? substr($store_name,0,20)."..." : $store_name;

        return $store_name;
    }

    public function getCurrentIP() {

        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

            $http_x_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $arr_http_x_ip = explode(",", $http_x_ip);

            $ip = $arr_http_x_ip[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    public function getUserTimezone() {


        $loc = $this->ipinfo($this->getCurrentIP());
        $timestamp = Mage::getModel('core/date')->timestamp(time());


        $getTimezone = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $loc . '&timestamp=' . $timestamp . '&key=%20AIzaSyDixbfbMFucrV2RIqaNcdIWge8NU5fPvfM'));

        return $getTimezone->timeZoneId;
    }

    public function ipinfo($ip) {

        $query = json_decode(file_get_contents('http://ipinfo.io/' . trim($ip) . '/json'));
        return $query->loc;
    }

    public function timezoneoffset($remote_tz, $origin_tz = null) {

        if ($origin_tz === null) {
            if (!is_string($origin_tz = date_default_timezone_get())) {
                return false; // A UTC timestamp was returned -- bail out!
            }
        }
        if ($remote_tz) {

            $origin_dtz = new DateTimeZone($origin_tz);
            $remote_dtz = new DateTimeZone($remote_tz);
            $origin_dt = new DateTime("now", $origin_dtz);
            $remote_dt = new DateTime("now", $remote_dtz);
            $offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
            return $offset;
        }
    }

    public function findTimezone($lat, $long) {

        $timezone = array();
        $data_timezone = '';
        $timezone["0"]["0"] = "Australia/Sydney";
        $timezone["-34.933425"]["138.606035"] = "Australia/Adelaide";
        $timezone["-34.930452"]["138.593693"] = "Australia/Adelaide";
        $timezone["-33.887726"]["151.169506"] = "Australia/Sydney";
        $timezone["-30.51262"]["151.671268"] = "Australia/Sydney";
        $timezone["-27.932687"]["153.375998"] = "Australia/Brisbane";
        $timezone["-27.976834"]["153.381041"] = "Australia/Brisbane";
        $timezone["-17.254138"]["145.47613"] = "Australia/Brisbane";
        $timezone["-19.578409"]["147.401377"] = "Australia/Brisbane";
        $timezone["-31.862252"]["115.80853"] = "Australia/Perth";
        $timezone["-37.572719"]["143.837710"] = "Australia/Hobart";
        $timezone["-37.559356"]["143.854274"] = "Australia/Hobart";
        $timezone["-28.866876"]["153.555824"] = "Australia/Sydney";
        $timezone["-33.917159"]["151.032908"] = "Australia/Sydney";
        $timezone["-33.413661"]["149.580626"] = "Australia/Sydney";
        $timezone["-37.823633"]["145.293601"] = "Australia/Hobart";
        $timezone["-27.713042"]["153.19357"] = "Australia/Brisbane";
        $timezone["-36.673976"]["149.83922"] = "Australia/Sydney";
        $timezone["-42.865569"]["147.371985"] = "Australia/Melbourne";
        $timezone["-38.17153"]["144.34593"] = "Australia/Hobart";
        $timezone["-36.548157"]["145.996035"] = "Australia/Hobart";
        $timezone["-36.758304"]["144.284975"] = "Australia/Hobart";
        $timezone["-37.924832"]["145.027106"] = "Australia/Hobart";
        $timezone["-33.759146"]["150.913589"] = "Australia/Sydney";
        $timezone["-37.820293"]["145.12022"] = "Australia/Hobart";
        $timezone["-35.273841"]["149.132291"] = "Australia/Sydney";
        $timezone["-38.000859"]["145.105303"] = "Australia/Hobart";
        $timezone["-32.180635"]["148.622659"] = "Australia/Sydney";
        $timezone["-33.765363"]["151.267422"] = "Australia/Sydney";
        $timezone["-27.665009"]["153.040495"] = "Australia/Brisbane";
        $timezone["-33.328902"]["115.639255"] = "Australia/Perth";
        $timezone["-24.865322"]["152.355401"] = "Australia/Brisbane";
        $timezone["-41.052493"]["145.903447"] = "Australia/Melbourne";
        $timezone["-37.850075"]["145.092412"] = "Australia/Hobart";
        $timezone["-16.920418"]["145.770849"] = "Australia/Brisbane";
        $timezone["-37.678653"]["144.956368"] = "Australia/Hobart";
        $timezone["-34.062514"]["150.818031"] = "Australia/Sydney";
        $timezone["-32.060323"]["115.908010"] = "Australia/Perth";
        $timezone["-37.797993"]["144.974779"] = "Australia/Hobart";
        $timezone["-24.870253"]["113.675745"] = "Australia/Perth";
        $timezone["-28.861868"]["153.048285"] = "Australia/Sydney";
        $timezone["-33.735001"]["150.98146"] = "Australia/Sydney";
        $timezone["-33.786251"]["151.200972"] = "Australia/Sydney";
        $timezone["-27.384589"]["153.030535"] = "Australia/Brisbane";
        $timezone["-31.983462"]["115.783649"] = "Australia/Perth";
        $timezone["-37.928565"]["145.119018"] = "Australia/Hobart";
        $timezone["-30.292698"]["153.117312"] = "Australia/Sydney";
        $timezone["-38.339019"]["143.580501"] = "Australia/Hobart";
        $timezone["-35.994563"]["146.390474"] = "Australia/Sydney";
        $timezone["-38.108332"]["145.283464"] = "Australia/Hobart";
        $timezone["-33.825604"]["151.199627"] = "Australia/Sydney";
        $timezone["-26.767568"]["153.122095"] = "Australia/Brisbane";
        $timezone["-27.180715"]["151.263655"] = "Australia/Brisbane";
        $timezone["-38.015795"]["145.225795"] = "Australia/Hobart";
        $timezone["-12.433158"]["130.848241"] = "Australia/Darwin";
        $timezone["-35.529759"]["144.956885"] = "Australia/Sydney";
        $timezone["-41.186982"]["146.361767"] = "Australia/Melbourne";
        $timezone["-37.787834"]["145.122424"] = "Australia/Hobart";
        $timezone["-32.251463"]["148.601289"] = "Australia/Sydney";
        $timezone["-31.993158"]["115.910991"] = "Australia/Perth";
        $timezone["-33.792546"]["150.872028"] = "Australia/Sydney";
        $timezone["-36.139799"]["144.754844"] = "Australia/Hobart";
        $timezone["-34.982292"]["138.574437"] = "Australia/Adelaide";
        $timezone["-34.718997"]["138.665350"] = "Australia/Adelaide";
        $timezone["-23.52422"]["148.15404"] = "Australia/Brisbane";
        $timezone["-37.651850"]["145.023610"] = "Australia/Hobart";
        $timezone["-33.840721"]["121.899173"] = "Australia/Perth";
        $timezone["-37.740803"]["144.901536"] = "Australia/Hobart";
        $timezone["-37.883665"]["145.281026"] = "Australia/Hobart";
        $timezone["-35.645345"]["145.575869"] = "Australia/Sydney";
        $timezone["-27.445627"]["153.043443"] = "Australia/Brisbane";
        $timezone["-38.018873"]["145.294938"] = "Australia/Hobart";
        $timezone["-38.137992"]["145.128036"] = "Australia/Hobart";
        $timezone["-35.330569"]["149.161921"] = "Australia/Sydney";
        $timezone["-27.569894"]["152.266126"] = "Australia/Brisbane";
        $timezone["-38.153457"]["144.357829"] = "Australia/Hobart";
        $timezone["-28.750234"]["114.627723"] = "Australia/Perth";
        $timezone["-23.869909"]["151.230844"] = "Australia/Brisbane";
        $timezone["-23.848401"]["151.260445"] = "Australia/Brisbane";
        $timezone["-29.736142"]["151.735899"] = "Australia/Sydney";
        $timezone["-21.141845"]["149.179048"] = "Australia/Brisbane";
        $timezone["-33.418944"]["151.343472"] = "Australia/Sydney";
        $timezone["-34.758842"]["149.717181"] = "Australia/Sydney";
        $timezone["-29.691762"]["152.93967"] = "Australia/Sydney";
        $timezone["-37.702236"]["145.106596"] = "Australia/Hobart";
        $timezone["-33.894932"]["148.157984"] = "Australia/Sydney";
        $timezone["-34.287958"]["146.053404"] = "Australia/Sydney";
        $timezone["-30.975651"]["150.249003"] = "Australia/Sydney";
        $timezone["-26.187448"]["152.667888"] = "Australia/Brisbane";
        $timezone["-37.743338"]["142.028177"] = "Australia/Hobart";
        $timezone["-37.824046"]["145.047501"] = "Australia/Hobart";
        $timezone["-27.414801"]["153.074904"] = "Australia/Brisbane";
        $timezone["-42.880638"]["147.322139"] = "Australia/Melbourne";
        $timezone["-37.879098"]["144.707402"] = "Australia/Hobart";
        $timezone["-29.771201"]["151.115761"] = "Australia/Sydney";
        $timezone["-41.423883"]["147.136907"] = "Australia/Melbourne";
        $timezone["-30.780069"]["121.433741"] = "Australia/Perth";
        $timezone["-20.770394"]["116.871642"] = "Australia/Perth";
        $timezone["-14.463021"]["132.260292"] = "Australia/Darwin";
        $timezone["-31.080181"]["152.836195"] = "Australia/Sydney";
        $timezone["-37.793448"]["145.063907"] = "Australia/Hobart";
        $timezone["-31.985266"]["115.953057"] = "Australia/Perth";
        $timezone["-37.993463"]["145.188393"] = "Australia/Hobart";
        $timezone["-37.308218"]["144.950373"] = "Australia/Hobart";
        $timezone["-42.982351"]["147.295135"] = "Australia/Melbourne";
        $timezone["33.4181509"]["-99.818980"] = "America/Chicago";
        $timezone["-32.940342"]["151.710448"] = "Australia/Sydney";
        $timezone["-32.240660"]["115.782155"] = "Australia/Perth";
        $timezone["-41.438015"]["147.140818"] = "Australia/Melbourne";
        $timezone["-27.291990"]["152.985675"] = "Australia/Brisbane";
        $timezone["-38.474581"]["145.945401"] = "Australia/Hobart";
        $timezone["-33.927321"]["150.919992"] = "Australia/Sydney";
        $timezone["-27.655038"]["153.168875"] = "Australia/Brisbane";
        $timezone["-34.451855"]["140.572563"] = "Australia/Adelaide";
        $timezone["-21.171485"]["149.161444"] = "Australia/Brisbane";
        $timezone["-37.852239"]["145.037141"] = "Australia/Hobart";
        $timezone["-32.536736"]["115.740752"] = "Australia/Perth";
        $timezone["-26.653164"]["153.087032"] = "Australia/Brisbane";
        $timezone["-33.941339"]["151.236809"] = "Australia/Sydney";
        $timezone["-33.919658"]["151.183460"] = "Australia/Sydney";
        $timezone["-32.046287"]["115.815679"] = "Australia/Perth";
        $timezone["-31.891838"]["116.010929"] = "Australia/Perth";
        $timezone["-34.191333"]["142.175887"] = "Australia/Hobart";
        $timezone["-34.828199"]["138.691847"] = "Australia/Adelaide";
        $timezone["-42.844517"]["147.291635"] = "Australia/Melbourne";
        $timezone["-27.11419"]["152.952802"] = "Australia/Brisbane";
        $timezone["-27.46723"]["153.078057"] = "Australia/Brisbane";
        $timezone["-38.235061"]["145.056808"] = "Australia/Hobart";
        $timezone["-38.232485"]["146.443891"] = "Australia/Hobart";
        $timezone["-27.539149"]["153.080924"] = "Australia/Brisbane";
        $timezone["-20.720738"]["139.485607"] = "Australia/Brisbane";
        $timezone["-37.903746"]["145.157627"] = "Australia/Hobart";
        $timezone["-35.126171"]["139.256403"] = "Australia/Adelaide";
        $timezone["-28.329077"]["153.400878"] = "Australia/Sydney";
        $timezone["-32.272751"]["150.890144"] = "Australia/Sydney";
        $timezone["-26.623759"]["152.961389"] = "Australia/Brisbane";
        $timezone["-36.955785"]["140.739681"] = "Australia/Adelaide";
        $timezone["-30.32205"]["149.783317"] = "Australia/Sydney";
        $timezone["-34.744262"]["146.554641"] = "Australia/Sydney";
        $timezone["-32.939937"]["117.187703"] = "Australia/Perth";
        $timezone["-28.005901"]["153.341133"] = "Australia/Brisbane";
        $timezone["-42.780674"]["147.061508"] = "Australia/Melbourne";
        $timezone["-37.841262"]["144.882957"] = "Australia/Hobart";
        $timezone["-35.143371"]["138.488125"] = "Australia/Adelaide";
        $timezone["-26.410907"]["153.047363"] = "Australia/Brisbane";
        $timezone["-36.041729"]["146.962464"] = "Australia/Sydney";
        $timezone["-38.107016"]["144.342762"] = "Australia/Hobart";
        $timezone["-31.649209"]["116.673546"] = "Australia/Perth";
        $timezone["-34.921899"]["138.62816"] = "Australia/Adelaide";
        $timezone["-33.579133"]["150.719075"] = "Australia/Sydney";
        $timezone["-33.282351"]["149.103545"] = "Australia/Sydney";
        $timezone["-31.899683"]["115.808302"] = "Australia/Perth";
        $timezone["-31.910876"]["115.816676"] = "Australia/Perth";
        $timezone["-38.081382"]["145.487928"] = "Australia/Hobart";
        $timezone["-12.476982"]["130.984190"] = "Australia/Darwin";
        $timezone["-33.817009"]["150.999038"] = "Australia/Sydney";
        $timezone["-37.373808"]["140.831191"] = "Australia/Adelaide";
        $timezone["-33.754569"]["150.696927"] = "Australia/Sydney";
        $timezone["-34.192422"]["150.604874"] = "Australia/Sydney";
        $timezone["-34.847615"]["138.508081"] = "Australia/Adelaide";
        $timezone["-32.49377"]["137.763097"] = "Australia/Adelaide";
        $timezone["-20.310750"]["118.580072"] = "Australia/Perth";
        $timezone["-34.478546"]["150.888791"] = "Australia/Sydney";
        $timezone["-31.429143"]["152.882112"] = "Australia/Sydney";
        $timezone["-37.830243"]["144.945339"] = "Australia/Hobart";
        $timezone["-33.180586"]["138.011048"] = "Australia/Adelaide";
        $timezone["-16.939908"]["145.769995"] = "Australia/Brisbane";
        $timezone["-37.736453"]["145.004261"] = "Australia/Hobart";
        $timezone["-34.724917"]["135.864881"] = "Australia/Adelaide";
        $timezone["-33.749162"]["151.146372"] = "Australia/Sydney";
        $timezone["-32.76177"]["151.740971"] = "Australia/Sydney";
        $timezone["-34.184877"]["140.732522"] = "Australia/Adelaide";
        $timezone["-35.103778"]["138.523205"] = "Australia/Adelaide";
        $timezone["-34.934994"]["138.570208"] = "Australia/Adelaide";
        $timezone["-37.819235"]["145.005224"] = "Australia/Hobart";
        $timezone["-37.816957"]["145.220484"] = "Australia/Hobart";
        $timezone["-23.356439"]["150.524381"] = "Australia/Brisbane";
        $timezone["-32.276970"]["115.760436"] = "Australia/Perth";
        $timezone["-27.540884"]["153.010179"] = "Australia/Brisbane";
        $timezone["-37.737159"]["145.075043"] = "Australia/Hobart";
        $timezone["-32.721676"]["151.540502"] = "Australia/Sydney";
        $timezone["-33.816674"]["151.104338"] = "Australia/Sydney";
        $timezone["-41.159161"]["147.517552"] = "Australia/Melbourne";
        $timezone["-35.191489"]["138.483483"] = "Australia/Adelaide";
        $timezone["-36.38642"]["145.427956"] = "Australia/Hobart";
        $timezone["-40.844144"]["145.124369"] = "Australia/Melbourne";
        $timezone["-37.628448"]["144.949045"] = "Australia/Hobart";
        $timezone["-37.651324"]["145.084206"] = "Australia/Hobart";
        $timezone["-37.847475"]["145.000663"] = "Australia/Hobart";
        $timezone["-27.969599"]["153.412283"] = "Australia/Brisbane";
        $timezone["-32.096418"]["115.799702"] = "Australia/Perth";
        $timezone["-33.769644"]["150.768808"] = "Australia/Sydney";
        $timezone["-27.409681"]["153.011320"] = "Australia/Brisbane";
        $timezone["-32.879377"]["151.726103"] = "Australia/Sydney";
        $timezone["-35.260361"]["138.892416"] = "Australia/Adelaide";
        $timezone["-19.266174"]["146.809228"] = "Australia/Brisbane";
        $timezone["-37.583494"]["144.722566"] = "Australia/Hobart";
        $timezone["-37.784824"]["144.834027"] = "Australia/Hobart";
        $timezone["-35.340660"]["143.561061"] = "Australia/Hobart";
        $timezone["-31.08977"]["150.932721"] = "Australia/Sydney";
        $timezone["-34.516857"]["138.969278"] = "Australia/Adelaide";
        $timezone["-34.029927"]["151.120331"] = "Australia/Sydney";
        $timezone["-19.318558"]["146.725314"] = "Australia/Brisbane";
        $timezone["-32.788534"]["151.6338"] = "Australia/Sydney";
        $timezone["-27.569292"]["151.951612"] = "Australia/Brisbane";
        $timezone["-27.574324"]["151.925348"] = "Australia/Brisbane";
        $timezone["-19.264725"]["146.776236"] = "Australia/Brisbane";
        $timezone["-37.815642"]["140.758798"] = "Australia/Adelaide";
        $timezone["-35.302852"]["148.223484"] = "Australia/Sydney";
        $timezone["-27.623385"]["153.116094"] = "Australia/Brisbane";
        $timezone["-27.370524"]["153.062553"] = "Australia/Brisbane";
        $timezone["-35.115271"]["147.348419"] = "Australia/Sydney";
        $timezone["-33.309289"]["117.34183"] = "Australia/Perth";
        $timezone["-36.359132"]["146.318901"] = "Australia/Hobart";
        $timezone["-34.996994"]["138.530265"] = "Australia/Adelaide";
        $timezone["-28.212226"]["152.036717"] = "Australia/Brisbane";
        $timezone["-31.462107"]["152.733249"] = "Australia/Sydney";
        $timezone["-37.903129"]["144.657226"] = "Australia/Hobart";
        $timezone["-28.101766"]["153.434272"] = "Australia/Brisbane";
        $timezone["-37.796849"]["144.872197"] = "Australia/Hobart";
        $timezone["-37.812210"]["144.951268"] = "Australia/Hobart";
        $timezone["-37.814794"]["144.813803"] = "Australia/Hobart";
        $timezone["-33.033132"]["137.583696"] = "Australia/Adelaide";
        $timezone["-34.850305"]["138.551209"] = "Australia/Adelaide";
        $timezone["-12.425432"]["130.886556"] = "Australia/Darwin";
        $timezone["-36.119784"]["146.880638"] = "Australia/Hobart";
        $timezone["-34.428455"]["150.893116"] = "Australia/Sydney";
        $timezone["-27.490754"]["153.035421"] = "Australia/Brisbane";
        $timezone["-27.446894"]["153.172495"] = "Australia/Brisbane";
        $timezone["-33.281492"]["151.425793"] = "Australia/Sydney";
        $timezone["-34.310273"]["148.284715"] = "Australia/Sydney";

        if (isset($timezone[$lat][$long]))
            $data_timezone = $timezone[$lat][$long];

        if ($data_timezone == "") {
            $timestamp = Mage::getModel('core/date')->timestamp(time());
            $getTimezone = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/timezone/json?location=' . $lat . ',' . $long . '&timestamp=' . $timestamp . '&key=%20AIzaSyDixbfbMFucrV2RIqaNcdIWge8NU5fPvfM'));
            return $getTimezone->timeZoneId;
        }
        return $data_timezone;
    }

    /**
     * @param null $openingHours
     * @param null $closingHOurs
     * @return string
     */
    public function displayOpeningHours($openingHours = null, $closingHours = null) {

        $CheckOpeningHours = strtotime($openingHours);
        $CheckClosingHours = strtotime($closingHours);

        if (!empty($CheckOpeningHours) && !empty($CheckClosingHours)) {
            return $this->__('%s - %s', $openingHours, $closingHours);
        } else {
            return $this->__('Closed');
        }
    }

    public function getRegionValues() {
        $region = Mage::getModel('storelocator/region')->getCollection()
                ->addFieldtoSelect('region_id')
                ->addFieldtoSelect('name')
                ->load();
        $regionOptions = array();
        foreach ($region as $reg) {
            $regionOptions[] = array(
                'value' => $reg->getId(),
                'label' => $reg->getName(),
            );
        }
        return $regionOptions;
    }

    public function searchUrlKey() {
        $region = Mage::getModel('storelocator/region')->getCollection()
                ->addFieldtoSelect('url_key')
                ->load();
        $urlkey = array();
        foreach ($region as $reg) {
            $urlkey[] = $reg->getUrlKey();
        }
        return $urlkey;
    }

    public function getAreaDetails($address) {
        $searchAddress = array();
        switch ($address) {
            case 'melbourne':
                $searchAddress = array('search_address' => 'Melbourne Victoria, Australia',
                    'name' => 'Melbourne');
                break;
            case 'geelong':
                $searchAddress = array('search_address' => 'Geelong, Victoria, Australia',
                    'name' => 'Geelong');
                break;
            case 'adelaide':
                $searchAddress = array('search_address' => 'Adelaide, South Australia, Australia',
                    'name' => 'Adelaide');
                break;
            case 'darwin':
                $searchAddress = array('search_address' => 'Darwin, Northern Territory, Australia',
                    'name' => 'Darwin');
                break;
            case 'gold-coast':
                $searchAddress = array('search_address' => 'Gold Coast, Queensland, Australia',
                    'name' => 'Gold Coast');
                break;
            case 'hobart':
                $searchAddress = array('search_address' => 'Hobart, Tasmania, Australia',
                    'name' => 'Hobart');
                break;
            case 'newcastle':
                $searchAddress = array('search_address' => 'Newcastle, New South Wales, Australia',
                    'name' => 'Newcastle');
                break;
            case 'northern-territory':
                $searchAddress = array('search_address' => 'Northern Territory, Australia',
                    'name' => 'Northern Territory');
                break;
            case 'nsw':
                $searchAddress = array('search_address' => 'NSW, Australia',
                    'name' => 'NSW');
                break;
            case 'perth':
                $searchAddress = array('search_address' => 'Perth, Western Australia, Australia',
                    'name' => 'Perth');
                break;
            case 'western-australia':
                $searchAddress = array('search_address' => 'Western Australia, Australia',
                    'name' => 'Western Australia');
                break;
            case 'queensland':
                $searchAddress = array('search_address' => 'Queensland, Australia',
                    'name' => 'Queensland');
                break;
            case 'sydney':
                $searchAddress = array('search_address' => 'Sydney, New South Wales, Australia',
                    'name' => 'Sydney');
                break;
            case 'tasmania':
                $searchAddress = array('search_address' => 'Tasmania, Australia',
                    'name' => 'Tasmania');
                break;
            case 'victoria':
                $searchAddress = array('search_address' => 'Victoria, Australia',
                    'name' => 'Victoria');
                break;
            case 'canberra':
                $searchAddress = array('search_address' => 'Canberra, Australian Capital Territory, Australia',
                    'name' => 'Canberra');
                break;
            case 'act':
                $searchAddress = array('search_address' => 'ACT, Australia',
                    'name' => 'ACT');
                break;
            case 'wa':
                $searchAddress = array('search_address' => 'Western Australia, Australia',
                    'name' => 'Western Australia');
                break;
            case 'brisbane':
                $searchAddress = array('search_address' => 'Brisbane, Queensland, Australia',
                    'name' => 'Brisbane');
                break;
            case 'sa':
                $searchAddress = array('search_address' => 'South Australia, Australia',
                    'name' => 'South Australia');
                break;
        }
        return $searchAddress;
    }

    private function _getAllowedUserRole() {
        return array('Administrators', 'GoodyearAu', 'GoodyearNz');
    }

    private function _checkPermission() {
        $roleId = implode('', Mage::getSingleton('admin/session')->getUser()->getRoles());
        $roleName = Mage::getModel('admin/roles')->load($roleId)->getRoleName();
        return $roleName;
    }

    public function isAllowed() {
        $role = $this->_checkPermission();
        $roles = $this->_getAllowedUserRole();
        return (in_array($role, $roles)) ? true : false;
    }

}
