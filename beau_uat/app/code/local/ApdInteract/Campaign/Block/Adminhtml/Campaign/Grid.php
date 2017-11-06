<?php

class ApdInteract_Campaign_Block_Adminhtml_Campaign_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("campaignGrid");
        $this->setDefaultSort("entity_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel("campaign/campaign")->getCollection();        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn("entity_id", array(
            "header" => Mage::helper("campaign")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "entity_id",
        ));
        
        $this->addColumn("date_added", array(
            "header" => Mage::helper("campaign")->__("Subscribed"),
            "index" => "date_added",
        ));

        $this->addColumn("first_name", array(
            "header" => Mage::helper("campaign")->__("First Name"),
            "index" => "first_name",
        ));
        $this->addColumn("last_name", array(
            "header" => Mage::helper("campaign")->__("Last Name"),
            "index" => "last_name",
        ));
        $this->addColumn("email", array(
            "header" => Mage::helper("campaign")->__("Email"),
            "index" => "email",
        ));
        $this->addColumn("mobile", array(
            "header" => Mage::helper("campaign")->__("Mobile"),
            "index" => "mobile",
        ));
        $this->addColumn("postcode", array(
            "header" => Mage::helper("campaign")->__("Postcode"),
            "index" => "postcode",
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header' => Mage::helper('campaign')->__('Store View'),
                'index' => 'store_id',
                'type' => 'store',
                'store_all' => true,
                'store_view' => true,
                'sortable' => true,
                'filter_condition_callback' => array($this,
                    '_filterStoreCondition'),
            ));
        }
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return false;
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('entity_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_campaign', array(
            'label' => Mage::helper('campaign')->__('Delete'),
            'url' => $this->getUrl('*/adminhtml_campaign/massRemove'),
            'confirm' => Mage::helper('campaign')->__('Are you sure?')
        ));
        return $this;
    }

    protected function _filterStoreCondition($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }

}
