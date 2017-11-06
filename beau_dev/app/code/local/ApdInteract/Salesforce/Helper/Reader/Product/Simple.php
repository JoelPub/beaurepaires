<?php

class ApdInteract_Salesforce_Helper_Reader_Product_Simple extends ApdInteract_Salesforce_Helper_Core_Reader_Abstract {

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
        $lastUpdated = Mage::helper('apdinteract_salesforce')->getLastUpdatedDateTime($class);
        $this->collection->addAttributeToSelect('*');
        $this->collection->addAttributeToFilter('type_id', array('eq' => 'simple'));        
        //$this->collection->addAttributeToFilter('entity_id', array('eq' => '3342'));
        $this->collection->addAttributeToFilter('updated_at', array('gteq' => $lastUpdated));

        //echo $this->collection->getSelect().'<br>';
    }

}
