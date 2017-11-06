<?php

class ApdInteract_Salesforce_Model_Process_Business_Booking extends Mage_Core_Model_Abstract {

    public function process() {
        date_default_timezone_set('Australia/Melbourne');
        $attributes = $this->_getAttributes();
        $param = array("entity" => "Booking__C");
        $data = Mage::Helper('apdinteract_salesforce');
        $booking = Mage::Helper('apdinteract_salesforce/booking');
        $result = $booking->getAllBookingData();

        $rows_update = array();
        $rows_create = array();
        $array = array();
        while ($row = $result->fetch_assoc()):
           
            foreach ($attributes as $value => $key):                
                if ($value == 'book_datetime' || $value == 'end_datetime' || $value == 'start_datetime'): //dates
                    $array[$key] = date("c", strtotime($row[$value]));
                elseif ($key == 'customer_info'):
                    unset($array[$key]);
                    $array = $this->_unsetContact($array);
                    if (!is_null($row[$value]) && trim($row[$value])!=''):
                        $customer_info = json_decode($row[$value]);
                    
                        $array['Contact_City__c'] = $customer_info->city;
                        $array['Contact_Company_Name__c'] = $customer_info->company_name;
                        $array['Contact_Email__c'] = $customer_info->email;
                        $array['Contact_First_Name__c'] = $customer_info->first_name;
                        $array['Contact_Last_Name__c'] = $customer_info->last_name;
                        $array['Contact_Notes__c'] = $customer_info->notes;
                        $array['Contact_Phone_Number__c'] = $customer_info->phone_number;
                        $array['Contact_Zip_Code__c'] = $customer_info->zip_code;
                    
                    endif;
                else:
                    $array[$key] = $row[$value];
                endif;                
            endforeach;
            $sf_id = $data->getSFId($row['booking_id'], 'DBC_BOOKING_CALENDAR');
		    $array['Account__c'] = $this->getAccount($row['magento_customer_id'], $row['customer_email'], $row);
            if ($sf_id == '0'):                  
                $rows_create[] = array('source' => $row['booking_id'], 'data' => $array);
            else:               
                unset($array['BC_ID__c']);
                unset($array['Account__c']);
                $rows_update[] = array('source' => $row['booking_id'], 'data' => $array, 'sf_id' => $sf_id);
				
            endif;
        endwhile;        
        echo "create new bookings... \n";        
        $booking->bulkCreate($rows_create, 25, 'Booking__c');
        echo "update bookings... \n";        
        $booking->bulkUpdate($rows_update, 25, 'Booking__c');                
        Mage::getModel("apdinteract_salesforce/updates")->saveUpdates('', 'DBC_BOOKING_CALENDAR');
    }

    private function getAccount($customer_id, $email, $row) {
        $booking = Mage::Helper('apdinteract_salesforce/booking');
        //if (is_null($customer_id) || $customer_id == '' || $customer_id <= 0) {
            return $booking->getAccountByEmail($email, 1, $row);
        //} else {
          //  return $booking->getOrAddSFID($customer_id, $email, 1, $row);
        //}
    }

    private function _getAttributes() {

        $data = array(
            "bay_address" => "Bay_Address__c",
            "bay_city" => "Bay_City__c",
            "bay_email" => "Bay_Email__c",
            "bay_mobile_number" => "Bay_Mobile__c",
            "bay_name" => "Bay_Name__c",
            "bay_note" => "Bay_Note__c",
            "bay_phone_number" => "Bay_Phone__c",
            "bay_state" => "Bay_State__c",
            "bay_zip_code" => "Bay_Zip__c",
            "booking_id" => "BC_ID__c",
            "book_datetime" => "Booking_Date__c",
            "end_datetime" => "End_Date__c",
            "notes" => "Notes__c",
            "services_id" => "Service_Id__c",
            "service_name" => "Service_Name__c",
            "start_datetime" => "Start_Date__c",
            "store_id" => "Store_Id__c",
            "store_name" => "Store_Name__c",
            "magento_vir_status" => "VIR_Status__c",
            "customer_info" => "customer_info"
        );

        return $data;
    }
    
    private function _unsetContact($array) {
        $fields = array('Contact_City__c','Contact_Company_Name__c','Contact_Email__c','Contact_First_Name__c','Contact_Last_Name__c','Contact_Notes__c','Contact_Phone_Number__c','Contact_Zip_Code__c'); 
        foreach($fields as $field):
            unset($array[$field]);
        endforeach;
        return $array;
    }

}
