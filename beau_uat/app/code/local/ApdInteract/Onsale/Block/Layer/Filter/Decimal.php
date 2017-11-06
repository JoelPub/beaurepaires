<?php 
class ApdInteract_Onsale_Block_Layer_Filter_Decimal extends Mage_Catalog_Block_Layer_Filter_Decimal
{
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'apdInteract_searchResult/layer_filter_decimal';
    }
}