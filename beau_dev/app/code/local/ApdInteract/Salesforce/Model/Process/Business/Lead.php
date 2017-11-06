<?php

class ApdInteract_Salesforce_Model_Process_Business_Lead
extends ApdInteract_Salesforce_Model_Core_Process_Abstract {
	
	public function process() {
		$param = array("entity"=>"Lead");
		$mapper = Mage::helper("apdinteract_salesforce/mapper_lead");
		$data = $this->input;                
		$sfobj = $mapper->map($data);
		
		$connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", $param);
                $this->result = $connector->create($sfobj)->getResult();
                	
		return $this;
	}
        
       
	
}