<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Customer
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Sales_Invoice_Comment extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     */
    public function __construct() {
       $this->protocol = array(           
            "comment"=> "comment__c",
            "entity_id" => "Magento_ID__c"          
        );
    }

    /**
     * 
     * 
     * @param Mage_Customer_Model_Customer $input
     */
    public function map($input) {
       $helper = Mage::Helper('apdinteract_salesforce');
       $result = parent::map($input);
       $invoice = get_class(Mage::getModel('sales/order_invoice')); 
       $result['Invoice__c'] = Mage::helper('apdinteract_salesforce')->getSFId($input->getData('parent_id'),$invoice);    
       $billing = $helper->loadByBillingId($input->getData('billing_address_id'));
       $result['iscustomernotified__c'] = $this->_getStatus($input->getData('is_customer_notified')); 
       $result['isvisibleonfront__c'] = $this->_getStatus($input->getData('is_visible_on_front'));      
       $result['Created_Date__c'] = date("c", strtotime($input->getData('created_at')));
       
       //Zend_Debug::dump($result);       
       return $result;
    }
    
    private function _getStatus($status) {
        $statuses = array(0=>"NO",1=>"YES",2=>"YES");
        return $statuses['status'];
    }
        
    
    

}
