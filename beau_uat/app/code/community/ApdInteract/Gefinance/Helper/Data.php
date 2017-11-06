<?php
// See documentation - http://assets.gecapital.com.au/applyandbuy/api/api-apply-guide.asp    

class ApdInteract_Gefinance_Helper_Data extends Mage_Core_Helper_Abstract
{
    private $_aesKey;
    
    // Set to true to enable testing debug to screen
    private $_testing = false;
        
    public function getAvailableTerms()
    {
    	$grandtotal = Mage::getModel('checkout/cart')->getQuote()->getGrandTotal();
    	$available_terms    = $this->getConfig('ge_term');
        $available_terms_ar = explode(",", $available_terms);
        $allterms = $this->getAllTerms();
        $terms = array();
        foreach($available_terms_ar as $term) {
        	$label = $allterms[$term];
        	$minimumPurchaseAmt = $this->getConfig('ge_' . $term . 'mo_min');
        	if ($minimumPurchaseAmt <= $grandtotal) {
        		$terms[] = array("label"=>$label, "value"=>$term);
        	}
        }

        return $terms;
    }

    public function getAllTerms() {
		$terms = Mage::getModel('gefinance/system_config_source_geterm')
        				   ->toArray();

        return $terms;
	}

    public function getConfig($key)
    {        
        $promo_code = Mage::getStoreConfig('payment/gefinance/' . $key);
        return $promo_code;
    }

    public function getmicrotime()
    {
        list($usec, $sec) = explode(" ", microtime());
        $usec = (int) ((float) $usec * 1000);
        while (strlen($usec) < 3) {
            $usec = "0" . $usec;
        }
        return $sec . $usec;
    }

    public function hopHash($data, $key)
    {
        return base64_encode($this->_php_hmacsha1($data, $key));
    }

    public function _php_hmacsha1($data, $key)
    {
        $klen = strlen($key);
        $blen = 64;
        $ipad = str_pad("", $blen, chr(0x36));
        $opad = str_pad("", $blen, chr(0x5c));

        if ($klen <= $blen) {
            while (strlen($key) < $blen) {
                $key .= "\0";
            } #zero-fill to blocksize
        } else {
            $key = $this->_cybs_sha1($key); #if longer, pre-hash key
        }
        $key = str_pad($key, strlen($ipad) + strlen($data), "\0");
        return $this->_cybs_sha1(($key ^ $opad) . $this->_cybs_sha1($key ^ $ipad . $data));
    }



    public function _cybs_sha1($in)
    {

        if (function_exists('sha1')) {
            return pack("H*", sha1($in));
        }

        $indx  = 0;
        $chunk = "";

        $A = array(
            1732584193,
            4023233417,
            2562383102,
            271733878,
            3285377520
        );
        $K = array(
            1518500249,
            1859775393,
            2400959708,
            3395469782
        );
        $a = $b = $c = $d = $e = 0;
        $l = $p = $r = $t = 0;

        do {
            $chunk = substr($in, $l, 64);
            $r     = strlen($chunk);
            $l += $r;

            if ($r < 64 && !$p++) {
                $r++;
                $chunk .= "\x80";
            }
            $chunk .= "\0\0\0\0";
            while (strlen($chunk) % 4 > 0) {
                $chunk .= "\0";
            }
            $len = strlen($chunk) / 4;
            if ($len > 16)
                $len = 16;
            $fmt = "N" . $len;
            $W   = array_values(unpack($fmt, $chunk));
            if ($r < 57) {
                while (count($W) < 15) {
                    array_push($W, "\0");
                }
                $W[15] = $l * 8;
            }

            for ($i = 16; $i <= 79; $i++) {
                $v1 = d($W, $i - 3);
                $v2 = d($W, $i - 8);
                $v3 = d($W, $i - 14);
                $v4 = d($W, $i - 16);
                array_push($W, L($v1 ^ $v2 ^ $v3 ^ $v4, 1));
            }

            list($a, $b, $c, $d, $e) = $A;

            for ($i = 0; $i <= 79; $i++) {
                $t0 = 0;
                switch (intval($i / 20)) {
                    case 1:
                    case 3:
                        $t0 = F1($b, $c, $d);
                        break;
                    case 2:
                        $t0 = F2($b, $c, $d);
                        break;
                    default:
                        $t0 = F0($b, $c, $d);
                        break;
                }
                $t = M($t0 + $e + d($W, $i) + d($K, $i / 20) + L($a, 5));
                $e = $d;
                $d = $c;
                $c = L($b, 30);
                $b = $a;
                $a = $t;
            }

            $A[0] = M($A[0] + $a);
            $A[1] = M($A[1] + $b);
            $A[2] = M($A[2] + $c);
            $A[3] = M($A[3] + $d);
            $A[4] = M($A[4] + $e);

        } while ($r > 56);
        $v = pack("N*", $A[0], $A[1], $A[2], $A[3], $A[4]);
        return $v;
    }

    #### Ancillary routines used by sha1

    public function dd($x)
    {
        if (defined($x))
            return $x;
        return 0;
    }

    public function d($arr, $x)
    {
        if ($x < count($arr))
            return $arr[$x];
        return 0;
    }

    public function F0($b, $c, $d)
    {
        return $b & ($c ^ $d) ^ $d;
    }

    public function F1($b, $c, $d)
    {
        return $b ^ $c ^ $d;
    }

    public function F2($b, $c, $d)
    {
        return ($b | $c) & $d | $b & $c;
    }

    # ($num)
    public function M($x)
    {
        $m = 1 + ~0;
        if ($m == 0)
            return $x;
        return ($x - $m * intval($x / $m));
    }

    # ($string, $count)
    public function L($x, $n)
    {
        return (($x << $n) | ((pow(2, $n) - 1) & ($x >> (32 - $n))));
    }

    public function VerifySignature($data, $signature, $publicKey)
    {
        $pub_digest = $this->hopHash($data, $publicKey);
        return strcmp($pub_digest, $signature) == 0;
    }

    public function VerifyTransactionSignature($inputMap, $publicKey)
    {
         if (count($inputMap)<=0) {
            return false;
         }

        $transactionSignature       = $inputMap['signedDataPublicSignature'];
        $transactionSignatureFields = $inputMap['signedFields'];

        $tokenizer = explode(",", $transactionSignatureFields);
        $data      = "";
        while (list($key, $value) = each($tokenizer)) {
            $data .= $value . "=" . $inputMap[$value] . ",";
        }

        $publicKey = $this->getConfig('ge_key');
        $data .= "signedFieldsPublicSignature=";
        $data .= $this->hopHash($transactionSignatureFields, $publicKey);
        return $this->verifySignature($data, $transactionSignature, $publicKey);
    }
    
    private function _getAesKey() {
        if (!isset($this->_aesKey)) {
            $lib_dir = Mage::getBaseDir('lib');
            $path = $lib_dir . '/GeFinance/';
            $filename = $this->getConfig('ge_keyfile_name');
            if (empty($filename)) {
                $filename = '415_0_SYMMETRIC.key';
            }            
            $this->_aesKey = file_get_contents($path . $filename);                        
        }
        
        if (empty($this->_aesKey)) {
                throw new Exception('AES key is required, but was not read: check keyfile exists in /lib/GeFinance and is correctly named and readable');
            }
        
        return $this->_aesKey;
    }
    
    private function _getAesKeyBase64Encoded() {
        $aes_key = $this->_getAesKey();
        return base64_encode($aes_key);        
    }
    
    public function getAesKeyBase64Encoded() {
        return $this->_getAesKeyBase64Encoded();
    }
    
    private function _includeGeApplyLibs() {
        // Include required classes from plugin
        $lib_dir = Mage::getBaseDir('lib');

        include_once $lib_dir . '/GeFinance/util/merchantConstants.php';
        
        // required for requestGenerator.php to work
        include_once $lib_dir . '/GeFinance/util/phpAESHelperCode.php';
        include_once $lib_dir . '/GeFinance/exception/customException.php';
//        include_once $lib_dir . '/GeFinance/apply/param/merchantRequestParameter.php';
//        include_once $lib_dir . '/GeFinance/merchantConstants.php';
        
        // responseProcessor.php includes
//        include_once $lib_dir . '/GeFinance/exception/customException.php';
//        include_once $lib_dir . '/GeFinance/apply/param/merchantReponseParameter.php';
//        include_once $lib_dir . '/GeFinance/util/merchantConstants.php';
        
        // generatorUtility.php include
        include_once $lib_dir . '/GeFinance/buy/cybs/param/buyMerchantRequestResponseParameter.php';
        
        include_once $lib_dir . '/GeFinance/apply/param/merchantRequestParameter.php';
        include_once $lib_dir . '/GeFinance/apply/request/requestGenerator.php';
        include_once $lib_dir . '/GeFinance/apply/response/responseProcessor.php';
        include_once $lib_dir . '/GeFinance/buy/request/generator/generatorUtility.php';
    }

    private function _getApplyInputParameters() {

        // Need to include GE libs before this function will work.               
        $merchant_id = $this->getConfig('merchant_id');
        if (empty($merchant_id)) {
            // $merchant_id = '000100100';
            // Or throw "Need to supply a merchant ID in system > config > payment methods > GE Finance" exception
            throw new Exception('Merchant ID is required, but empty: System > Configuration > Payment Methods > Ge Finance');
        }
        $gem_id = $this->getConfig('ge_gem_id');
        if (empty($gem_id)) {
            // $gem_id = 'abc123';
            // Or throw "Need to supply a gem1 ID in system > config > payment methods > GE Finance" exception
            throw new Exception('Gem1 ID is required, but empty: System > Configuration > Payment Methods > Ge Finance');
        }
        $ip_address = Mage::helper('core/http')->getRemoteAddr();
        
        
        // Test lines
//        $merchant_id = '000100113';
//        $ip_address = '127.0.0.1';
//        $gem_id = 'APIPluginPHP';        
        
        // End test lines
        
        
        // Put all parameters into an ordered map for generating request parameters
        $inputParameters = array (
            MerchantRequestParameter::$MERCHANT_ID => $merchant_id,                                     // Merchant ID
            MerchantRequestParameter::$SOURCE_FLAG => '0',                                              // 0 = Website
            MerchantRequestParameter::$CARD_TYPE => 'gemvisaau',                                        // Default option
            MerchantRequestParameter::$IP_ADDRESS => $ip_address,    // Customer's IP address
            MerchantRequestParameter::$RETURN_URL => Mage::helper('core/url')->getCurrentUrl(),         // URL this script is called from
            MerchantRequestParameter::$STREAM => 'Upstream',                                            // or DownStream if from a payment gateway
            MerchantRequestParameter::$CHANNEL => 'Online',                                             // or Retail
            MerchantRequestParameter::$GEMID1 => $gem_id                                                // Default to abc123
        );

        // Optional fields
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
 
            // Load the customer's data
            $customer = Mage::getSingleton('customer/session')->getCustomer();
            $inputParameters[MerchantRequestParameter::$FIRST_NAME] = $customer->getFirstname(); // First Name
            $inputParameters[MerchantRequestParameter::$LAST_NAME] = $customer->getLastname(); // Last Name
            
            // This won't work as we don't store customer addresses - unless you count na na na na.
            // Leaving this in in case this changes later
            // 
//            $customerAddressId = Mage::getSingleton('customer/session')->getCustomer()->getDefaultBilling();
//            if ($customerAddressId) {
//                $address = Mage::getModel('customer/address')->load($customerAddressId);
//                $inputParameters[MerchantRequestParameter::$UNIT_NO] = "unitNo";
//                $inputParameters[MerchantRequestParameter::$STREET_NO] = "streetNo";
//                $inputParameters[MerchantRequestParameter::$PROPERTY_STREET_NAME] = "streetName";
//                $inputParameters[MerchantRequestParameter::$STREET_TYPE] = "streetType";
//                $inputParameters[MerchantRequestParameter::$CITY_TOWN_SUBURB] = $address->getCity();
//                $inputParameters[MerchantRequestParameter::$STATE] = $address->getRegion();
//                $inputParameters[MerchantRequestParameter::$POST_CODE] = $address->getPostcode();
//                
//                                                
//                $mydatas['name'] = $address->getFirstname().' '.$address->getLastname();
//                $mydatas['company'] = $address->getCompany();
//                $mydatas['zip'] = $address->getPostcode();
//                $mydatas['state'] = $address->getRegion();
//                $mydatas['city'] = $address->getCity();
//                $street = $address->getStreet();
//                $mydatas['street'] = $street[0];
//                $mydatas['telephone'] = $address->getTelephone();
//                $mydatas['fax'] = $address->getFax();
//                $mydatas['country'] = $address->getCountry();
//            }
                        

        }
        
        return $inputParameters;
    }

    private function _generateRequest($parameters) {
        
        RequestGenerator::validateRequestParameter($parameters); // Will throw exception if invalid
        $generatedRequest = RequestGenerator::generateRequestString($parameters);
        $generatedEncryptedRequest = $this->_encryptGeString($generatedRequest);

        $merchantRequest = array(
            MerchantRequestParameter::$MERCHANT_ID => $parameters [MerchantRequestParameter::$MERCHANT_ID],
            MerchantRequestParameter::$SOURCE_FLAG => $parameters [MerchantRequestParameter::$SOURCE_FLAG],
            MerchantRequestParameter::$IN_DATA_BLOCK => $generatedEncryptedRequest
        );
        return $merchantRequest;
    }
    
    private function _encryptGeString($string) {        
        $strb64Key = $this->_getAesKeyBase64Encoded(); // _getAesKey();
        
        if (empty($strb64Key)) {
            throw new Exception('AES key is required, but empty: System > Configuration > Payment Methods > Ge Finance');
        }
                
        $generatedEncryptedRequest = fnEncrypt($string, $strb64Key);
        return $generatedEncryptedRequest;
    }
    
    private function _decryptGeString($string) {
        $this->_includeGeApplyLibs();        
        $strb64Key = $this->_getAesKeyBase64Encoded();
        $outputDecrypted = fnDecrypt($string, $strb64Key);
        $response = $this->_splitResponseMerchantData($outputDecrypted);
        return $response;
    }
    
    public function encryptGeString($string) {
        $this->_includeGeApplyLibs();
        return $this->_encryptGeString($string);        
    }
    
    private function _getGeApplyFields() {
        if (! $this->getConfig('active')) {
            throw new Exception('Ge Finance is disabled. Enable it in System > Configuration > Payment Methods > Ge Finance');                
        }

        $this->_includeGeApplyLibs();
        $apply_inputs = $this->_getApplyInputParameters();
        $encoded_fields = $this->_generateRequest($apply_inputs); 
        
        if ($this->_testing) {
            // Test lines
            $encoded_fields['apply_inputs'] = $apply_inputs;
            $encoded_fields['decoded_apply_inputs'] = $this->_decryptGeString($encoded_fields[MerchantRequestParameter::$IN_DATA_BLOCK]);
            $encoded_fields['aes_key_encoded'] = $this->_getAesKeyBase64Encoded();
        }
        
        return $encoded_fields;        
    }
    
    public function getMerchantEappsData() {
        try {
            $output = $this->_getGeApplyFields();
            return $output[MerchantRequestParameter::$IN_DATA_BLOCK];
            
        } catch (Exception $e) {
            Mage::logException($e);            
            return '';         
        }    
    }
    
    public function getGeApplyForm() {
        try {
            $testing = true;
            
            $output = $this->_getGeApplyFields();
            $form = '';
            
            if ($this->_testing) {
                $form .= "<!-- Testing vars: 
                    apply_inputs: " . print_r($output['apply_inputs'], true) . "                     
                    decrypted_eappsData: " . print_r($output['decoded_apply_inputs'], true) . " 
                    aes_key_encoded: " . print_r($output['aes_key_encoded'], true) . "
                    MerchantEappsData: " . $this->getMerchantEappsData() . "    
                    -->"; 
            }
            
            $form .= <<<EOF
                                                                
            <form name="merchantForm" action="https://onlinepaymentintegration.gemoney.com.au/eapps/ApplyRetail.faces" method="POST">
                <input type="hidden" name="merchantID" value="{$output [MerchantRequestParameter::$MERCHANT_ID]}">
                <input type="hidden" name="source" value="{$output [MerchantRequestParameter::$SOURCE_FLAG]}">
                <input type="hidden" name="merchant_eappsData" value="{$output [MerchantRequestParameter::$IN_DATA_BLOCK]}">
                <input type="submit" name="Submit" value="Apply Now" class="button radius">
            </form>
EOF;

            return $form;
            
        } catch (Exception $e) {
            Mage::logException($e);            
            $html = "<!-- GE FINANCE EXCEPTION: {$e->getMessage()} -->";
            $html .= 'Apply is currently unavailable. Please try again later.';
            return $html;          
        }
    }
    
    public function getHopDataFromSession() {
        return Mage::getSingleton('core/session')->getEappsHopData();
    }
    
    public function setHopDataInSession($eapps_hopData) {
        Mage::getSingleton('core/session')->setEappsHopData($eapps_hopData);
    }
    
    private function _splitResponseMerchantData($outputDecrypted) {
        $finalParams = array();
        // Split the $outputDecrypted string on the hat (^) charater
        $parts = explode("^", $outputDecrypted);

        // Iterate over each $part and..
        foreach($parts as $val){
            // ..split again on the equals (=) character
            $pair = explode("=", $val);
            // Store the key and value in the array
            // In $pair, array index 0 is the name, and index 1 is the value
            $finalParams[$pair[0]] = $pair[1];
        }
        return $finalParams;
    }
    
	
	public function _decryptApplyResponseMerchantData($eapps_merchantData) {
        $this->_includeGeApplyLibs();        
        $strb64Key = $this->_getAesKeyBase64Encoded();
        $outputDecrypted = fnDecrypt($eapps_merchantData, $strb64Key);
        $response = $this->_splitResponseMerchantData($outputDecrypted);
        return $response;
    }
    
    public function handleGeApplyResponse($eapps_merchantData, $eapps_hopData) {
        try {
            if (empty($eapps_merchantData) || empty($eapps_hopData)) {
                // If both params aren't set, then do nothing
                return;
            }
            
            $this->setHopDataInSession($eapps_hopData);
            
             $response = $this->_decryptApplyResponseMerchantData($eapps_merchantData);
			
			switch ($response['eapps_appStatus']) {
				case "Declined":
					$alertClass = "warning";
					$alertMsg = "Unfortunately your Gem Visa application was not approved. Please continue to the checkout to use another payment method.";
					break;
				case "Approved":
					$alertClass = "info";
					$alertMsg = "Congratulations, your Gem Visa application was approved! Continue to the checkout to use your new Gem Visa.";
					break;
				case "Pending":
					$alertClass = "info";
					$alertMsg = "Your Gem Visa application is in progress, please follow the instructions received by email to complete the application, or continue with another payment method.";
					break;
				case "Incomplete":
					$alertClass = "warning";
					$alertMsg = "Your Gem Visa application is incomplete. Please Return to Gem Visa to complete your application or try another payment method.";
					break;
				default:
					$alertMsg = "";
			}
			
			if ($alertMsg != "") {
				Mage::getSingleton('core/session')->addNotice('<li class="alert-box '.$alertClass.' radius">'.$alertMsg.'</li>');
			}
             
            
            // $response = $this->_decryptApplyResponseMerchantData($eapps_merchantData);
            // uncomment if you want to do something with the merchant response.

        } catch (exception $e) {
            Mage::logException($e);
            // silently
        }
        
    }

}
