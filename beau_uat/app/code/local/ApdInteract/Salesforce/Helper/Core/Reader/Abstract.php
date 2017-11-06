<?php

class ApdInteract_Salesforce_Helper_Core_Reader_Abstract extends Mage_Core_Helper_Abstract {

    protected $model_name;
    protected $model;
    protected $id_name;
    protected $last_updated;

    /**
     * 
     * @var Mage_Core_Model_Resource_Db_Collection_Abstract
     */
    protected $collection;

    public function __construct() {
        $this->id_name = "entity_id";
        $this->_getProductLastUpdate();
        $this->__init();
    }

    /**
     * 
     * @return ApdInteract_Salesforce_Helper_Core_Reader_Abstract
     */
    public function __init() {
        $this->model = Mage::getModel($this->model_name);
        $this->collection = $this->model->getCollection();
        return $this;
    }

    /**
     * add updated filter
     * 
     * @param number $nodays
     * @return array
     */
    public function addUpdatedFilter($nodays = 1) {
        $this->collection->addFieldToFilter('updated_at', array('gt' => Mage::getModel('core/date')->date('Y-m-d H:i:s', strtotime("-1 $nodays"))));
        return $this;
    }

    /**
     * 
     * @param array $dictionaries
     * @return ApdInteract_Salesforce_Helper_Core_Reader_Abstract
     */
    public function addToCreateFilter($dictionaries = array()) {
        $ids = array_keys($dictionaries);
        if ($ids) {
            $this->collection->addFieldToFilter($this->id_name, array("nin" => $ids));
        }
        return $this;
    }

    /**
     * 
     * @param array $dictionaries
     * @return ApdInteract_Salesforce_Helper_Core_Reader_Abstract
     */
    public function addToUpdateFilter($dictionaries = array()) {
        $ids = array_keys($dictionaries);
        $this->collection->addFieldToFilter($this->id_name, array("in" => $ids));
        return $this;
    }

    /**
     * get model class name
     * 
     * @return string
     */
    public function getModelClassName() {
        return get_class($this->model);
    }

    /**
     * get data
     * 
     * @return array
     */
    public function load() {
        $result = $this->collection->load();
        $this->__init();
        return $result;
    }

    public function _getProductLastUpdate() {
        $class = get_class(Mage::getModel('catalog/product'));
        $this->last_updated = Mage::helper('apdinteract_salesforce')->getLastUpdatedDateTime($class);
        return $this;
    }

}
