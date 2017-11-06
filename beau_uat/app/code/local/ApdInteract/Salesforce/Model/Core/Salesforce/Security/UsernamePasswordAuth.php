<?php

/**
 * Connector to connect salesforce url
 *
 *
 * @category    ApdInteract
 * @package		ApdInteract_Salesforce
 * @author		Haihao Yan
 */
class ApdInteract_Salesforce_Model_Core_Salesforce_Security_UsernamePasswordAuth extends Mage_Core_Model_Abstract {

    protected $consumer_key;
    protected $consumer_secret;
    protected $security_token;
    protected $redirect_url;
    protected $login_url;
    protected $username;
    protected $password;
    protected $token;
    protected $instance;

    /* @var $http_client ApdInteract_Salesforce_Model_Core_Http_Client */
    protected $http_client;
    protected $helper;

    public function __construct() {
        $this->helper = Mage::helper("apdinteract_salesforce/data");
        $params = $this->helper->getConfig();

        $this->consumer_key = $params["consumerkey"];
        $this->consumer_secret = $params["consumersecret"];
        $this->redirect_url = $params["redirect"];
        $this->login_url = $params["login"];
        $this->username = $params["username"];
        $this->password = $params["password"];
        $this->token = $params["token"];
        $this->instance = $params["instance"];
        $this->security_token = $params["security_token"];

        $this->http_client = Mage::getModel("apdinteract_salesforce/core_http_client");
    }

    /**
     * get token
     * 
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * get instance
     * 
     * @return string
     */
    public function getInstance() {
        return $this->instance;
    }

    /**
     * get status
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * authorize application
     * 
     * @return ApdInteract_Salesforce_Model_Core_Salesforce_Security_UsernamePasswordAuth
     */
    public function authorize() {
        $url = $this->login_url . "/services/oauth2/token";
        $params = array(
            "grant_type" => "password",
            "client_id" => $this->consumer_key,
            "client_secret" => $this->consumer_secret,
            "username" => $this->username,
            "password" => $this->password . $this->security_token
        );
        $this->http_client->post($url, $params);
        $result = $this->http_client->getJsonResult();
        $status = $this->http_client->getStatusCode();
        $this->status = $status;
        if ($status == 200) {
            if ($this->token != $result->access_token)
                $this->helper->saveConfig("token", $result->access_token);
            if ($this->instance != $result->instance_url)
                $this->helper->saveConfig("instance", $result->instance_url);
            if ($this->token != $result->access_token || $this->instance != $result->instance_url)
                $this->helper->cleanCache();
            $this->token = $result->access_token;
            $this->instance = $result->instance_url;
        }
        return $this;
    }

}
