<?php

class ApdInteract_Si_Helper_Data extends Mage_Core_Helper_Abstract {

    private $_params;
    private $_categoryIdByProductId;
    private $_store;
    private $_order;


    private function _redirectTo404() {
        echo '<html><head></head><body><script>location.href="/no-route";</script></body></html>';
        exit;
    }

    private function _getOrdersCollection() {
        $store = Mage::app()->getStore();

        // Gets the current store's id
        $storeId = Mage::app()->getStore()->getStoreId();

        $orders = Mage::getModel('sales/order')->getCollection()->addAttributeToSelect('increment_id')->addAttributeToFilter('status', array(
            'nin' => array(
                'canceled'
            )
        ))->addFieldToFilter('created_at', array(
            'gteq' => $this->DateFromGmtFormat()
        ))->addFieldToFilter('store_id', array(
            'eq' => $storeId
        ))->addFieldToFilter('created_at', array(
            'lteq' => $this->DateToGmtFormat()
        ))->addFieldToFilter('request_type', array(
            'neq' => ''            
        ))->join(array(
            'soa' => 'sales/order_address'
        ), 'soa.parent_id=main_table.entity_id and soa.address_type = "billing"', array(
            'name' => 'CONCAT(soa.firstname, " " , soa.lastname)',
            'custemail' => 'soa.email',
            'custphoneno' => 'soa.telephone',
            'custaddress' => 'soa.street',
            'custsuburb' => 'soa.city',
            'custregion' => 'soa.region',
            'custpostcode' => 'soa.postcode'
        ), null, 'left');
        $orders->getSelect()->columns(array(
            'increment_id AS increment_id',
            'quote_id AS quote_id',
            'store_id AS web_site_id',
            'created_at AS date_submission',
            'vmake AS make',
            'vmodel AS model',
            'delivery_date AS delivery_date',
            'registration_number AS registration',
            'customer_email AS customer_email',
            'storelocation AS storelocation',
            'year AS year',
            'request_type AS request_type',
            //                    'delivery_time AS delivery_time', // for project
            'soa.region AS custregion'
            //                    'tyre_size AS tyre_size',
            //                    'tyre_selected AS tyre_selected',
            //                    'additional_info AS additional_info',
            //                    'othserv_rotation AS othserv_rotation',
            //                    'othserv_warranty AS othserv_warranty',
            //                    'othserv_alignment AS othserv_alignment',
            //                    'servicing_details AS servicing_details',
            //                    'last_service AS last_service',
            //                    'opt_out_product AS opt_out_product',
            //                    'vehicle_version AS vehicle_version',
            //                    'email_address_to_sent AS email_address_to_sent',
            //                    'num_tyres AS num_tyres',
        ))
            //->group('name')
            ->order(array(
                'increment_id ASC'
            ));

        return $orders;
    }

    public function getStreamInteractiveOrderXml($params) {

        $this->_params = $params;

        $xml = '';
        $authFilter = $params['auth'];

        if ($authFilter != "91850210b93d06aa7a5018f0c5c403f84f42ecf5233d0c418e478b82c8fabc2a") {
            $this->_redirectTo404();
        }

        $today = new DateTime(null, new DateTimeZone('Australia/Melbourne'));
        $orders = $this->_getOrdersCollection();
        $counter = $orders->count();

        $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<!DOCTYPE QuoteRequestResponse>';
        $xml .= '<QuoteRequestResponse rows="' . $counter . '" fields="26">';
        $rowCounter = 1;
        foreach ($orders as $order) {
            $increment_id = $order->getIncrementId();

            $incrementIdParts = explode('-', $increment_id); // if we need to strip out the revision id's // disabled this part as it strips the store code
            if (count($incrementIdParts) > 1) {
                $increment_id = $incrementIdParts[0];
            }

            $additionalInfo = "";

            $_order = Mage::getModel('sales/order')->loadByIncrementId($order->getIncrementId());
            $items = $_order->getAllVisibleItems(); // Now that products are added to cart correctly, we can do this:

            //// Get simples instead, just in case configurable attribute has not been set correctly in order
//            $items = $_order->getItemsCollection()->addAttributeToSelect('*')->addAttributeToFilter('product_type_id', array(
//                        'eq' => 'simple'
//                    ))->load();


            // Get Store details ~ State / Zip / Town
            $storeLocation = $order->getStorelocation();
            $store = Mage::getModel('storelocator/stores')->load($storeLocation);
            $easter_size = $_order->getEasterfrmTyreSize();
            $this->_setStore($store);
            $this->_setOrder($order);
            $state = $this->_getState();

            // BCC-16: Change catch-alls from 'n/a' to NT / 0000 / Not Specified
            $zip_return_if_empty = '0000'; // 'n/a';
            $city = 'Not Specified'; // 'n/a';



            $zip = $this->_formatPostcode($store->getPostalCode(), $zip_return_if_empty);

            if ($store->getCity()) {
                $city = $store->getCity();
            }

            $ordered_items = Array();
            $tyreSize = Array();
            $requestPriceChecker = false;

            $quoteType = 'B'; // If a quote contains ONLY batteries, then B

            foreach ($items as $item_id => $item) {

                $productOptions = $item->getProductOptions();
                $sizes = "";
                if (isset($productOptions['attributes_info'][0]))
                {
                    $sizes = implode(' ', $productOptions['attributes_info'][0]);
                }

//              //  Might need this for project, but not for care (no upsells)
//                $productOptions = $item->getProductOptions();
//                if (isset($productOptions['info_buyRequest'])) {
//                    $productOptions = $productOptions['info_buyRequest'];
//                }
//                $upsells = $this->_addUpsells($productOptions);

                $ordered_items[] = intval($item->getQtyOrdered()) . " x " . $item->getName() . " (" . $item->getSku() . ") " . $sizes . $upsells;
                $isEasterQuote = 'N';
                $isTyresfrom399Quote = 'N';
                $isbetofferQuote = 'N';
                if($item->getProductId() == Mage::getStoreConfig('booking_date/date/easter')) {                    
                    $quoteType = 'T';
                    $isEasterQuote = 'Y';
                }elseif($item->getProductId() == Mage::getStoreConfig('booking_date/date/tyresfrom399')) {
                    $quoteType = 'T';
                    $isTyresfrom399Quote = 'Y';
                }elseif($item->getProductId() == Mage::getStoreConfig('booking_date/date/batoffer')) {
                    $quoteType = 'T';
                    $isbetofferQuote = 'Y';
                } elseif ($quoteType == 'B' ) {
                    // only do this expensive product loading / category checking stuff if needed
                    $quoteType = $this->_getQuoteType($item);
                }         
                    

                //}
            }

            $additionalInfo = implode(",\n", $ordered_items);

            $othserv_rotation = 'N';
            $othserv_warranty = 'N';
            $othserv_alignment = 'N';

            if ($quoteType == 'B') {
                $othserv_rotation = '&#160;'; // TODO: Change this to whatever Stream Interactive decide "blank" means.
                $othserv_warranty = '&#160;';
                $othserv_alignment = '&#160;';
            }
            
            

            // Useful only if customer has entered an address
            // $custPostCode = $this->_formatPostcode($order->getCustpostcode());

            $deliveryTime = $this->_convertDeliveryTime($order->getDeliveryTime());

            $xml .= '<row responseQueryId="' . $rowCounter . '">';
            // get data
            $xml .= '<quote_id>' . $increment_id . '</quote_id>';
            $xml .= '<web_site_id>5</web_site_id>';
            $xml .= '<quote_type>' . $quoteType . '</quote_type>';
            $xml .= '<date_submission>' . $this->convertToAuTime($order->getDateSubmission()) . '</date_submission>';
            $xml .= '<name>' . $this->xmlEscape($order->getName()) . '</name>';
            $xml .= '<email>' . $this->xmlEscape($order->getCustomerEmail()) . '</email>';
            $xml .= '<phone>' . $this->xmlEscape($order->getCustphoneno()) . '</phone>';
            
            $xml .= '<postcode>' . $this->xmlEscape(substr($zip,0,4)) . '</postcode>';
            $xml .= '<state>' . $this->xmlEscape($state) . '</state>';
            $xml .= '<town>' . $this->xmlEscape($city) . '</town>';
            
                
            $xml .= '<make>' . $this->xmlEscape(substr($order->getMake(),0,15)) . '</make>';
            $xml .= '<model>' . $this->xmlEscape($order->getModel()) . '</model>';
            $xml .= '<year>' . $this->xmlEscape($order->getYear()) . '</year>';
            $xml .= '<registration>' . $this->xmlEscape($order->getRegistration()) . '</registration>';

            if(trim($easter_size) !='')
                $xml .= '<tyre_size>' . $this->xmlEscape($easter_size) . '</tyre_size>';
            else
            $xml .= '<tyre_size>' . $this->xmlEscape(implode(',', $tyreSize)) . '</tyre_size>';
            // Care - no delivery time

            if($quoteType=='T' && $isEasterQuote=='Y'):
                $xml .= '<additional_info>EASTER CAMPAIGN - Get a $150 gift card when you buy 4 selected tyres</additional_info>';
            elseif($quoteType=='T' && $isTyresfrom399Quote=='Y'):
                $xml .= '<additional_info>May/June fixed priced offers</additional_info>';
            elseif($quoteType=='T' && $isbetofferQuote=='Y'):
                $xml .= '<additional_info>June $30 Battery offer </additional_info>';
            else:
            $xml .= '<additional_info>' . $this->xmlEscape($order->getRequestType()) . ' ' . $this->xmlEscape($order->getDeliveryDate()) . ' - ' . $this->xmlEscape($additionalInfo) . '</additional_info>';
            endif;
            // Project - includes delivery time
            //            $xml .= '<additional_info>' . $this->xmlEscape($order->getRequestType()) . ' ' . $this->xmlEscape($order->getDeliveryDate()) . ' ' . $this->xmlEscape($deliveryTime) . ' - ' . $this->xmlEscape($additionalInfo) . '</additional_info>';
            $xml .= '<othserv_rotation>' . $othserv_rotation . '</othserv_rotation>';
            $xml .= '<othserv_warranty>' . $othserv_warranty . '</othserv_warranty>';
            $xml .= '<othserv_alignment>' . $othserv_alignment . '</othserv_alignment>';
            $xml .= '<servicing_details></servicing_details>';
            $xml .= '<last_service></last_service>';
            $xml .= '<opt_out_product>N</opt_out_product>';
            $xml .= '<vehicle_version></vehicle_version>';
            $xml .= '<email_address_to_sent></email_address_to_sent>';
            $xml .= '<num_tyres></num_tyres>';

            $xml .= '</row>';
            $rowCounter += 1;
        }

        $xml .= '</QuoteRequestResponse>';


        return $xml;
    }

    function xmlEscape($string) {
        return str_replace(array(
            '&',
            '<',
            '>',
            '\'',
            '"'
        ), array(
            '&amp;',
            '&lt;',
            '&gt;',
            '&apos;',
            '&quot;'
        ), $string);
    }

    public function DateFromGmtFormat() {
        $melbourne = new DateTimeZone('Australia/Melbourne');
        $gmt = new DateTimeZone('GMT');

        $date = new DateTime($this->getDateParam() . '00:00:00', $melbourne);
        $date->setTimezone($gmt);
        return $date->format('Y-m-d H:i:s');
    }

    public function DateToGmtFormat() {
        $melbourne = new DateTimeZone('Australia/Melbourne');
        $gmt = new DateTimeZone('GMT');

        $date = new DateTime($this->getDateParam() . '23:59:59', $melbourne);
        $date->setTimezone($gmt);

        return $date->format('Y-m-d H:i:s');
    }

    public function getDateParam() {
        $params = $this->_params;
        $dateFilter = $params['date'];
        $year = intval(substr($dateFilter, 0, 4));
        $month = intval(substr($dateFilter, 4, 2));
        $day = intval(substr($dateFilter, 6, 2));

        if (!$year || !$month || !$day) {
            $this->_redirectTo404();
        }

        $date = $this->_padToFourDigits($year) . '-' . $this->_padToTwoDigits($month) . '-' . $this->_padToTwoDigits($day);

        return $date;
    }

    public function convertToAuTime($date) {
        $utc_date = DateTime::createFromFormat('Y-m-d H:i:s', $date, new DateTimeZone('UTC'));
        $acst_date = clone $utc_date;
        $acst_date->setTimeZone(new DateTimeZone('Australia/Melbourne'));

        return $acst_date->format('Y-m-d H:i:s');
    }

    private function _getStateCode($region) {
        $code = Mage::getModel('directory/region')->loadByName($region, 'AU');
        return strtoupper($code->getCode());
    }

    private function _convertDeliveryTime($time) {
        $convertedTime = ltrim(date('h:i a', strtotime($time)), '0');
        return $convertedTime;
    }

    private function _padToDigits($number, $pad_length = 4) {
        return sprintf("%0{$pad_length}d", $number);
    }

    private function _padToTwoDigits($number) {
        return $this->_padToDigits($number, 2);
    }

    private function _padToFourDigits($number) {
        return $this->_padToDigits($number, 4);
    }

    private function _formatPostcode($postcode, $return_if_empty = '0000') {
        $custPostCode = $this->_padToFourDigits(intval($postcode));
        if ($custPostCode == "0000") {
            $custPostCode = $return_if_empty;
        }
        return $custPostCode;
    }

    private function _addUpsells($productOptions) {
        // For care
        return '';

        // for project
        //        $exclude = array('Free option 1', 'Free option 2');
        //        return '+ ' . $this->_flattenArray($productOptions['options'], 'label', $exclude);
    }

    private function _getIfExists($string) {
        if (isset($string)) {
            return $string;
        }
        return '';
    }

//    private function _flattenArray($array, $value_key, $exclude = null, $label_key = null)
//    {
//        foreach ($array as $option) {
//
//            // Something like "Size"
//            $label = '';
//            if (!is_null($label_key) && !is_empty($label = $this->_getIfExists($option[$label_key]))) {
//                $label = ' ' . $label . ': ';
//            }
//
//            // Something like "205/60R15"
//            $value = $this->_getIfExists($option[$value_key]);
//
//            if (!is_array($exclude) || !in_array($value, $exclude)) {
//                $included_values[] = $label . $value;
//            }
//        }
//        return implode(',', $included_values);
//    }

    private function _getCategoryId($product_id) {
        // Return first category of product
        if (!isset($this->_categoryIdByProductId[$product_id])) {
            $product = Mage::getModel('catalog/product')->load($product_id);
            $category = $product->getCategoryIds();
            $this->_categoryIdByProductId[$product_id] = $category[0];
        }
        return $this->_categoryIdByProductId[$product_id];
    }


    private function _getQuoteType($item) {

        $quoteType = 'B';

        $product_id = $item->getProductId();        
        $category = $this->_getCategoryId($product_id);
        if ($category !== '43') {
            $quoteType = 'T'; // If a quote contains non-battery products, then T
        }

        return $quoteType;
    }

    private function _getRegionModel() {
        return Mage::getModel('directory/region');
    }

    private function _setStore($store) {
        $this->_store = $store;
    }

    private function _setOrder($order) {
        $this->_order = $order;
    }

    private function _getState() {
        // Get from closest store region ID
        if ($regionId = $this->_store->getRegionId()) {
            $region = $this->_getRegionModel()->load($regionId);
            return strtoupper($region->getCode());
        }

        // If blank, try closest store region (text)
        if (!$region = $this->_store->getRegion()) {

            // if blank, try customer address record
            $region = $this->_getStateCode($this->_order->getCustregion());
        }


        // Make sure state is valid - otherwise Stream Interactive might reject it
        if ($this->_validateState($region)) {
            return strtoupper($region);
        }

        return $state = 'NT'; // SI Valid catchall.
    }

    private function _validateState($state, $countryId = 'AU') {
        $regions = Mage::getResourceModel('directory/region_collection');
//        $regions->addCountryFilter($countryId);
//        $regions->addExpressionFieldToSelect('state_upper', 'UPPER({{code}})', 'code');
//        $regions->addFieldToFilter('state_upper', array('eq' => $state) );
//        Above doesn't wotk, but this does:
        $regions->getSelect()
            ->reset(Zend_Db_Select::WHERE)
            ->where("country_id = ?", $countryId)
            ->where("UPPER(code) = ?", $state);

        // To see the SQL query
        //$select = (string)$regions->getSelect();

        $matches = $regions->getSize(); // count() also works, but is less efficient;
        return ($matches > 0);
    }

}
