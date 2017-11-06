<?php
/**
 * Vehicle Logic API monotoring call
 *
 * Class ApdInteract_SearchTyre_Model_Apimonitoring
 * @category	ApdInteract
 * @package		ApdInteract_SearchTyre_Model
 * @author		Jagdeep
 *
 * Admin Config: System -> Configuration -> Beaurepaires -> Api provider monitoring -> Veehicle logic api monitoring
 *
 */

class ApdInteract_SearchTyre_Model_Apimonitoring extends ApdInteract_SearchTyre_Model_Searchtyre
{

    private $_vlUrl;
    private $_vlUsername;
    private $_vlPassword;


    /**
     * set Vehicle APi URL
     */
    private function _setVlUrl()
    {
        $this->_vlUrl = Mage::getStoreConfig('apimonitoring/vlapi/vlapi');
    }

    /**
     * Set Vehicle Api Username and Password
     */
    private function _setVlCredentials()
    {
        $this->_vlUsername = Mage::getStoreConfig('apimonitoring/vlapi/vlusername');
        $this->_vlPassword = Mage::getStoreConfig('apimonitoring/vlapi/vlpassword');
    }

    /**
     * Check if Vehicle Logic Api monitoring enabled
     */
    private function isApiMonitoringEnabled()
    {
        return Mage::getStoreConfig('apimonitoring/vlapi/vlenabled');
    }


    /**
     * Make a Api ping Call to montior if its working or not
     *
     * @param string $request
     * @return array
     */
    public function pingApi($request='vehicles/models')
    {

        if(!$this->isApiMonitoringEnabled()) {
            return array('error'=>false);
        }
        $this->_setVlUrl();

        try{

            $getToken = $this->_vlFetchTokenFromRemote();
            if ($getToken['error']) {
                return $getToken;
            }

            $CollectionRequest = new Zend_Http_Client();
            $CollectionRequest->setUri($this->_vlUrl . $request);
            $CollectionRequest->setMethod(Zend_Http_Client::GET);
            $CollectionRequest->setHeaders(Zend_Http_Client::CONTENT_TYPE, 'text/json');
            $CollectionRequest->setHeaders('Authorization: Bearer ' . $getToken['token']);
            $request = $CollectionRequest->request();
            $response = $request->getBody();
            $status = $request->getStatus();
            if($request->getStatus()==200) {
                return array('error'=>false);
            }else{
                return array('error'=>true,'message'=>$response,'code'=>$status);
            }

        } catch (Exception $e) {
            return array('error'=>true,'message'=>$e->getMessage(),'code'=>'unkown');
        }

    }

    /**
     * Get Vehicle logic token based on provided Url,Username and Password
     *
     * @return array
     * @throws Zend_Http_Client_Exception
     */
    public function _vlFetchTokenFromRemote()
    {
        $this->_setvlCredentials();
        $url = $this->_vlUrl . 'token';

        $clientLogin = new Zend_Http_Client();
        $clientLogin->setUri($url);
        $clientLogin->setMethod(Zend_Http_Client::POST);
        $clientLogin->setHeaders(Zend_Http_Client::CONTENT_TYPE, 'application/x-www-form-urlencoded');
        $clientLogin->setParameterPost('grant_type', 'password');
        $clientLogin->setParameterPost('username', $this->_vlUsername);
        $clientLogin->setParameterPost('password', $this->_vlPassword);

        $responseLogin     = $clientLogin->request();
        $responseLoginBody = $responseLogin->getBody();

        $value = json_decode($responseLoginBody);
        if (isset($value->access_token)) {
            return array('error'=>false,'token'=>$value->access_token);
        }

        return array('error'=>true,'message'=>$responseLoginBody);

    }

}