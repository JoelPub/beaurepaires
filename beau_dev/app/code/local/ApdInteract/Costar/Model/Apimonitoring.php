<?php
/**
 * Costar API Monitoring call
 *
 * Class ApdInteract_Costar_Model_Api
 * @category	ApdInteract
 * @package		ApdInteract_Costar
 * @author		Jagdeep
 *
 */
class ApdInteract_Costar_Model_Apimonitoring extends ApdInteract_Costar_Model_Api
{
    /**
     * Ping Costar API to see if connection working fine
     *
     * @return array
     */
    public function ping(){

        if(!Mage::getStoreConfig('apimonitoring/costarapi/api_enabled')) {
            Mage::helper('costar/api')->log("Costar API Monitoring is not Active");
            return array("error" => false, "message" => "Costar API Monitoring is not Active");
        }

        //Pre Validation to check required Credentials
        if(!$this->_preAPIMonitoringValidation()) {
            return array("error" => true, "message" => "Invalid Costar API Monitoring Credentials");
        }

        //Create HTTP Request Body in Json format
        $messageBody= array("p_PlainText"   => "Hello. This is plain text.");
        $this->httpBodyFieldsJsonEncode = json_encode($messageBody);


        $simpleText = "Hello. This is plain text.";
        $costarLiveId = "8200";

        //Encrypt some text, for the parameter, p_Encrypted
        $encryptedText = $this->_encryptPassword($simpleText);

        //Create HTTP Request Body in Json format
        $messageBody= array("p_CostarLiveId"=>$costarLiveId,
            "p_Encrypted"=>$encryptedText[0],
            "p_PlainText"=>$simpleText);

        $this->httpBodyFieldsJsonEncode = json_encode($messageBody);

        //Create Signature and added into HTTP Request
        $header =  $this->_signRequest();
        array_push($header,$encryptedText[1],"CostarCipheredFieldCsv: p_Encrypted");


        //Create Signature and added into HTTP Request
        $header = $this->_signRequest();

        try {
            //Make a API CAll Ping
            $response = $this->_apicall("TestSignatureAndEncryption", $header, false);

            //Filter Response
            if ($response['status_code'] == 200) {
                $result = $response['result'];
                $return = array("error" => false, "message" => $result,"code" => $response['status_code']);
            } elseif ($response['status_code'] == 400) {
                $message = $response['result']['Message'];
                if (strpos($message, "Error in Costar") !== false) {
                    $return = array("error" => true, "message" => $message,"code" => $response['status_code']);
                }else{
                    $return = array("error" => false, "message" => $message,"code" => $response['status_code']);
                }
            } else {
                $return = array("error" => true, "message" => (isset($response['error'])) ? $response['error'] : "Unkown","code" => $response['status_code']);
            }

        } catch (Exception $e) {
            Mage::helper('costar/api')->log('ApdInteract_Costar_Model_Api::ping Costar api call monitoring Exception Error:');
            Mage::helper('costar/api')->log($e->getMessage());
            $return = array("error" => true, "message" => $e->getMessage());
        }

        return $return;
    }


    /**
     *
     * Pre-API monitoring Validation
     * - if Costar API call enabled from Api Provider Monitoring admin
     * - URL, SecretKey and Application ID not empty
     * @return bool
     */
    protected function _preAPIMonitoringValidation() {

        $this->baseURL = Mage::getStoreConfig('apimonitoring/costarapi/api_url');
        $secretKey = Mage::getStoreConfig('apimonitoring/costarapi/api_secret_key');
        $this->applicationId =  Mage::getStoreConfig('apimonitoring/costarapi/api_applicationid');

        $timeOutInSec = Mage::getStoreConfig('apimonitoring/costarapi/api_timeout');
        if(!empty($timeOutInSec) || is_numeric($timeOutInSec))
            $this->connectionTimeout = $timeOutInSec;

        if(empty($this->baseURL) || filter_var($this->baseURL, FILTER_VALIDATE_URL) === false || empty($secretKey) || empty($this->applicationId)){
            Mage::helper('costar/api')->log("Costar API Monitoring Credentials are not valid, Please update from Admin");
            return false;
        }

        $this->secretKey = base64_decode($secretKey);

        return true;

    }
}