<?php

class ApdInteract_Salesforce_Model_Process_Business_Review extends ApdInteract_Salesforce_Model_Core_Process_Abstract {

    public function add() {
        $param = array("entity" => "Product_Review__c");
        $mapper = Mage::helper("apdinteract_salesforce/mapper_review");
        $data = $this->input;
        $sfobj = $mapper->map($data);

        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", $param);
        $this->result = $connector->create($sfobj)->getResult();

        return $this;
    }

    public function update() {
        
        $param = array("entity" => "Product_Review__c");
        $mapper = Mage::helper("apdinteract_salesforce/mapper_review");
        $data = $this->input;
        $sfobj = $mapper->map($data);
        $sfid = $sfobj["salesforce_id"];
        unset($sfobj['salesforce_id']);
        unset($sfobj['Customer_Email__c']);
                        
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", $param);
        $connector->authorize();
        $this->result = $connector->update($sfid, $sfobj)->getResult();
        
        return $this; 
    }
    
    public function delete() {
        
        $param = array("entity" => "Product_Review__c");
        $sfobj = array("Deleted_In_Magento__c"=>1);
        $review_id = $this->input;          
        $sfid = Mage::helper('apdinteract_salesforce/mapper_review')->getReviewSalesforceId($review_id);        
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", $param);
        $connector->authorize();
        $this->result = $connector->update($sfid, $sfobj)->getResult();
       
        return $this; 
    }

}
