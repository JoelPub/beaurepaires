<?php
class ApdInteract_Vehicle_Model_Resource_Vehicle_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        parent::_construct();

        $this->_init(
            'apdinteract_vehicle/vehicle',
            'apdinteract_vehicle/vehicle'
        );
    }
}
