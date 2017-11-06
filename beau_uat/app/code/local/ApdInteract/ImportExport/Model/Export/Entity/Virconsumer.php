<?php

class ApdInteract_ImportExport_Model_Export_Entity_Virconsumer extends Mage_ImportExport_Model_Export_Entity_Abstract
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
        $vir_consumer = Mage::getModel('apdinteract_vir/order')->getCollection()
                        ->addFieldToSelect('*');

        $vir_first_item = $vir_consumer->getFirstItem()->getData();
        $writer->setHeaderCols(array_keys($vir_first_item));

        $vir_all_items = $vir_consumer->getData();
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
        return 'virconsumer';
    }
    
     public function exportFile()
    {
        
         $this->export();         

        $writer = $this->getWriter();

        return array(
            'rows'  => $writer->getRowsCount(),
            'value' => $writer->getDestination()
        );
    }
}
