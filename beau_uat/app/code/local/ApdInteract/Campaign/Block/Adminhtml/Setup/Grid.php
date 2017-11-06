<?php

class ApdInteract_Campaign_Block_Adminhtml_Setup_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("SetupGrid");
        $this->setDefaultSort("entity_id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel("campaign/setup")->getCollection();
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

        $this->addColumn("campaign_name", array(
            "header" => Mage::helper("campaign")->__("Campaign"),
            "index" => "campaign_name",
        ));

        $this->addColumn("sku", array(
            "header" => Mage::helper("campaign")->__("Product Sku"),
            "index" => "sku",
        ));

        $this->addColumn("cms_page", array(
            "header" => Mage::helper("campaign")->__("CMS Page Identifier"),
            "index" => "cms_page",
        ));

        $this->addColumn("thank_you", array(
            "header" => Mage::helper("campaign")->__("Confirmation Page Identifier"),
            "index" => "thank_you",
        ));

        $this->addColumn('active', array(
            "header" => Mage::helper('campaign')->__('Status'),
            "index" => "active",
            "type" => "options",
            "options" => array(1 => 'Enabled', 2 => 'Disabled')
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

    protected function _prepareMassaction() {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('entity_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_campaign', array(
            'label' => Mage::helper('campaign')->__('Delete'),
            'url' => $this->getUrl('*/adminhtml_setup/massRemove'),
            'confirm' => Mage::helper('campaign')->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getEntityId()));
    }

    protected function _filterStoreCondition($collection, $column) {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreFilter($value);
    }

}
