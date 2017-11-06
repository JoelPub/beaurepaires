<?php
/**
 * 
 * http client
 * 
 * @category	ApdInteract
 * @package		ApdInteract_Salesforce
 * @author		hyan
 *
 */
class ApdInteract_Salesforce_Model_Core_Http_Client extends Mage_Core_Model_Abstract {
	
	/**
	 *
	 * @var array | object
	 */
	protected $result;
	
	/**
	 *
	 * @var int
	 */
	protected $status_code;
	
	/**
	 */
	public function __construct() {
		$this->result = "";
		$this->status_code = 0;
	}
	
	/**
	 * get json result
	 *
	 * @return mixed
	 */
	public function getJsonResult() {
		return json_decode ( $this->result );
	}
	
	/**
	 * get result
	 *
	 * @return string|NULL|mixed
	 */
	public function getRawResult() {
		return $this->result;
	}
	
	/**
	 * get Status Code
	 *
	 * @return int
	 */
	public function getStatusCode() {
		return $this->status_code;
	}
	
	/**
	 * get params
	 *
	 * @param array $params        	
	 * @param bool $encode        	
	 * @return string
	 */
	public function getQuery($params = array(), $encode = false) {
		$result = "";
		$keys = array_keys ( $params );
		$i = 0;
		foreach ( $keys as $key ) {
			$value = $params [$key];
			if ($encode) {
				$result .= $i == 0 ? ($key . "=" . urlencode ( $value )) : "&" . ($key . "=" . urlencode ( $value ));
			} else {
				$result .= $i == 0 ? ($key . "=" . $value) : "&" . ($key . "=" . $value);
			}
			$i ++;
		}
		return $result;
	}
	
	/**
	 * get request
	 *
	 * @param string $url        	
	 * @param array $params        	
	 * @param array $headers        	
	 * @return ApdInteract_Salesforce_Model_Core_Http_Client
	 */
	public function get($url, $params = array(), $headers = array()) {
		$this->result = null;
		$this->status_code = 0;
		$query = $this->getQuery ( $params, true );
		$url .= "?" . $query;
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_HEADER, false );     
                
		if ($headers)
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		
		$this->result = curl_exec ( $ch );
		$this->status_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		
		curl_close ( $ch );
		
		return $this;
	}
	
	/**
	 * post request
	 *
	 * @param string $url        	
	 * @param array $params        	
	 * @param array $headers        	
	 * @return ApdInteract_Salesforce_Model_Core_Http_Client
	 */
	public function post($url, $params = array(), $json_encode = false, $headers = array()) {
		$this->result = null;
		$this->status_code = 0;
		
		$query = $json_encode ? json_encode ( $params ) : $this->getQuery ( $params );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_HEADER, false );
		if ($headers)
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );                
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $query ); 
                
		$this->result = curl_exec ( $ch );
		$this->status_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		
		curl_close ( $ch );
		
		return $this;
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see Mage_Core_Model_Abstract::delete()
	 */
	public function delete($url, $headers = array()){
		$this->result = null;
		$this->status_code = 0;
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_HEADER, false );
		if ($headers)
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "DELETE" );
                
		$this->result = curl_exec ( $ch );
		$this->status_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		curl_close ( $ch );
		
		return $this;
	}
	
	/**
	 * patch request
	 *
	 * @param string $url        	
	 * @param array $params        	
	 * @param array $headers        	
	 * @return ApdInteract_Salesforce_Model_Core_Http_Client
	 */
	public function patch($url, $params = array(), $json_encode = false, $headers = array()) {
		$this->result = null;
		$this->status_code = 0;
		
		$query = $json_encode ? json_encode ( $params ) : $this->getQuery ( $params );
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_HEADER, false );
                
		if ($headers)
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "PATCH" );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $query );                  
		$this->result = curl_exec ( $ch );
		$this->status_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		
		curl_close ( $ch );
		
		return $this;
	}
}