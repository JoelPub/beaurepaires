<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Customer
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Sales_Creditmemo_Comment extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

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
       $result = parent::map($input);
       $credit_memo = get_class(Mage::getModel('sales/order_creditmemo')); 
       $result['Credit_Memo__c'] = Mage::helper('apdinteract_salesforce')->getSFId($input->getData('parent_id'),$credit_memo);           
       $result['Created_Date__c'] = date("c", strtotime($input->getData('created_at')));
       
       //Zend_Debug::dump($result);       
       return $result;
    }
    
    private function _getStatus($status) {
        $statuses = array(0=>"YES",1=>"NO");
        return $statuses['status'];
    }
        
    
    

}
