<?php

class ApdInteract_ImportExport_Model_Export_Entity_Vircommercial extends Mage_ImportExport_Model_Export_Entity_Abstract
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Export process.
     *
     * @return string
     */
    public function export()
    {

        $writer = $this->getWriter();
        $vir_commercial = Mage::getModel('apdinteract_vir/ordercommercial')->getCollection()
                        ->addFieldToSelect('*');

        $vir_first_item = $vir_commercial->getFirstItem()->getData();
        $writer->setHeaderCols(array_keys($vir_first_item));

        $vir_all_items = $vir_commercial->getData();
        foreach($vir_all_items as $row){
            $writer->writeRow($row);
        }

        return $writer->getContents();
    }


    /**
     * @return mixed
     */
    public function getAttributeCollection()
    {
        return Mage::getResourceModel('eav/entity_attribute_collection')->setCodeFilter('website_id');

    }

    /**
     * EAV entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'vircommercial';
    }
    
     public function exportFile() {
        $this->export();

        $writer = $this->getWriter();

        return array(
            'rows' => $writer->getRowsCount(),
            'value' => $writer->getDestination()
        );
    }

}
