<?php
class ApdInteract_Geopostcode_Model_Resource_Geopostcode extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('apdinteract_geopostcode/geopostcode', 'entity_id');
    }
}

