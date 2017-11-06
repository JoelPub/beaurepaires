<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Customer
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Sales_Creditmemo_Creditmemo extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     */
    public function __construct() {
       $this->protocol = array(           
           "base_grand_total" => "basegrandtotal__c",
           "base_to_global_rate" => "basetoglobalrate__c",
           "base_to_order_rate" => "basetoorderrate__c",                     
           "grand_total" => "grandtotal__c",
           "entity_id" => "Magento_Id__c",           
           "store_to_base_rate" => "storetobaserate__c",
           "store_to_order_rate" => "storetoorderrate__c",
           "increment_id" => "Credit_Memo__c",          
           "discount_amount" => "Discount__c",
           "base_adjustment_positive" => "Adjustment_Refund__c",             
           "tax_amount" => "Tax__c"
           
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
       $sales = get_class(Mage::getModel('sales/order')); 
       $result['Order__c'] = Mage::helper('apdinteract_salesforce')->getSFId($input->getData('order_id'),$sales);       
       $billing = $helper->loadByBillingId($input->getData('billing_address_id'));
       $result['billingname__c'] = $billing['firstname']." ".$billing['lastname'];
       $result['creditmemostatus__c'] = $this->_getStatus($input->getData('state'));
       $result['Adjustment_Fee__c'] = $input->getData('adjustment_negative');
       $result['Created_Date__c'] = date("c", strtotime($input->getData('updated_at')));
       $result['Grand_Total_Excl_Tax__c'] =  $input->getData('grand_total') - $input->getData('tax_amount');
       $result['Subtotal__c'] =  $input->getData('subtotal_incl_tax');       
       
       //Zend_Debug::dump($result);
       return $result;
    }
    
    private function _getStatus($status) {
        $statuses = array(1=>"Open",2=>"Refunded",3=>"Cancelled");
        return $statuses['status'];
    }

    

}
