<?php
class ApdInteract_Storecategory_Model_Mysql4_Storecategory extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("storecategory/storecategory", "id");
    }
}