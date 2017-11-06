<?php

class ApdInteract_Salesforce_Model_Process_Business_Product extends ApdInteract_Salesforce_Model_Core_Process_Abstract {

    public function process() {

        $model = Mage::getModel("catalog/product");
        Mage::helper('apdinteract_salesforce')->disableFlatTables(0);
        echo "export configurable products \n";
        $configurable = Mage::getModel("apdinteract_salesforce/process_business_export_product_configurable");
        $configurable->process();
        echo "export simple products \n";
        $simple = Mage::getModel("apdinteract_salesforce/process_business_export_product_simple");
        $simple->process();
        echo "add standard prices.. \n";
        $this->createStandardPrice(); //create product standard prices
       
        Mage::helper('apdinteract_salesforce')->disableFlatTables(1);
        Mage::getModel("apdinteract_salesforce/updates")->saveUpdates($model);
        
        echo "export group/tier prices \n";
        Mage::helper('apdinteract_salesforce')->getAllGroupAndTierPrices();
        
        return $this;
    }

    public function delete() {
        $class = get_class(Mage::getModel("catalog/product"));
        $param = array("entity" => "Product2");
        $sfobj = array("Deleted_In_Magento__c" => 1);
        $product_id = $this->input;
        $sfid = Mage::helper('apdinteract_salesforce')->getSFId($class, $product_id);
        $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", $param);
        $connector->authorize();
        $this->result = $connector->update($sfid, $sfobj)->getResult();

        return $this;
    }

    public function createStandardPrice() {
        $class = "Mage_Catalog_Model_Product";
        $class_price = "Mage_Catalog_Model_Product_Standard_Price";

        /**
         * Get the resource model
         */
        $resource = Mage::getSingleton('core/resource');

        /**
         * Retrieve the read connection
         */
        $readConnection = $resource->getConnection('core_read');

        $table = $resource->getTableName('apdinteract_salesforce/dictionary');

        $query = "SELECT salesforce_id,entity_id FROM $table 
					WHERE entity_type='$class' 
							AND entity_id NOT IN 
								(SELECT entity_id FROM $table WHERE entity_type='$class_price')
									ORDER BY entity_id DESC";
        /**
         * Execute the query and store the results in $results
         */
        $results = $readConnection->fetchAll($query);

        /**
         * Print out the results
         */
        $extra = "Standard_Price";
        $Pricebook2Id = Mage::getStoreConfig('salesforce/pricebook_default/configuration'); //'01s2800000CBwtT';
        $entity = "PricebookEntry";    
        $data = array();
        foreach ($results as $object):
            $product_id = $object['salesforce_id'];
            $model = Mage::getModel('catalog/product')->load($object['entity_id']);            
            $data= array("Product2Id" => $product_id, "IsActive" => true, "Pricebook2Id" => $Pricebook2Id, "UnitPrice" => $model->getFinalPrice()); //create data      
            Mage::Helper('apdinteract_salesforce')->createSfEntries($entity, $data, $extra, $model); //send to salesforce 
        endforeach;
        
        
    }

}
