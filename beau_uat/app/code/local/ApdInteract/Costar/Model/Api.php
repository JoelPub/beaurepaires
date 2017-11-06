<?php
/**
 * Costar API call
 *
 * Class ApdInteract_Costar_Model_Api
 * @category	ApdInteract
 * @package		ApdInteract_Costar
 * @author		Jagdeep
 *
 */
class ApdInteract_Costar_Model_Api extends Mage_Core_Model_Abstract
{

    /**
     * Costar API call Header Fields
     * @var array
     */
    protected $httpHeader = array(
        "Content-type: application/json",
        "CostarSigningAlgorithm: HMAC-SHA256",
        "CostarCipher: AES256",
    );

    /**
     * Costar API call Body Fields
     * @var array
     */
    protected $httpBodyFieldsJsonEncode = "";

    /**
     * @var string
     */
    protected $baseURL = "";

    /**
     * @var string
     */
    protected $applicationId = "";

    /**
     * @var string
     */
    protected $secretKey = "";

    /**
     * @var string
     */
    protected $encryptedText = '';

    /**
     * @var string
     */
    protected $method = "AES-256-CBC";

    /**
     * Default Connection Time out 10 sec
     * @var int
     */
    protected $connectionTimeout = 10;

    /**
     *
     * Pre-Validation
     * - if Costar API call enabled from admin
     * - URL, SecretKey and Application ID not empty
     * @return bool
     */
    protected function _preValidation() {

        if(!Mage::helper('costar/api')->isEnabled()) {
            Mage::helper('costar/api')->log("Costar API is not Active");
            return false;
        }        
        
        
        
        $this->baseURL = Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_api/costar_api_url');
        $secretKey = Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_api/costar_api_secret_key');
        $this->applicationId =  Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_api/costar_api_applicationid');

        $timeOutInSec = Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_api/costar_api_timeout');
        if(!empty($timeOutInSec) || is_numeric($timeOutInSec))
            $this->connectionTimeout = $timeOutInSec;

        if(empty($this->baseURL) || filter_var($this->baseURL, FILTER_VALIDATE_URL) === false || empty($secretKey) || empty($this->applicationId)){
            Mage::helper('costar/api')->log("Costar Credentials are not valid, Please update from Admin");
            return false;
        }

        $this->secretKey = base64_decode($secretKey);

        return true;

    }

    /**
     * Used to Test Signature And Encryption
     *
     * @return bool|string
     */
    public function testSignatureAndEncryption(){        
        if(!$this->_preValidation())
           return false;

        $simpleText = "Hello. This is plain text.";
        $costarLiveId = "8199";
        
        //Encrypt some text, for the parameter, p_Encrypted
        $encryptedText = $this->_encryptPassword($simpleText);

        //Create HTTP Request Body in Json format
        $messageBody= array("p_CostarLiveId"=>$costarLiveId,
            "p_Encrypted"=>$encryptedText[0],
            "p_PlainText"=>$simpleText);
      
        // An HTTP header indicates that p_Encrypted is encrypted
        //$header[]= "CostarCipheredFieldCsv: p_Encrypted";

        $this->httpBodyFieldsJsonEncode = json_encode($messageBody);

        //Create Signature and added into HTTP Request
        $header =  $this->_signRequest();
        array_push($header,$encryptedText[1],"CostarCipheredFieldCsv: p_Encrypted");

        //Make a API CAll
        $response = $this->_apicall("TestSignatureAndEncryption", $header);

        return $response;

    }

    /**
     *
     * Make Stock Query Call
     *
     * @return mixed
     */
    public function stockQuery($fieldsArray=array())
    {

        if (empty($fieldsArray) || empty($fieldsArray['costarLiveId']) || empty($fieldsArray['branchPassword']) || empty($fieldsArray['branchCode']) || empty($fieldsArray['itemSku'])) {
            Mage::helper('costar/api')->log("Invalid Fields to make a StockQuery API call :");
            Mage::helper('costar/api')->log($fieldsArray);
            return array("error" => true, "costarQty" => 0, "message" => "");
        }


        //Pre Validation to check required Credentials
        if (!$this->_preValidation())
        {
            return array("error" => true, "costarQty" => 0, "message" => "");
        }

        //Encrypt some text, for the parameter, p_Encrypted
        $encryptedText_array = $this->_encryptPassword($fieldsArray['branchPassword']);

        //Create HTTP Request Body in Json format
        $messageBody= array(
            "p_CostarLiveId"   => $fieldsArray['costarLiveId'],
            "p_BranchCode"     => $fieldsArray['branchCode'],
            "p_BranchPassword" => $encryptedText_array[0],
            "p_Item"           => $fieldsArray['itemSku']);
        //$header = array();
        // An HTTP header indicates that p_BranchPassword is encrypted
        //$header[] = "CostarCipheredFieldCsv: p_BranchPassword";

        $this->httpBodyFieldsJsonEncode = json_encode($messageBody);

        //Create Signature and added into HTTP Request
        $header = $this->_signRequest();
        array_push($header,$encryptedText_array[1],"CostarCipheredFieldCsv: p_BranchPassword");

        //Make a API CAll for StockQuery
        $response = $this->_apicall("StockQuery", $header);
        
        if($response['status_code']==200){
            $result = $response['result'];
            $return = array("error"=>false,"costarQty"=>$result['AvailableQuantity'],"message"=>"");
        }elseif($response['status_code']==400){
            $message = $response['result']['Message'];
            $return = array("error"=>true,"costarQty"=>0,"message"=>"");

            preg_match('/\#(.*?)\#/', $message, $errorMessage);
            if(isset($errorMessage[1])){
                $errorDescription = $this->costarApiErrorMessages($errorMessage[1]);
            }
        }else{
            $return = array("error"=>true,"costarQty"=>0,"message"=>"");
        }

        return $return;
    }

    /**
     *
     * Make Api call to submit Order Info into Costar
     *
     * @param array $fieldsArray
     * @return array
     */
    public function submitOrder($fieldsArray=array(),$orderInfo =array())
    {
        
        //Pre Validation to check required Credentials
        if(!$this->_preValidation()) {
            return array("error" => true, "costarQty" => 0, "message" => "");
        }

        //Encrypt some text, for the parameter, p_Encrypted
        $encryptedText_array = $this->_encryptPassword($fieldsArray['branchPassword']);

        //Create HTTP Request Body in Json format
        $messageBody= array(
            "p_CostarLiveId"   => $fieldsArray['costarLiveId'],
            "p_BranchCode"     => $fieldsArray['branchCode'],
            "p_BranchPassword" => $encryptedText_array[0],
            "p_Order"          => $orderInfo);
        
        // An HTTP header indicates that p_BranchPassword is encrypted
        //$header= "CostarCipheredFieldCsv: p_BranchPassword";

        $this->httpBodyFieldsJsonEncode = json_encode($messageBody);

        //Create Signature and added into HTTP Request
        $header = $this->_signRequest();
        array_push($header,$encryptedText_array[1],"CostarCipheredFieldCsv: p_BranchPassword");

        try {
            //Make a API CAll for StockQuery
            $response = $this->_apicall("SubmitOrder", $header);
            
            //Filter Response
            if ($response['status_code'] == 200) {
                $result = $response['result'];
                $return = array("error" => false, "message" => $result);
            } elseif ($response['status_code'] == 400) {
                $message = $response['result']['Message'];
                /*preg_match_all('/\#(.*?)\#/', $message, $errorMessage); 
                //Mage::helper('costar/api')->log("ERROR CODE: ". print_r($errorMessage,true));
                if(isset($errorMessage[1][0]) && $errorMessage[1][0] !='' )
                    $error = $this->costarApiErrorMessages($errorMessage[1][0]);
                else
                    $error = $message;    
                */
                $return = array("error" => true, "message" => $message);
            } else {
                $return = array("error" => true, "message" => "Unknown");
            }
        } catch (Exception $e) {
            Mage::helper('costar/api')->log('ApdInteract_Costar_Model_Api::Costar api call Exception Error:');
            Mage::helper('costar/api')->log($e->getMessage());
            //Mage::helper('costar/api')->sendEmail("Exception Error",$e->getMessage());
            $return = array("error" => true, "message" => "Unknown");
        }
           
        return $return;

    }

    /**
     *
     * Make Costar API Call using CURL request
     * @return array
     */
    protected function _apicall($endPoint, $header, $sendEmail = true)
    {

        $gdtB2cAdapterBaseURL = $this->baseURL."/GdtB2cAdapter/Adapter.svc/rest/".$endPoint;
        Mage::helper('costar/api')->log("Costar API CALL URL: ".$gdtB2cAdapterBaseURL);
 
        try {

            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_URL, $gdtB2cAdapterBaseURL );
            curl_setopt ( $ch, CURLOPT_HEADER, false );
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
            curl_setopt ( $ch, CURLOPT_POST, TRUE );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $this->httpBodyFieldsJsonEncode );
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER,FALSE);
            curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST,FALSE);
            curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT,$this->connectionTimeout);

            $result = curl_exec ( $ch );
            $error = curl_error($ch);
            $status_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
            curl_close ( $ch );

            /**
             * Response always encoded with UTF-8 byte order mark BOM (EF, BB, BF)
             * will always be the first 3 bytes of the response body.
             * Remove first 3 bytes from response and decode json format to php array
             *  //$bom = pack("CCC", 0xef, 0xbb, 0xbf);
             *  //if (0 === strncmp($result, $bom, 3))
             *  //echo "BOM detected - file is UTF-8\n";
             */
             $result = substr($result, 3);
             $resultArray = json_decode($result,true);
            $response =  array("result"=>$resultArray,"status_code"=>$status_code, "error"=>$error);
            Mage::helper('costar/api')->log("Costar API Call Response: ");
            Mage::helper('costar/api')->log($response);
        } catch (Exception $e) {            
            Mage::helper('costar/api')->log('ApdInteract_Costar_Model_Api::Costar api call Exception Error:');
            Mage::helper('costar/api')->log($e->getMessage());
            //Mage::helper('costar/api')->sendEmail("Exception Error",$e->getMessage());

            $response =  array("result"=>"","status_code"=>"", "error"=>"Exception Error");
        }

        if(!$sendEmail) {
            return $response;
        }

        if(($response['status_code']!=200) && ($response['status_code']!=400) && !empty($response)){
            $message = "";
            $message .= "Error : ".$error."<br/>";
            $message .= "Status Code : ".$status_code."<br/>";
            $message .= "Result : ".$resultArray."<br/>";

            //print_r($message); die();

            Mage::helper('costar/api')->sendEmail("Error",$message);
        }

        return $response;

    }

    /**
     * Get Encripted Password
     *
     * @return string
     */
    protected function _encryptPassword($password)
    {
        $p_plainText = utf8_encode($password);

        /**
         * 256 bit AES requires a 32 bit key; use first 32 bytes of 64 byte secret key
         * //$byte32 = mb_strcut ($this->secretKey,0,32);
         * We tried to use mb_strcut () fucntion to get 32 byte but its always reutn 31 bytes.
         *
         */
        $byte64 = unpack ( "C*", $this->secretKey );
        $byte32 = array_slice ( $byte64, 0, 32 );
        $parameter = array_merge(array("C*"), $byte32);
        //Generate initialization vector IV random 16 bytes
        $iv_length = openssl_cipher_iv_length($this->method);
        $costarCipherIv = openssl_random_pseudo_bytes($iv_length);
        $costarCipherIvBase64 = base64_encode($costarCipherIv);

        //Get Encripted Password
        $encriptedText = openssl_encrypt($p_plainText, $this->method, call_user_func_array("pack", $parameter), OPENSSL_RAW_DATA, $costarCipherIv);
        $encriptedText =  base64_encode($encriptedText);

        $header= "CostarCipherIv: ".$costarCipherIvBase64;

        Mage::helper('costar/api')->log("encryptText:".$encriptedText);

        return array($encriptedText,$header);

    }

    /**
     * Create Signature
     */
    protected function _signRequest()
    {
        //Get dynamic GUID
        $costarGuid = $this->getGUID();
        //ISO8601 UTC date
        $costarUtc = gmdate("Y-m-d\TH:i:s.O");
        $costarUtc = str_replace('+','',$costarUtc);
        $stringToHash = $this->applicationId.$costarGuid.$costarUtc.$this->httpBodyFieldsJsonEncode;
        $stringToHashUtf8 = utf8_encode($stringToHash);
        $costarSignature = base64_encode( hash_hmac ( 'sha256', $stringToHashUtf8 ,$this->secretKey, true ) );
        
        $header = array(
        "Content-type: application/json",
        "CostarSigningAlgorithm: HMAC-SHA256",
        "CostarCipher: AES256",
        );
       
        
        //$this->httpHeader[]= "CostarApplicationId: ".$this->applicationId;
        //$this->httpHeader[]= "CostarGuid: ".$costarGuid;
        //$this->httpHeader[]= "CostarUtc: ".$costarUtc;
        //$this->httpHeader[]= "CostarSignature: ".$costarSignature;
        
        $header[] = "CostarApplicationId: ".$this->applicationId;
        $header[] = "CostarGuid: ".$costarGuid;
        $header[] = "CostarUtc: ".$costarUtc;
        $header[]= "CostarSignature: ".$costarSignature;
        
        $current = array_merge($this->httpHeader,$header);

        Mage::helper('costar/api')->log("Costar API CALl HTTP HEADER: ");
        Mage::helper('costar/api')->log($current);
        Mage::helper('costar/api')->log("Costar API CALl HTTP Body: ". $this->httpBodyFieldsJsonEncode);
        
        return $header;
    }

    /**
     *
     * Generate Unique GUID
     * @return string
     */
    private function getGUID()
    {
        if (function_exists('com_create_guid')){
            return trim(com_create_guid(),'{}');
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
            return trim($uuid,'{}');
        }
    }


    /**
     * Get Costar API error code and description
     * @param $errorCode
     * @return mixed
     */
    private function costarApiErrorMessages($errorCode){

        $errorMessages = array();

        //Common Error Codes -  HTTP 400 (Bad Request) response
        $errorMessages['CostarLiveIdMismatch'] = "The database costarLiveIId not match";
        $errorMessages['DatabaseError'] = "Could not connect to the database, related to p_CostarLiveId.";
        $errorMessages['DecryptionError'] = "An encrypted parameter, like p_BranchPassword, failed to be decrypted.";
        $errorMessages['EmptyCostarLiveId'] = "The p_CostarLiveId parameter cannot be empty.";
        $errorMessages['EmptyBranchCode'] = "The p_BranchCode parameter cannot be empty.";
        $errorMessages['EmptyBranchPassword'] = "The p_BranchPassword parameter cannot be empty";
        $errorMessages['GdtApiDisabled'] = "The GDT API is not enabled for the store related to p_CostarLiveId. Enable the GDT API in Order Entry Options.";
        $errorMessages['InvalidApplicationId'] = "The HTTP header, CostarApplicationId, must be GDTAPI ";
        $errorMessages['InvalidBranch'] = "No branch could be found that matches p_BranchCode.";
        $errorMessages['InvalidBranchPassword'] = "The p_BranchPassword parameter does not match the expected branch password.";
        $errorMessages['InvalidHttpHeader'] = "An HTTP header has an unexpected value. Following this error code is the name of the missing header, along with a description of the problem.";
        $errorMessages['InvalidMessageBody'] = "The HTTP Message body cannot be empty.";
        $errorMessages['InvalidSignature'] = "The signature in the HTTP header, CostarSignature, is not valid.";
        $errorMessages['MissingBranchPassword'] = "The ApplicationBranchSetting table does not contain a BranchPassword for the branch specified by p_BranchCode";
        $errorMessages['MissingConnectionString'] = "No connection string found that matches p_CostarLiveId";
        $errorMessages['MissingHttpHeader'] = "A required HTTP header is missing. The name of the missing header will follow this error code.";
        $errorMessages['RequestTooOld'] = "For the standard protocol, requests must be received within 15 minutes of their creation.";
        $errorMessages['SimpleProtocol'] = "When using the simple protocol: (1) digital signatures are not allowed   (2) ciphered (encrypted) fields are not allowed";

        //Error Codes (returned in the message field of an HTTP 400 error)
        $errorMessages['EmptyItem'] = "The p_Item parameter cannot be empty.";
        $errorMessages['NotFound'] = "The item specified by p_Item could not be found. ";
        $errorMessages['InvalidUnitNetTaxIn'] = "InvalidUnitNetTaxIn";

        return isset($errorMessages[$errorCode])? $errorMessages[$errorCode] : '';

    }

}