<?php

class ApdInteract_Salesforce_Helper_Reader_Product_Configurable extends ApdInteract_Salesforce_Helper_Core_Reader_Abstract {

    public function __construct() {
        $this->model_name = "catalog/product";
        parent::__construct();
    }

    /**
     * 
     * {@inheritDoc}
     * @see ApdInteract_Salesforce_Helper_Core_Reader_Abstract::__init()
     */
    public function __init() {
        parent::__init();

        $class = get_class(Mage::getModel('catalog/product'));
        $lastUpdated = Mage::Helper('apdinteract_salesforce')->getLastUpdatedDateTime($class);       
        $this->collection->addAttributeToSelect('entity_id');
        $this->collection->addAttributeToFilter('type_id', array('eq' => 'configurable'));        
        //$this->collection->addAttributeToFilter('sku', array('eq' => '532970-P'));
        $this->collection->addAttributeToFilter('updated_at', array('gteq' => $lastUpdated));
        //$this->collection->getSelect()->limit(1);
        //echo $this->collection->getSelect().'<br>';
    }

}
