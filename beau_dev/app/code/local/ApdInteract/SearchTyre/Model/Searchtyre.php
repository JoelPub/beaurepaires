<?php

class ApdInteract_SearchTyre_Model_Searchtyre extends Mage_Core_Model_Abstract
{
    
    private $_accessToken;
    private $_apiUrl;
    private $_apiUsername;
    private $_apiPassword;
    private $_request;
    private $_body;
    private $_fields;
    
    private function _setUrl()
    {
        $this->_apiUrl = Mage::getStoreConfig('apdinteract/apdinteract_group/apdinteract_api');
    }
    
    private function _setCredentials()
    {
        $this->_apiUsername = Mage::getStoreConfig('apdinteract/apdinteract_group/apdinteract_user');
        $this->_apiPassword = Mage::getStoreConfig('apdinteract/apdinteract_group/apdinteract_pass');
    }
    
    public function fetchFromApi($request)
    {
        $this->_setUrl();
        $CollectionRequest = new Zend_Http_Client();
        $CollectionRequest->setUri($this->_apiUrl . $request);
        $CollectionRequest->setMethod(Zend_Http_Client::GET);
        $CollectionRequest->setHeaders(Zend_Http_Client::CONTENT_TYPE, 'text/json');
        $CollectionRequest->setHeaders('Authorization: Bearer ' . $this->getToken());
        return $CollectionRequest;
    }
    
    
    private function _fetchTokenFromRemote()
    {
        $this->_setCredentials();
        $url = $this->_apiUrl . 'token';
        
        $clientLogin = new Zend_Http_Client();
        $clientLogin->setUri($url);
        $clientLogin->setMethod(Zend_Http_Client::POST);
        $clientLogin->setHeaders(Zend_Http_Client::CONTENT_TYPE, 'application/x-www-form-urlencoded');
        $clientLogin->setParameterPost('grant_type', 'password');
        $clientLogin->setParameterPost('username', $this->_apiUsername);
        $clientLogin->setParameterPost('password', $this->_apiPassword);
        
        $responseLogin     = $clientLogin->request();
        $responseLoginBody = $responseLogin->getBody();
        
        $value = json_decode($responseLoginBody);
        if (isset($value->access_token)) {
            return $value->access_token;
        }
        
        echo "{$responseLoginBody}\r\n";
        echo "Url: {$url}\r\n";
        die();
        
    }
    
    private function _tokenHasExpired()
    {
        $token_created_at = Mage::getSingleton('core/session')->getApiAccessTokenCreatedAt();
        if (empty($token_created_at)) {
            return true;
        }
        
        $expiry = 60 * 60 * 24; // 1 day
        
        $now                = Mage::getModel('core/date')->timestamp(); // new Zend_Date(Mage::getModel('core/date')->timestamp());
        $then               = $token_created_at; // new Zend_Date($token_created_at);
        $seconds_difference = $now - $then; // $now->sub($then)->toValue();              
        
        if ($seconds_difference > $expiry) {
            return true;
        }
        
        return false;
    }
    
    private function _setToken()
    {
        // Stores in session        
        if (!isset($this->_accessToken)) {
            $this->_accessToken = Mage::getSingleton('core/session')->getApiAccessToken();
            if (!isset($this->_accessToken) || $this->_tokenHasExpired()) {
                $this->_accessToken = $this->_fetchTokenFromRemote();
                Mage::getSingleton('core/session')->setApiAccessToken($this->_accessToken);
                Mage::getSingleton('core/session')->setApiAccessTokenCreatedAt(Mage::getModel('core/date')->timestamp());
            }
        }
    }
    
    
    public function getToken()
    {
        if (!isset($this->_accessToken)) {
            $this->_setUrl();
            $this->_setToken();
        }
        return $this->_accessToken;
    }
    
    public function getRequest()
    {
        
    }
    
    
    public function connect($request, $fields)
    {
        $this->_request = $request;
        // $this->_fetchDataFromDatabase();
        
        if (!isset($this->_body)) {
            // Get remote data
            $this->_fields = $fields;
            $this->_setUrl();
            $this->_setToken();
            // $this->_fetchDataFromRemote();        
        }
        
        return $this->_accessToken; // $this->_body;
    }
    
    public function connectorRemote()
    {
        $CollectionRequest = new Zend_Http_Client();
        $CollectionRequest->setUri($this->_apiUrl . $this->_request);
        $CollectionRequest->setMethod(Zend_Http_Client::GET);
        $CollectionRequest->setHeaders(Zend_Http_Client::CONTENT_TYPE, 'text/json');
        $CollectionRequest->setHeaders('Authorization: Bearer ' . $this->_accessToken);
        $request = $CollectionRequest->request();
        
        $this->_body = $request->getBody();
    }
    
    
    private function _fetchDataFromRemote()
    {
        $this->connectorRemote();
        $this->_convertFromJsonToSelectHtml();
        $this->_cacheResult();
    }
    
    
    public function _fetchDataFromRemoteRaw()
    {
        $this->connectorRemote();
        $this->_cacheResult();
        
        return $this->_body;
    }
    
    private function _dbr()
    {
        return Mage::getSingleton('core/resource')->getConnection('core_read');
    }
    
    private function _dbw()
    {
        return Mage::getSingleton('core/resource')->getConnection('core_write');
    }
    
    private function _fetchDataFromDatabase()
    {
        // This is just a key/value store.
        // NoSQL would be a better place to store this stuff than MySQL
        // Try lib/credis
        
        $sql    = 'SELECT body FROM apd_datacache WHERE request = ? LIMIT 1';
        $result = $this->_dbr()->fetchOne($sql, $this->_request);
        if (!empty($result)) {
            $this->_body = $result;
        }
    }
    
    private function _cacheResult()
    {
        $sql = 'INSERT INTO apd_datacache (request,body) VALUES (?,?)   
                ON DUPLICATE KEY UPDATE body=VALUES(body)';
        $this->_dbw()->query($sql, array(
            $this->_request,
            $this->_body
        ));
    }
    
    public function clearApiCache()
    {
        $sql = 'TRUNCATE TABLE apd_datacache';
        $this->_dbw()->query($sql);
    }
    
    private function _convertFromJsonToSelectHtml()
    {
        $result = json_decode($this->_body);
        $html   = '';
        $c      = 0;
        foreach ($result->Items as $row) {
            $html .= "<option value=\"{$row->Id}\">{$row->Name}</option>\r\n";
            $c++;
        }
        echo $html;
        $this->_body = $html;
    }
    
    public function getConfigurableOptions($configId,$sizes,$current_url = null)
    {
        $product          = Mage::getModel('catalog/product')->load($configId);
        $type_ar          = array(
            "product_type" => $product->getTypeId(),
            "product_price" => $product->getFinalPrice()
        );
               
        
        $sizes = array_unique($sizes);
        
         $size_count = count($sizes);
                       
        $attributeOptions = array();
        $attributeOptions1 = array();
                
        if ($product->getTypeId() == 'configurable') {
            
            $productAttributeOptions = $product->getTypeInstance(true)->getConfigurableOptions($product);
            foreach ($productAttributeOptions as $productAttribute) {
                foreach ($productAttribute as $attribute) {      
                    $configurable_Options[$attribute['option_title']] = $attribute['sku'];
                }
            }
         
            $onsale_items = $this->onsaleItem($product);
    
            $productAttributeOptions = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
            foreach ($productAttributeOptions as $productAttribute) {
            
                foreach ($productAttribute['values'] as $attribute) {
        		      
                    if(strpos($current_url,'onsale') === false){
                        $attributeOptions[] = array(
                            "id" => $attribute['value_index'],
                            "label" => $attribute['store_label'],
                             "p_id" => $configurable_Options[$attribute['store_label']]
                         );
                    }else{
                        
                        if(in_array($configurable_Options[$attribute['store_label']],$onsale_items)){
                            $attributeOptions[] = array(
                              "id" => $attribute['value_index'],
                              "label" => $attribute['store_label'],
                              "p_id" => $configurable_Options[$attribute['store_label']]
                            );
                        }
                    }
                        
                }
            }
        }
        
        for($i=0;$i<=$size_count;$i++) {
			for($j=0;$j<=sizeof($attributeOptions);$j++) {
				if($sizes[$i]!='') {							
					if($attributeOptions[$j]['label']==$sizes[$i]){
						$attributeOptions1[] = array(
	                        "id" => $attributeOptions[$j]['id'],
	                        "label" => $attributeOptions[$j]['label'],
                                "p_id" => $configurable_Options[$attributeOptions[$j]['label']]             
	                    );
					} else {
						if(strlen($sizes[$i])<=2 && $sizes[$i]== substr($attributeOptions[$j]['label'],0,2)) {
							$attributeOptions1[] = array(
		                        "id" => $attributeOptions[$j]['id'],
		                        "label" => $attributeOptions[$j]['label'],
                                        "p_id" => $configurable_Options[$attributeOptions[$j]['label']]
		                    );
						}
					}
				}
			}
		}
        
        
        if(sizeof($attributeOptions1)>0) {        	
			$final_ar = $attributeOptions1;
		} else {			
			$final_ar = $attributeOptions;
		}
		
			
        
        $this->sksort($final_ar, "label", true);
        
        $sizes_ar[Sizes] = $final_ar;
        
        #$options = $this->getCustomOptions($configId);		
        $new_values = array_merge($type_ar, $sizes_ar);
        
        
        
        return $new_values;
    }
    
    
   public function onsaleItem($product){
    
    $rs = array();
    $childProducts = Mage::getModel('catalog/product_type_configurable')						
                                        ->getUsedProductCollection($product)
                                        ->addAttributeToSelect(array('overlay','sku'))
                                        ->addAttributeToFilter('overlay',712);
    foreach ($childProducts as $cp) {
            $rs[] = $cp->getSku();
        }
       
        return $rs;
   }
	
	
    
    public function sksort(&$array, $subkey = "id", $sort_ascending = false)
    {       
        
        if (count($array))
            $temp_array[key($array)] = array_shift($array);
        
        foreach ($array as $key => $val) {
            $offset = 0;
            $found  = false;
            foreach ($temp_array as $tmp_key => $tmp_val) {
                if (!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey])) {
                    $temp_array = array_merge((array) array_slice($temp_array, 0, $offset), array(
                        $key => $val
                    ), array_slice($temp_array, $offset));
                    $found      = true;
                }
                $offset++;
            }
            if (!$found)
                $temp_array = array_merge($temp_array, array(
                    $key => $val
                ));
        }
        
        if ($sort_ascending)
            $array = array_reverse($temp_array);
        
        else
            $array = $temp_array;
        
        return $array;
    }
    
    public function getCustomOptions($productId)
    {
        $product = Mage::getModel('catalog/product');
        $product->load($productId);
        $options = array();
        
        if ($product->getData('has_options')) {
            foreach ($product->getOptions() as $o) {
                $optionType = $o->getType();
                $values     = $o->getValues();
                $option_ar  = array();
                if (count($values) > 0) {
                    foreach ($values as $k => $v) {
                        $option_ar = array(
                            "option_title" => $v->getTitle(),
                            "option_sku" => $v->getSku(),
                            "option_sku" => $v->getPrice(),
                            "option_option_id" => $v->getOptionId()
                        );
                    }
                }
                $values_ar            = array(
                    "type" => $optionType,
                    "title" => $o->getTitle(),
                    "sku" => $o->getSku(),
                    "price" => $o->getPrice(),
                    "option_id" => $o->getOptionId(),
                    "options_children" => $option_ar
                );
                $options['Options'][] = $values_ar;
            }
        }
        
        return $options;
    }
    
    
    
    //    private function getNameId(fromClassName, params, toClassName, label) {    	
    //        var options = '';
    //        options += '<option value="">Loading..</option>';
    //        jQuery(toClassName).html(options);
    //
    //        jQuery.getJSON(params, {}, function(j) {
    //            var options = '';          
    //            options += '<option value="">' + label + '</option>';
    //            for (var i = 0; i < j.Total; i++) {
    //                options += '<option value="' + j.Items[i].Id + '">' + j.Items[i].Name + '</option>';
    //            }			
    //            jQuery(toClassName).html(options);
    //        })
    //    }
    
    //        function getRanges(fromClassName, params, toClassName, label) {
    //        var options = '';
    //        options += '<option value="">Loading..</option>';
    //        jQuery(toClassName).html(options);
    //        jQuery.getJSON(params, {}, function(j) {
    //            var options = '';
    //            options += '<option value="">' + label + '</option>';
    //            for (var i = 0; i < j.Total; i++) {
    //                options += '<option value="' + j.Items[i] + '">' + j.Items[i] + '</option>';
    //            }
    //            jQuery(toClassName).html(options);
    //        })
    //    }
    
}
