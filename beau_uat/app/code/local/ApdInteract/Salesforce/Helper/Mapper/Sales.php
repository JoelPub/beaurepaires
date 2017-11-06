<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Customer
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Sales extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     */
    public function __construct() {
        $this->protocol = array(
            "entity_id" => "Magento_ID__c",
            "increment_id" => "Magento_Order_Number__c",
            //"grand_total" => "TotalAmount",
            "remote_ip" => "Remote_IP__c",
            "customer_email" => "Order_Email__c",
            "discount_amount" =>"Discount__c",
            "grand_total" => "Order_Total_With_Discount__c"
        );
    }

    /**
     * 
     * 
     * @param Mage_Customer_Model_Customer $input
     */
    public function map($input) {

        $result = parent::map($input);
        $order_id = $input->getData("entity_id");
        $customer_id = $input->getData("customer_id");
        $result['Website__c'] = Mage::helper('apdinteract_salesforce')->getWebsiteByStoreId($input->getData("store_id"));
        $result['Request_Order_Type__c'] = $input->getData('request_type') == '' ? 'ONLINE ORDER' : $input->getData('request_type');
        $result['Order_Status__c'] = $this->_getStatusByCode($input->getData('status'));
        $result['Customer_Group__c'] = $this->_getGroupById($input->getData('customer_group_id'));
        $result['Appointment_Store_Name__c'] = $this->_getStoreLocationByID($input->getData('storelocation'));
        $result['Appointment_Date_Time__c'] = date("c", strtotime($input->getData("appointment_datetime")));
        $this->_getBillingByID($input->getData("billing_address_id"), $result, $customer_id, $input->getData("store_id"), $input->getData("customer_email"));
        $sales_order_model = 'Mage_Sales_Model_Order_Contract';
        $contract_id = Mage::helper('apdinteract_salesforce')->getSFId($order_id, $sales_order_model);               
        $result['EffectiveDate'] = date("c", strtotime($input->getData("created_at"))); 
        $result['ContractId'] = $contract_id==0 ? $this->createContract($input->getData("billing_address_id"), $result, $input->getData("created_at"), $type="Billing", $order_id) : $contract_id;                                           
        $result['Status'] = 'Draft'; //need to change
        $sales_order_model = 'Mage_Sales_Model_Order_Book';
        $pricebook_id = Mage::helper('apdinteract_salesforce')->getSFId($order_id, $sales_order_model);                             
        $priceBookdata = array("Name"=>"Price Book for: ".$input->getData("increment_id"),"isActive"=>true);
        $result['Subtotal__c'] = $input->getData('subtotal_incl_tax');
        $result['Grand_Total_Excl_Tax__c'] = $input->getData('grand_total') - $input->getData('tax_amount');
        $result['Tax__c'] = $input->getData('tax_amount');
        $result['Total_Paid__c'] = $input->getData('total_paid');
        $result['Total_Refunded__c'] = $input->getData('total_refunded');
        $result['Total_Due__c'] = $input->getData('total_due');                
        $result['Pricebook2Id'] =  $pricebook_id==0 ? $this->createPriceBook($priceBookdata, $order_id) : $pricebook_id;

        $result['Payment_Information__c'] = Mage::helper('apdinteract_salesforce')->getPaymentInfo($order_id);
        //$result['Pricebook2Id'] = '01s2800000CBwtT';

        Zend_debug::dump($result);
        //$this->sendOrderDetails($order_id); //order details
        
        return $result;
    }
        
    
    

    protected function _getBillingByID($billing_id, &$result, $customer_id, $store_id, $email, $type = 'Billing') {
        $billing = Mage::getModel('sales/order_address')->load($billing_id);

        $result[$type . "Street"] = $billing->getData("street");
        $result[$type . "City"] = $billing->getData("city");
        $result[$type . "PostalCode"] = $billing->getData("postcode");
        $result[$type . "State"] = $billing->getData("region");
        $result[$type . "Country"] = $billing->getData("country_id");

        if (is_null($customer_id) || $customer_id == '') {            
			//echo $email."====\n";
            $result['AccountId'] = $this->_getAccountByEmail($store_id, $email, $billing_id);
        } else {
			//echo $email."00000\n";
            $result['AccountId'] = $this->_getOrAddSFID($customer_id, $billing_id, $email, $store_id);
        }
        
        
    }

    protected function _getStatusByCode($code) {
        $array = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
        foreach ($array as $ar):
            if ($ar['status'] == $code):
                return $ar['label'];
                break;
            endif;
        endforeach;
    }

    protected function _getGroupById($group_id) {
        return Mage::getModel('customer/group')->load($group_id)->getCustomerGroupCode();
    }

    protected function _getStoreLocationByID($store_id) {
        $store = Mage::getModel('storelocator/stores')->load($store_id);
        return $store->getTitle();
    }

    protected function _getAccountByEmail($websiteId, $customerEmail, $billing_id) {
        $id = 0; 
        $customer = Mage::getModel('customer/customer')
                ->setWebsiteId($websiteId)
                ->loadByEmail($customerEmail);
        if($customer->getId())
        $id = $customer->getId();
        
        return $this->_getOrAddSFID($id, $billing_id, $customerEmail, $websiteId);
    }

    protected function _getOrAddSFID($id, $billing_id, $email, $store_id) {
        
        $model = Mage::getModel('customer/customer');
        $helper = Mage::helper('apdinteract_salesforce');
        $class = get_class($model);

        $account = $helper->getSFId($id, $class);
                

        return $account > 0 ? $account : $helper->addCustomerByBilling($billing_id, $store_id, $email, $id);
    }
    
    
    protected function createContract($billing_id, $result, $order_date, $type="Billing", $order_id) {
        $billing_address = Mage::helper('apdinteract_salesforce')->loadByBillingId($billing_id);
        $billing = array();
        
        
        
        $data =  array("AccountId"=>$result['AccountId'],
                       "StartDate" =>  $result['EffectiveDate'],                                      
                       "ContractTerm" => "36"
                        );
        
        $data[$type . "Street"] = $billing_address["street"];
        $data[$type . "City"] = $billing_address["city"];
        $data[$type . "PostalCode"] = $billing_address["postcode"];
        $data[$type . "State"] = $billing_address["region"];
        $data[$type . "Country"] = $billing_address["country_id"];
                 
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => "Contract"));
        $connector->authorize();
        $result = $connector->create($data)->getResult();
        
        //Zend_debug::dump($result);
        
        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        $order = Mage::getModel("sales/order")->load($order_id);
        if(isset($result->id) !=''):                                  
            $dictionary->saveDictionary($order, $result->id,'Contract');
            return $result->id;
        elseif(isset($result[0]->errorCode) && $result[0]->errorCode=='DUPLICATE_VALUE'):            
            $message = explode(":",$result[0]->message);
            $dictionary->saveDictionary($order,trim($message[3]),'Contract');
            return $message[3];
        endif; 
        
    }
    
       
    protected function createPriceBook($data, $order_id) {
        
        $order = Mage::getModel('sales/order')->load($order_id);
        $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => "Pricebook2"));
        $connector->authorize();
        $result = $connector->create($data)->getResult();
        //Zend_debug::dump($result);
        
        if(isset($result->id) !=''):                                  
        $dictionary->saveDictionary($order, $result->id,'Book');
        return $result->id;
        elseif(isset($result[0]->errorCode) && $result[0]->errorCode=='DUPLICATE_VALUE'):            
            $message = explode(":",$result[0]->message);
            $dictionary->saveDictionary($order,trim($message[3]),'Book');
            return $message[3];
        endif; 
    }
    

}
