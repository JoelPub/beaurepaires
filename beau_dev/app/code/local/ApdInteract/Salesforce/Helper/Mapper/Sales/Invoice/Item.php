<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Customer
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Sales_Invoice_Item extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     */
    public function __construct() {
       $this->protocol = array(           
           "additional_data" => "additionaldata__c",
           "base_cost" => "basecost__c",
           "base_discount_amount" => "basediscountamount__c",
           "base_gomage_gift_wrap_amount" => "basegomagegiftwrapamount__c",
           "base_hidden_tax_amount" => "basehiddentaxamount__c",
           "base_price" => "baseprice__c",
           "base_price_incl_tax" => "basepriceincltax__c",
           "base_row_total" => "baserowtotal__c",
           "base_row_total_incl_tax" => "baserowtotalincltax__c",
           "base_tax_amount" => "basetaxamount__c",
           "base_weee_tax_applied_amount" => "baseweeetaxappliedamount__c",
           "base_weee_tax_applied_row_amnt" => "baseweeetaxappliedrowamnt__c",
           "base_weee_tax_disposition" => "baseweeetaxdisposition__c",
           "base_weee_tax_row_disposition" => "baseweeetaxrowdisposition__c",
           "description" => "description__c",
           "discount_amount" => "discountamount__c",
           "gomage_gift_wrap" => "gomagegiftwrap__c",
           "gomage_gift_wrap_amount" => "gomagegiftwrapamount__c",
           "hidden_tax_amount" => "hiddentaxamount__c",         
           "entity_id" => "Magento_ID__c",
           "name" => "name__c",
           "order_item_id" => "rderitemid__c",
           "price" => "price__c",
           "price_incl_tax" => "priceincltax__c",
           "product_id" => "productid__c",
           "qty" => "qty__c",
           "row_total" => "rowtotal__c",
           "row_total_incl_tax" => "rowtotalincltax__c",
           "sku" => "sku__c",
           "tax_amount" => "taxamount__c",
           "weee_tax_applied" => "weeetaxapplied__c",
           "weee_tax_applied_amount" => "weeetaxappliedamount__c",
           "weee_tax_applied_row_amount" => "weeetaxappliedrowamount__c",
           "weee_tax_disposition" => "weeetaxdisposition__c",
           "weee_tax_row_disposition" => "weeetaxrowdisposition__c",
           
           
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
    
       //Zend_Debug::dump($result);       
              
       return $result;
    }
    
     
    
    

}
