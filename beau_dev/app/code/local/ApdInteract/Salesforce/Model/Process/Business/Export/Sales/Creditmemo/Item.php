<?php
class ApdInteract_Salesforce_Model_Process_Business_Export_Sales_Creditmemo_Item
extends ApdInteract_Salesforce_Model_Core_Process_Export_Abstract {

	public function __construct($params = array()){
		$this->ditionary_collection = Mage::getModel("apdinteract_salesforce/dictionary")->getCollection();
		$this->sao = Mage::getModel("apdinteract_salesforce/sao_sales_creditmemo_item");
		$this->reader = Mage::helper("apdinteract_salesforce/reader_sales_creditmemo_item");
		$this->mapper = Mage::helper("apdinteract_salesforce/mapper_sales_creditmemo_item");
		parent::__construct($params);
	}

}