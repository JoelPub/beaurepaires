<?php

class ApdInteract_Salesforce_Model_Process_Business_Vircommercial extends ApdInteract_Salesforce_Model_Core_Process_Abstract {

    public function add() {        
        $param = array("entity" => "VIR_Commercial_Report__c");
        $mapper = Mage::helper("apdinteract_salesforce/mapper_vircommercial");
        $data = $this->input;
        $sfobj = $mapper->map($data);
        unset($sfobj['salesforce_id']);                        
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", $param);
        $this->result = $connector->create($sfobj)->getResult();

        return $this;
    }

    public function update() {        
        $param = array("entity" => "VIR_Commercial_Report__c");
        $mapper = Mage::helper("apdinteract_salesforce/mapper_vircommercial");
        $data = $this->input;
        $sfobj = $mapper->map($data);
        $sfid = $sfobj["salesforce_id"];
        unset($sfobj['salesforce_id']);        
                        
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", $param);
        $connector->authorize();
        $this->result = $connector->update($sfid, $sfobj)->getResult();
        
        return $this; 
    }
    
    public function delete() {
        
        $param = array("entity" => "VIR_Commercial_Report__c");
        $sfobj = array("Deleted_In_Magento__c"=>1);
        $vir_id = $this->input;          
        $sfid = Mage::helper('apdinteract_salesforce/mapper_vircommercial')->getVirCommercialSalesforceId($vir_id);        
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", $param);
        $connector->authorize();
        $this->result = $connector->update($sfid, $sfobj)->getResult();
       
        return $this; 
    }

}
