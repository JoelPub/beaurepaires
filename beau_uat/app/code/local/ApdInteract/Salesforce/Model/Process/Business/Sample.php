<?php

class ApdInteract_Salesforce_Model_Process_Business_Sample
extends ApdInteract_Salesforce_Model_Core_Process_Abstract {
	
	public function process() {
		/**
		$mapper = Mage::helper("apdinteract_salesforce/mapper_customer");
		$customer = Mage::getModel("customer/customer")->load(20678);
		$sfobj = $mapper->map($customer);
		
		$sao = Mage::getModel("apdinteract_salesforce/sao_customer");
		$this->result = $sao->save($sfobj)->getSalesforceId();
		var_dump($sao->getStatus());*/
		$param  	= array("entity" => "Product2");
		$connector 	= Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", $param);		
		$connector->authorize();
		$this->result = $connector->delete("01tp0000004Dshf")->getResult();
		
		return $this;
	}
	
}