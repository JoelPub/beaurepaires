<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Lead
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Lead extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     * 
     * @param Mage_Customer_Model_Lead $input
     */
    public function map($input) {

        if (isset($input['date'])):
            $date = explode("/", $input['date']);
            $new = $date[2] . '-' . $date[1] . '-' . $date[0];
            $new = strtotime($new);
            $result["Appointment_Date__c"] = date("c", $new);
        endif;

        if (isset($input['subscribe']))
            $result["Subscribe_to_Newsletter__c"] = 1;

        $result["FirstName"] = $input['name'];
        $result["Email"] = $input['email'];
        $result["LeadSource"] = $input['source'];
        $result["LastName"] = $input['lastname'];
        $result["Company"] = $input['company'];
        $result["Inquiry_Type__c"] = $input['enquiry'];
        $result["Website"] = Mage::getStoreConfig(Mage_Core_Model_Url::XML_PATH_SECURE_URL);
        $result["Description_of_Enquiry__c"] = $input['comment'];
        $result["Preferred_Store__c"] = $input['storename'];
        $result["Company"] = $input['company'];
        $result["PostalCode"] = $input['zip'];
        $result["City"] = $input['city'];
        $result["Street"] = $input['street'];
        $result["State"] = $input['region'];
        //$result["Fax"] = $input['fax'];  
        //$result["MobilePhone"] = $input['mobile'];  
        $result["Phone"] = $input['telephone'];
        $result["Country"] = $input['country'];
        $result['Product_Name__c'] = $input['product_name'];
        $result['Product_SKU__c'] = $input['product_sku'];
        $result['Time_of_Enquiry__c'] = date("c",strtotime($input['time_of_inquiry']));;
        $result['Postcode__c'] = $input['postcode'];
        $result['Status__c'] = 'New';
        $result['Tyre_Wheel_Size__c'] = $input['requestedSize'];
        $result['Vehicle_Make__c'] = $input['vehicle_make'];
        $result['Vehicle_Model__c'] = $input['vehicle_model'];
        
        $id = $this->_getSFId($input['customer_id']);

        if ($id != 0)
            $result["Customer__c"] = $id;
        

        return $result;
    }

    private function _getSFId($id) {
        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        $customers = $dictionary->getCollection()
                ->addFieldToFilter("entity_type", "Mage_Customer_Model_Customer")
                ->addFieldToFilter("entity_id", $id)
                ->getFirstItem()
                ->getData();

        return isset($customers['salesforce_id']) ? $customers['salesforce_id'] : 0;
    }

}
