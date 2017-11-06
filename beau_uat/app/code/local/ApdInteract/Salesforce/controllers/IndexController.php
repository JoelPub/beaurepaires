<?php
/**
 * 
 * APD interact salesforce
 * 
 * @author hyan
 * @todo remove all test method when go live
 */

class ApdInteract_Salesforce_IndexController extends Mage_Core_Controller_Front_Action
{
	public function IndexAction() {		
		$connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array());
		$connector->authorize();
		$result = $connector->query("SELECT Name, Id from Account LIMIT 100")->getResult();
		print_r($result);		
	}
	
	public function TestcreateAction(){
		$connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity"=>"Account"));
		$connector->authorize();
		$result = $connector->create( array("Name"=>"a new record","email__c"=>"test@apdgroup.com"))->getResult();
		print_r($result);
		$result = $connector->query("SELECT Name, Id from Account LIMIT 100")->getResult();
		print_r($result);
	}
	
	public function TestupdateAction(){
		$connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity"=>"Account"));
		$connector->authorize();
		$result = $connector->update("00128000011TPEVAA4", array("Name"=>"Haihao update from magento 2"))->getResult();
		$result = $connector->query("SELECT Name, Id from Account LIMIT 100")->getResult();
		print_r($result);
	}
	
	public function TestbulkcreateAction() {
		$rows = array(
			array("Name"=>"a new record 1","email__c"=>"test1@apdgroup.com"),
			array("Name"=>"a new record 2","email__c"=>"test2@apdgroup.com"),
			array("Name"=>"a new record 3","email__c"=>"test3@apdgroup.com")
		);
		$connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity"=>"Account"));
		$connector->authorize();
		$result = $connector->bulkCreate($rows)->getResult();
		print_r($result);
	}
	
	public function TestbulkupdateAction() {
		$connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity"=>"Account"));
		$connector->authorize();
		$rows = array(
				"001280000121pmtAAA" => array("Name"=>"a new updated record 1","email__c"=>"test1@apdgroup.com"),
				"001280000121pmVAAQ" => array("Name"=>"a new haha record 2","email__c"=>"test2@apdgroup.com"),
				"001280000121pmuAAA" => array("Name"=>"a new lala record 3","email__c"=>"test3@apdgroup.com")
		);
		$result = $connector->bulkUpdate($rows)->getResult();
		print_r($result);
		$result = $connector->query("SELECT Name, Id from Account LIMIT 100")->getResult();
		print_r($result);
	}
	
	public function SampleAction(){
		$process = Mage::getModel("apdinteract_salesforce/process_business_sample");
		$result  = $process->process()->getResult();
		var_dump($result);
	}
	
	public function SampleinputAction() {
		$input = array("name"=>"Analyn");
		$process = Mage::getModel("apdinteract_salesforce/process_business_sampleWithInput", $input);
		$result  = $process->process()->getResult();
		var_dump($result);
	}
	
}