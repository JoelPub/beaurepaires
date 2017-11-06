<?php

/**
 * Salesforce booking helper
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Booking extends Mage_Core_Helper_Abstract {

    public function connectToDBC() {

        try {
            $file = Mage::getBaseDir('base') . DS . "app" . DS . "etc" . DS . "local.xml";

            $xml = simplexml_load_file($file);

            $host = $xml->global->resources->default_setup->connection->host;
            $username = $xml->global->resources->default_setup->connection->username;
            $password = $xml->global->resources->default_setup->connection->password;
            $dbname = Mage::getStoreConfig('salesforce/booking/db_name');

            $connection = new mysqli($host, $username, $password, $dbname);
            return $connection;
        } catch (Exception $e) {
            Mage::log("Cannot connect to booking calendar DB:" . $conn->connect_error . $e);
            return false;
        }
    }

    public function getAllBookingData() {
        $class = "DBC_BOOKING_CALENDAR";
        $helper = Mage::helper("apdinteract_salesforce");  
        $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        $before = mktime(0, 0, 0, date('m', $start), date("d") - 1, date('Y', $start));
        
        $last_updated = date("Y-m-d:H:i:s",$before);
        
        $connection = $this->connectToDBC();
        $sql = 'SELECT bookings.id as booking_id,
                           bookings.book_datetime,
                           bookings.start_datetime,
                           bookings.end_datetime,                                  
                           bookings.notes,
                           bookings.is_unavailable,
                           bookings.id_users_provider as bay_id,
                           CONCAT(providers.first_name," ",providers.last_name) as bay_name,
                           providers.email as bay_email,
                           providers.mobile_number as bay_mobile_number,
                           providers.phone_number as bay_phone_number,
                           providers.address as bay_address,
                           providers.city as bay_city,
                           providers.state as bay_state,
                           providers.zip_code as bay_zip_code,
                           providers.notes as bay_note,
                           customer.id as customer_id,
                           customer.first_name as customer_first,
                           customer.last_name as customer_last,                                          
                           customer.email as customer_email,
                           customer.company_name as customer_company,
                           customer.mobile_number as customer_mobile_number,
                           customer.phone_number as customer_phone_number,
                           customer.address as customer_address,
                           customer.city as customer_city,
                           customer.state as customer_state,
                           customer.zip_code as customer_zip_code,
                           customer.notes as customer_note,
                           customer.magento_customer_id,
                           bookings.id_google_calendar,
                           bookings.id_services as services_id,
                           services.name as service_name,
                           stores.id as store_id,
                           stores.name as store_name,
                           bookings.magento_order_id,
                           bookings.magento_vir_status,
                           bookings.customer_info
                                FROM ea_appointments as bookings
                                        JOIN ea_users as customer ON bookings.id_users_customer = customer.id
                                        JOIN ea_services as services ON bookings.id_services = services.id
                                        JOIN ea_users as providers ON bookings.id_users_provider = providers.id
                                        LEFT JOIN ea_stores as stores ON providers.id_stores = stores.id										
										WHERE bookings.update_at>="' . $last_updated . '"
                                                                                ORDER BY bookings.id ASC
                                                                                ';
               
        
        $result = $connection->query($sql);

        
        return $result;
    }

    public function getAccountByEmail($customerEmail, $websiteId = 1, $details) {
        $id = 0;
		//echo $customerEmail."ALL!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n"; 
        $customer = Mage::getModel('customer/customer')
                ->setWebsiteId($websiteId)
                ->loadByEmail($customerEmail);
        if ($customer->getId()):
            $id = $customer->getId();
			$old = true;
		else:
		    $old = false;
		endif;	
        
        
        return $this->getOrAddSFID($id, $customerEmail, $websiteId, $details, $old);
    }

    public function getOrAddSFID($id, $email, $store_id, $details, $old = false) {
        		
		
        $model = Mage::getModel('customer/customer');
        $helper = Mage::helper('apdinteract_salesforce');
        $class = get_class($model);
        
        
        $account = $helper->getSFId($id, $class);        
          
        if($account==0 && $old) {      
		//echo $account.'==='.$email." OLD!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";                
            $detailsn = $model->load($id);
			
            $account = $helper->createCustomerToSf($id, $detailsn, $details);
        }

        $address_data = array('firstname' => $details['customer_first'],
            'lastname' => $details['customer_last'],
            'country_id' => 'AU',
            'region_id' => '',
            'postcode' => $details['customer_zip_code'],
            'city' => $details['customer_city'],
            'telephone' => $details['customer_phone_number'],
            'fax' => '',
            'company' => $details['customer_company'],
            'street' => $details['customer_address']);

        return $account !=0 ? $account : $this->addCustomer($address_data, $store_id, $email, $id);
    }

    public function addCustomer($address_data, $store_id, $email, $id) {    
        //echo $account.'==='.$email." NEW!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!\n";                
        $helper = Mage::helper('apdinteract_salesforce');
        try {
            if ($id <= 0):
                $customer = Mage::getModel("customer/customer");
                $customer->setWebsiteId($store_id)
                        ->setFirstname($address_data['firstname'])
                        ->setLastname($address_data['lastname'])
                        ->setEmail($email)
                        ->setPassword('password123')
                        ->setGroupId(1)
                        ->setDormantFlag(1);

                $customer->save();
                $customer_id = $customer->getId();
                $helper->setCustomerAddress($customer_id, $address_data);
            else:
                $customer_id = $id;
            endif;
            return $helper->createCustomerToSf($customer_id, $address_data);
        } catch (Exception $e) {
            Zend_Debug::dump($e->getMessage());
        }
    }

    /**
     * bulk create
     * 
     * @todo please implement
     * @param array $rows     
     */
    public function bulkCreate($input, $batchSize = 25, $entity) {				
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => $entity));
        $connector->authorize();
        $helper = Mage::Helper('apdinteract_salesforce/booking');
        $size = sizeof($input);
        $count = 0;
        $batch = array();
        foreach ($input as $row):

            $sobject = $row["data"];
            $source = $row["source"];

            $batch[] = $sobject;
            $sourceBatch[] = $source;
            $count++;
        
            
            if ($count % $batchSize == 0 || $count == $size):
               
                $all_results = $connector->bulkCreate($batch)->getResult();
               
                
                $i = 0;
                foreach ($all_results[0]->results as $result):
                    $temp_source = $sourceBatch[$i];
                    if (property_exists($result->result, "id")){
                        $helper->saveDictionary($result->result->id, $temp_source, 'DBC_BOOKING_CALENDAR', true);                       
                    }else{
                        echo "failed to create for [DBC_BOOKING] " . $temp_source."\n";
                    }    
                    $i++;
                endforeach;
                $batch = array();
                $sourceBatch = array();           
            endif;         
            echo "creating $count / $size records\r";
        endforeach;
           
    }

    /**
     * bulk update
     * 
     * @todo please implement
     * @param array $data
     * @return ApdInteract_Salesforce_Model_Core_Salesforce_Connector_EntityConnector
     */
    public function bulkUpdate($input, $batchSize = 25, $entity) {
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => $entity));
        $connector->authorize();
        $helper = Mage::Helper('apdinteract_salesforce/booking');
        $size = sizeof($input);
        $count = 0;
        $batch = array();
        foreach ($input as $row):

            $sobject = $row["data"];
            $source = $row["source"];
            $sf_id = $row["sf_id"];

            $batch[$sf_id] = $sobject;
            $sourceBatch[] = $source;
            $count++;

            if ($count % $batchSize == 0 || $count == $size):
                
                //Zend_Debug::dump($batch);
                $all_results = $connector->bulkUpdate($batch)->getResult();
                $i = 0;
                //Zend_Debug::dump($all_results);                
                $batch = array();
                $sourceBatch = array();
            endif;
            echo "updating $count / $size records\r";
        endforeach;
    }

    public function saveDictionary($sid, $source, $class_name, $new) {
        $helper = Mage::Helper('apdinteract_salesforce');
        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        if (!$new):
            $id = $this->_getBookingId($source, 'DBC_BOOKING_CALENDAR');
            $dictionary->load($id);
        endif;

        $now = date("Y-m-d h:m:s", time());

        $dictionary->setData("entity_id", $source);
        $dictionary->setData("entity_type", $class_name);
        $dictionary->setData("updated_at", $now);
        if ($new)
            $dictionary->setData("created_at", $now);

        if ($sid)
            $dictionary->setData("salesforce_id", $sid);

        $dictionary->save();
       
    }

    public function _getBookingId($id, $class) {
        $class = ($this->getEquivalent($class) != '') ? $this->getEquivalent($class) : $class;
        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        $object = $dictionary->getCollection()
                ->addFieldToFilter("entity_type", $class)
                ->addFieldToFilter("entity_id", $id)
                ->getFirstItem()
                ->getData();

        return isset($object['entity_id']) ? $object['entity_id'] : 0;
    }

}
