<?php

/**
 * ApdInteract_Salesforce_Model_Core_Salesforce_Connector_EntityConnector
 * 
 * SObject entity operator
 * 		- Query
 * 		- Create
 * 		- Update
 * 		- Bulk Update
 * 		- Bulk Save
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Model_Core_Salesforce_Connector_EntityConnector extends Mage_Core_Model_Abstract {

    const SOBJECT_QUERY = "/services/data/v20.0/query";
    const SOBJECT_ENDPOINT = "/services/data/v20.0/sobjects/";
    const SOBJECT_BATCH_ENDPOINT = "/services/data/v34.0/composite/batch";

    /**
     * 
     * @var string
     */
    protected $service_url;

    /**
     * 
     * @var array
     */
    protected $config;

    /**
     * 
     * @var ApdInteract_Salesforce_Model_Core_Salesforce_Security_UsernamePasswordAuth
     */
    protected $authorizor;

    /**
     * 
     * @var ApdInteract_Salesforce_Helper_Data
     */
    protected $helper;

    /**
     * 
     * @var string
     */
    protected $instance;

    /**
     * 
     * @var string
     */
    protected $token;

    /**
     * 
     * @var ApdInteract_Salesforce_Model_Core_Http_Client
     */
    protected $http_client;

    /**
     *
     * @var String
     */
    protected $entity_name;

    /**
     * 
     * @var object
     */
    protected $result;

    /**
     * 
     * @var int
     */
    protected $status;

    public function __construct($params = array()) {

        $this->helper = Mage::helper("apdinteract_salesforce/data");
        $this->authorizor = Mage::getModel("apdinteract_salesforce/core_salesforce_security_usernamePasswordAuth");
        $this->http_client = Mage::getModel("apdinteract_salesforce/core_http_client");

        $this->config = $this->helper->getConfig();
        $this->entity_name = key_exists("entity", $params) ? $params["entity"] : "Account";
        $this->instance = $this->authorizor->getInstance();
        $this->token = $this->authorizor->getToken();
        $this->service_url = $this->instance . self::SOBJECT_ENDPOINT . $this->entity_name . "/";

        parent::__construct();
    }

    /**
     * 
     * @return ApdInteract_Salesforce_Model_Core_Salesforce_Connector_EntityConnector
     */
    public function authorize() {
        $this->authorizor->authorize();
        $this->instance = $this->authorizor->getInstance();
        $this->token = $this->authorizor->getToken();
        $this->status = $this->authorizor->getStatus();
        return $this;
    }

    /**
     * query salesforce to return a list of data
     * 
     * @param string $soql
     * @return ApdInteract_Salesforce_Model_Core_Salesforce_Connector_EntityConnector
     */
    public function query($soql) {
        $url = $this->instance . self::SOBJECT_QUERY;
        $params = array(
            "q" => $soql
        );

        $this->http_client->get($url, $params, $this->getHeader(false));

        $this->result = $this->http_client->getJsonResult();
        $this->status = $this->http_client->getStatusCode();
        return $this;
    }

    /**
     * create a new row of salesforce data
     * 
     * @param array $data
     * @return ApdInteract_Salesforce_Model_Core_Salesforce_Connector_EntityConnector
     */
    public function create($data) {

        $this->http_client->post($this->service_url, $data, true, $this->getHeader(true));

        $this->result = $this->http_client->getJsonResult();
        $this->status = $this->http_client->getStatusCode();

        return $this;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see Mage_Core_Model_Abstract::delete()
     */
    public function delete($sid) {
    	$url = $this->service_url.$sid;
    	$this->http_client->delete($url, $this->getHeader(true));
    	$this->result = $this->http_client->getJsonResult();
    	$this->status = $this->http_client->getStatusCode();    	
    	return $this;
    	
    }

    /**
     * update salesforce object 
     * 
     * @param int $id
     * @param array $data
     * @return ApdInteract_Salesforce_Model_Core_Salesforce_Connector_EntityConnector
     */
    public function update($id, $data) {
        $this->http_client->patch($this->service_url . $id, $data, true, $this->getHeader(true));

        $this->result = $this->http_client->getJsonResult();
        $this->status = $this->http_client->getStatusCode();        
        
        return $this;
    }

    /**
     * array (
     *    (
     *    		"data" => array(),
     *    		"entity" => "Account",
     *    		"sid" => "xxxxxxx"
     *    ),
     * ) 
     * @param array $actions
     */
    public function batchActions($actions, $size = 20) {
        $requests = array();
        foreach ($actions as $action) {
            $requests[] = $this->getActionRequest($action);
        }
        $count = count($requests);
        $batch = array();
        for ($i = 0; $i < count($rows); $i++) {
            $request = $requests[$i];
            $batch[] = $request;
            if (($i % 20 == 0 && $i != 0 ) || $count - 1 == $i) {
                $url = $this->instance . self::SOBJECT_BATCH_ENDPOINT;
                $data = array("batchRequests" => $batch);
                $this->http_client->post($url, $data, true, $this->getHeader(true));
                $this->result[] = $this->http_client->getJsonResult();
                $this->status[] = $this->http_client->getStatusCode();
                $batch = array();
            }
        }
        return $this;
    }

    protected function getActionRequest($action) {
        $result = array();
        $data = $action["data"];
        $entity = $action["entity"];
        $sid = $action["sid"];
        $method = $sid ? "PATCH" : "POST";
        $url = $sid ? "v34.0/sobjects/$entity/$sid" : "v34.0/sobjects/$entity";
        $result = array(
            "method" => "PATCH",
            "url" => "v34.0/sobjects/$entity/$sid",
            "richInput" => $data
        );
        return $result;
    }

    /**
     * bulk create
     * 
     * @todo please implement
     * @param array $rows
     * @return ApdInteract_Salesforce_Model_Core_Salesforce_Connector_EntityConnector
     */
    public function bulkCreate($rows, $batchSize = 25) {
        $this->result = array();
        $this->status = array();
        $batch = array();
        $count = count($rows);
        for ($i = 0; $i < count($rows); $i++) {
            $data = $rows[$i];
            $request = array(
                "method" => "POST",
                "url" => "v34.0/sobjects/" . $this->entity_name,
                "richInput" => $data
            );
            $batch[] = $request;
            if (($i % $batchSize == 0 && $i != 0 ) || $count - 1 == $i) {
                $url = $this->instance . self::SOBJECT_BATCH_ENDPOINT;
                $data = array("batchRequests" => $batch);
                $this->http_client->post($url, $data, true, $this->getHeader(true));
                $this->result[] = $this->http_client->getJsonResult();
                $this->status[] = $this->http_client->getStatusCode();
                $batch = array();
            }
        }
        return $this;
    }

    /**
     * bulk update
     * 
     * @todo please implement
     * @param array $data
     * @return ApdInteract_Salesforce_Model_Core_Salesforce_Connector_EntityConnector
     */
    public function bulkUpdate($rows, $batchSize = 25) {
        $this->result = array();
        $this->status = array();
        $batch = array();
        $count = count($rows);
        $i = 0;
        $keys = array_keys($rows);

        foreach ($keys as $key) {
            $data = $rows[$key];
            $request = array(
                "method" => "PATCH",
                "url" => "v34.0/sobjects/" . $this->entity_name . "/$key",
                "richInput" => $data
            );
            $batch[] = $request;
            $i++;
            if (($i % $batchSize == 0 ) || $count == $i) {
                $url = $this->instance . self::SOBJECT_BATCH_ENDPOINT;
                $data = array("batchRequests" => $batch);
                $this->http_client->post($url, $data, true, $this->getHeader(true));
                $this->result[] = $this->http_client->getJsonResult();
                $this->status[] = $this->http_client->getStatusCode();
                $batch = array();
            }
        }
        return $this;
    }

    /**
     * get result object
     * 
     * @return object
     */
    public function getResult() {
        return $this->result;
    }

    /**
     * get status
     * 
     * @return number
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * get header
     * 
     * @param string $json_encoded
     * @return string[]
     */
    protected function getHeader($json_encoded = false) {
        $token = $this->token;
        $result = array("Authorization: Bearer $token");
        if ($json_encoded) {
            $result[] = "Content-type: application/json";
        }
        return $result;
    }

}
