<?php 
class ApdInteract_Onsale_Block_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute
{
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'apdinteract_onsale/layer_filter_attribute';
    }
}