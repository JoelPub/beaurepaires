<?php
class ApdInteract_Storecategory_Block_Adminhtml_Storecategory_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId("storecategoryGrid");
        $this->setDefaultSort("id");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel("storecategory/storecategory")->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        $this->addColumn("id", array(
            "header" => Mage::helper("storecategory")->__("ID"),
            "align" => "right",
            "width" => "50px",
            "type" => "number",
            "index" => "id",
        ));

        $this->addColumn("name", array(
            "header" => Mage::helper("storecategory")->__("Name"),
            "index" => "name",
        ));
        $this->addColumn("code", array(
            "header" => Mage::helper("storecategory")->__("Code"),
            "index" => "code",
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row) {
        return $this->getUrl("*/*/edit", array("id" => $row->getId()));
    }

    protected function _prepareMassaction() {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_storecategory', array(
            'label' => Mage::helper('storecategory')->__('Delete'),
            'url' => $this->getUrl('*/adminhtml_storecategory/massRemove'),
            'confirm' => Mage::helper('storecategory')->__('Are you sure?')
        ));
        return $this;
    }

}
