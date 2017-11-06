<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Customer
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Sales_Transactions extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     */
    public function __construct() {
       $this->protocol = array(           
            "txn_id" => "txnid__c",            
            "txn_type" => "txntype__c",                       
            "parent_id" => "parenttxnid__c",
            "transaction_id" => "paymentid__c"
        );
    }

    /**
     * 
     * 
     * @param Mage_Customer_Model_Customer $input
     */
    public function map($input) {

       $result = parent::map($input);
       $sales = get_class(Mage::getModel('sales/order')); 
       $result['Order__c'] = Mage::helper('apdinteract_salesforce')->getSFId($input->getData('order_id'),$sales);       
       $result['additionalinformation__c'] = $input->getData('additional_information')[0];
       $result['isclosed__c'] = ($input->getData('is_closed')==1)? true : false;

       
       //Zend_Debug::dump($result);
       return $result;
    }

    

}
