<?php 
class ApdInteract_Onsale_Model_Layer_Filter_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute
{
    protected function _createItem($label, $value, $count = 0)
    {
        return Mage::getModel('apdinteract_onsale/layer_filter_item')
            ->setFilter($this)
            ->setLabel($label)
            ->setValue($value)
            ->setCount($count);
    }
}