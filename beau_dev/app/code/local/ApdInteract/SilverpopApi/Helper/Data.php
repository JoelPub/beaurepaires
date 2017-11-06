<?php

class ApdInteract_SilverpopApi_Helper_Data extends Mage_Core_Helper_Abstract
{
    private $_jsessionid;
    private $_databaseid;
    
    private function _xmlToSilverpop($xml)
    {
        if (empty($xml)) {
            return false;
        }
        
        // TODO: Get from Magento Admin > System > Config
        $url = 'http://api7.ibmmarketingcloud.com/XMLAPI';
        if (isset($this->_jsessionid)) 
        {
            $url .= ";jsessionid=" . $this->_jsessionid;
        }
        
        $clientLogin = new Zend_Http_Client();
        $clientLogin->setUri($url);
        $clientLogin->setMethod(Zend_Http_Client::POST);
        $clientLogin->setHeaders(Zend_Http_Client::CONTENT_TYPE, 'application/x-www-form-urlencoded');        
        $clientLogin->setParameterPost('xml', $xml);
                
        $responseLogin = $clientLogin->request();
        return $responseLogin->getBody();
    }
    
    private function _loadXml($xml) {
        return simplexml_load_string($xml, null, LIBXML_NOCDATA);
    }
    
    private function _logException($error_message)
    {
        Mage::log($error_message, null, 'silverpop_api_debug.log');
        echo $error_message;
    }
    
    private function _jsessionidFromLoginResponse($response)
    {
        $xml = $this->_loadXml($response);        
        if (empty($xml->Body->RESULT->SESSIONID)) 
        {
            $error_message = 'Could not login to Silverpop - ' . $xml->Body->Fault->FaultString;
            $this->_logException($error_message);
            return false;
        }
        return (string) $xml->Body->RESULT->SESSIONID;
    }
    
    private function _getConfigValue($path, $value_if_empty)
    {
        $value = Mage::getStoreConfig($path);
        if (empty($value))
        {
            return $value_if_empty;
        }
        return $value;
    }
    
    private function _getUsername()
    {
        return $this->_getConfigValue('silverpop_api/settings/username', 'api-magento.goodyear@goodyear.com.au');
    }
    
    private function _getPassword()
    {
        return $this->_getConfigValue('silverpop_api/settings/password', 'P@$$w0rd2');
    }
    
    private function _getDatabaseName()
    {
        return $this->_getConfigValue('silverpop_api/settings/db_name', 'Beaurepaires');
    }
    
    /**
     * Login to Silverpop and return jsession id
     * @assert () !== false
     * 
     */
    public function silverpopLogin($force = false) 
    {
        if (!isset($this->_jsessionid) || $force)
        {
        
            // TODO: Get from Magento Admin > System > Config
            $username = $this->_getUsername(); // 'api-magento.goodyear@goodyear.com.au'; // 'me@email.com';
            $password = $this->_getPassword(); // 'P@$$w0rd2'; // 'password123';

            $xml = "
            <Envelope><Body>
                <Login>
                    <USERNAME>{$username}</USERNAME>
                    <PASSWORD>{$password}</PASSWORD>
                </Login>
            </Body></Envelope>            
            ";

            $response = $this->_xmlToSilverpop($xml);
            // TODO: Probably a bunch of XML, get the jSessionId from this
            $this->_jsessionid = $this->_jsessionidFromLoginResponse($response);
        }
        return $this->_jsessionid;
    }
    
    /**
     * Logout from Silverpop
     */
    public function silverpopLogout() 
    {
        $xml = "
        <Envelope><Body>
            <Logout/>
        </Body></Envelope>            
        ";
                
        $response = $this->_xmlToSilverpop($xml);
        $this->_jsessionid = null;
        
        return $response;
    }
    
    public function getLists() 
    {                
        $xml = "
        <Envelope><Body>
            <GetLists>
                <VISIBILITY>1</VISIBILITY>
                <LIST_TYPE>2</LIST_TYPE>
                <INCLUDE_ALL_LISTS>True</INCLUDE_ALL_LISTS>
            </GetLists>
        </Body></Envelope>
        ";
                
        $response = $this->_xmlToSilverpop($xml);
        return $this->_loadXml($response);        
    }
    
    private function _startsWith($searchme, $prefix) 
    {
        $position = strpos($searchme, $prefix);
        return ($position !== false && $position === 0);
    }
    
    public function getListsStartingWith($prefix = null)
    {        
        foreach ($this->getLists()->Body->RESULT->LIST as $list)
        {            
            if ($this->_startsWith($list->NAME, $prefix))
            {
                $lists[] = $list;
            }
        }
        return $lists;
    }
    
    
    private function _getFieldOfListsStartingWith($field = 'ID', $prefix = null)
    {
        $list_field_array = array();
        foreach ($this->getLists()->Body->RESULT->LIST as $list)
        {            
            if ($this->_startsWith($list->NAME, $prefix))
            {
                $list_field_array[] = (string) $list->$field;
            }
        }
        return $list_field_array;
    }
    
    
    public function getListNamesStartingWith($prefix = null)
    {        
        $field = 'NAME';
        return $this->_getFieldOfListsStartingWith($field, $prefix);
    }
    
    public function getListIdsStartingWith($prefix = null)
    { 
        $field = 'ID';
        return $this->_getFieldOfListsStartingWith($field, $prefix);        
    }
    
    public function getListByName($list_name)
    {
        foreach ($this->getLists()->Body->RESULT->LIST as $list)
        {
            if ($list->NAME == $list_name)
            {
                return $list;
            }
        }
    }
    
    public function getDatabaseId() {
        if (!isset($this->_databaseid)) {
            $database_name = $this->_getDatabaseName(); // 'Beaurepaires'; // TODO: Get this from Admin > System > Config
            $this->_databaseid = $this->_getListId($database_name);
        }
        return $this->_databaseid;
    }
    
    private function _getListId($list_name)
    {
        return (string) $this->getListByName($list_name)->ID;
    }
    
    private function _addContactToLists($email_address, $contact_list_id_array, $database_list_id = null)
    {  
        if (!isset($database_list_id))
        {
            $database_list_id = $this->getDatabaseId();
        }
        
        $contact_lists_xml = 
            '<CONTACT_LIST_ID>' . 
            implode("</CONTACT_LIST_ID>\r\n<CONTACT_LIST_ID>", $contact_list_id_array) .
            '</CONTACT_LIST_ID>';
        
        $xml = "
        <Envelope><Body>
            <AddRecipient>
                <LIST_ID>{$database_list_id}</LIST_ID>
                <CREATED_FROM>1</CREATED_FROM>
                <CONTACT_LISTS>
                    {$contact_lists_xml}
                </CONTACT_LISTS>
                <UPDATE_IF_FOUND>true</UPDATE_IF_FOUND>
                <COLUMN>
                <NAME>EMAIL</NAME>
                <VALUE>{$email_address}</VALUE>
                </COLUMN>
            </AddRecipient>
        </Body></Envelope>
        ";
         
        $response = $this->_xmlToSilverpop($xml);
        return $this->_loadXml($response);
        
    }
    
    public function addContactToLists($email_address, $list_names_array)
    {
        foreach ($list_names_array as $list_name)
        {
            $list_ids[] = $this->_getListId($list_name);
        }
        
        $result = $this->_addContactToLists($email_address, $list_ids);
        return $result;
    }  
    
    public function removeContactFromLists($email_address, $list_names_array)
    {
        foreach ($list_names_array as $list_name)
        {
            $result[] = $this->removeContactFromList($email_address, $list_name);
        }
        return $result;
    }
    
    public function removeContactFromList($email_address, $list_name)
    {
        $list_id = $this->_getListId($list_name);
        return $this->_removeContactFromList($email_address, $list_id);     
    }
    
    private function _removeContactFromList($email_address, $list_id, $remove_type = 'RemoveRecipient')
    {
        // $remove_type = 'RemoveRecipient' or 'OptOutRecipient'
        // Remove takes them off the mailing list, OptOut marks them as "opted-out"
        
        $xml = "
        <Envelope><Body>        
            <RemoveRecipient>
                <LIST_ID>{$list_id}</LIST_ID>
                <EMAIL>{$email_address}</EMAIL>
            </RemoveRecipient>        
        </Body></Envelope>
        ";
            
        $response = $this->_xmlToSilverpop($xml);
        return $this->_loadXml($response);
            
    }
    
    public function createContactLists($list_names_array, $database_list_id = null)
    {
        foreach ($list_names_array as $list_name)
        {
            $response[] = $this->createContactList($list_name, $database_list_id);
        }
        return $response;
    }
    
    public function createContactList($list_name, $database_list_id = null)
    {
        if (!isset($database_list_id))
        {
            $database_list_id = $this->getDatabaseId();
        }
        
        $xml = "
        <Envelope><Body>
            <CreateContactList>
                <DATABASE_ID>{$database_list_id}</DATABASE_ID>
                <CONTACT_LIST_NAME>{$list_name}</CONTACT_LIST_NAME>
                <VISIBILITY>1</VISIBILITY>
            </CreateContactList>
        </Body></Envelope>
        ";
         
        $response = $this->_xmlToSilverpop($xml);
        return $this->_loadXml($response);
    }
    
    public function deleteContactList($list_name, $visibility = 0)
    {

        
        $xml = "
        <Envelope><Body>
            <DeleteTable>
                <TABLE_NAME>{$list_name}</TABLE_NAME>
                <TABLE_VISIBILITY>{$visibility}</TABLE_VISIBILITY>
                <EMAIL>spalmer@apdgroup.com</EMAIL>
            </DeleteTable>
        </Body></Envelope>
        ";
         
        $response = $this->_xmlToSilverpop($xml);
        return $this->_loadXml($response);
    }
    
    public function getDatabaseDetails($database_list_id = null)
    {
        if (!isset($database_list_id))
        {
            $database_list_id = $this->getDatabaseId();
        }
        
        $xml = "
        <Envelope><Body>
            <GetListMetaData>
                <LIST_ID>{$database_list_id}</LIST_ID>
            </GetListMetaData>
        </Body></Envelope>
        ";
         
        $response = $this->_xmlToSilverpop($xml);
        return $this->_loadXml($response);
    }
   
}
