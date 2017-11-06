<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Customer
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Sales_Invoice_Invoice extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     */
    public function __construct() {
       $this->protocol = array(           
            "base_currency_code"=> "basecurrencycode__c",
            "base_grand_total" => "basegrandtotal__c",
            "global_currency_code" => "globalcurrencycode__c",
            "grand_total" => "grandtotal__c",
            "entity_id" => "MagentoID__c",
            "order_currency_code" => "ordercurrencycode__c",
            "increment_id" => "Magento_Invoice__c",            
            "discount_amount" => "Discount__c",            
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
       $result['state__c'] = $this->_getStatus($input->getData('state'));
       $result['storeid__c'] = $helper->getWebsiteByStoreId($input->getData('store_id')); 
       $result['Created_Date__c'] =  date("c", strtotime($input->getData('updated_at')));
       $result['Grand_Total_Excl_Tax__c'] =  $input->getData('grand_total') - $input->getData('tax_amount');
       $result['Subtotal__c'] =  $input->getData('subtotal_incl_tax');       
       
       //Zend_Debug::dump($result);       
       return $result;
    }
    
    private function _getStatus($status) {
        $statuses = array(1=>"Open",2=>"Paid",3=>"Cancelled");
        return $statuses['status'];
    }
        
    
    

}
