<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Lead
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Review extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     * 
     * @param Mage_Customer_Model_Lead $input
     */
    public function map($input) {
        $model =  Mage::getModel('catalog/product');
        $result['Customer_Email__c'] = $input['email'];
        $result['Detail__c'] = $input['detail'];
        $result['Magento_Product_Review_Id__c'] = $input['review_id'];
        $result['Nickname__c'] = $input['nickname'];
        $result['Product_Name__c'] = $input['product_name'];
        $result['Score__c'] = $input['score'];
        $result['SKU__c'] = $input['product_sku'];
        $result['Status__c'] = $input['status'];
        $result['Website__c'] = $input['website'];
        $result['Title__c'] = $input['title'];
        $id = $this->getAccountSalesforceId($input['customer_id']);
        $rid = $this->getReviewSalesforceId($input['review_id']);  
        $result['Product__c'] = Mage::Helper('apdinteract_salesforce')->getSFId(Mage::app()->getRequest()->getParam('id'), get_class($model));
        
        if ($id > 0)
            $result["Customer__c"] = $id;
        
        if($rid !='')
            $result["salesforce_id"] = $rid;                
        
        return $result;
    }

    public function getAccountSalesforceId($cid) {
        $customer = Mage::getModel("customer/customer")->load($cid);
        $dictionary = Mage::getSingleton("apdinteract_salesforce/dictionary")
                ->getCollection()
                ->getDictionaryByModel($customer);
        return $dictionary->getData("salesforce_id");
    }
    
    public function getReviewSalesforceId($rid) {        
        $review = Mage::getModel('review/review')->load($rid);
        $dictionary = Mage::getSingleton("apdinteract_salesforce/dictionary")
                ->getCollection()
                ->getDictionaryByModel($review);        
        return $dictionary->getData("salesforce_id");
    }
        

}
