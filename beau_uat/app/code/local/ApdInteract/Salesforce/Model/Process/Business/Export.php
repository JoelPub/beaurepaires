<?php
class ApdInteract_Salesforce_Model_Process_Business_Export
extends ApdInteract_Salesforce_Model_Core_Process_Abstract {
	
	public function process() {
	
		echo "export customers \n";
		$customers = Mage::getModel("apdinteract_salesforce/process_business_export_customers");
		$customers->process();		
		echo "export addresses \n";
		$customers_address = Mage::getModel("apdinteract_salesforce/process_business_export_customers_addresses");
		$customers_address->process();		
		echo "export vehicles \n";
		$customers_vehicles = Mage::getModel("apdinteract_salesforce/process_business_export_customers_vehicles");
		$customers_vehicles->process();
		
		return $this;
	}
	
}