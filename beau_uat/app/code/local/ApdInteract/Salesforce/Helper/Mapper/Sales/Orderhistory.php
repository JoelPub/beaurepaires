<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Customer
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Sales_Orderhistory extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     */
    public function __construct() {
       $this->protocol = array(           
            "comment" => "Comment__c",            
            "entity_name" => "Entity_Name__c",                       
            "status" => "Status__c"
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
       $result['Order__c'] = Mage::helper('apdinteract_salesforce')->getSFId($input->getData('parent_id'),$sales);       
       $result['Is_Visible_on_Front__c'] = ($input->getData('is_visible_on_front')==1 || $input->getData('is_visible_on_front')==2)? true : false;
       $result['Is_Customer_Notified__c'] = ($input->getData('is_customer_notified')==1 || $input->getData('is_customer_notified')==2)? true : false;
       $result['Update_Date__c'] = date("c", strtotime($input->getData('created_at')));
       
       Zend_Debug::dump($result);
       return $result;
    }

    

}
