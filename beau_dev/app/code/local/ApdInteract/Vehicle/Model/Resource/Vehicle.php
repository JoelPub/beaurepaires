<?php
class ApdInteract_Vehicle_Model_Resource_Vehicle extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('apdinteract_vehicle/vehicle', 'vehicle_id');
    }
}

