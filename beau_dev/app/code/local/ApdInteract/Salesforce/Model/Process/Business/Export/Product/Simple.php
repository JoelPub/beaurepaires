<?php

class ApdInteract_Salesforce_Model_Process_Business_Export_Product_Simple extends ApdInteract_Salesforce_Model_Core_Process_Export_Abstract {

    public function __construct($params = array()) {
        $this->ditionary_collection = Mage::getModel("apdinteract_salesforce/dictionary")->getCollection();
        $this->sao = Mage::getModel("apdinteract_salesforce/sao_product");
        $this->reader = Mage::helper("apdinteract_salesforce/reader_product_simple");
        $this->mapper = Mage::helper("apdinteract_salesforce/mapper_product");
        parent::__construct($params);
    }

}
