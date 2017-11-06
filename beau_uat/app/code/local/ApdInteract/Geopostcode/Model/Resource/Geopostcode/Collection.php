<?php
class ApdInteract_Geopostcode_Model_Resource_Geopostcode_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        parent::_construct();

        $this->_init(
            'apdinteract_geopostcode/geopostcode',
            'apdinteract_geopostcode/geopostcode'
                
        );
    }
}
