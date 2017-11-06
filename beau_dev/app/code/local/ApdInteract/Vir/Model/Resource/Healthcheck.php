<?php

class ApdInteract_Vir_Model_Resource_Healthcheck extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('apdinteract_vir/healthcheck', 'entity_id');
    }
}

