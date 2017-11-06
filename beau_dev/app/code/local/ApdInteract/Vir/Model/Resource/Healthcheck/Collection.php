<?php

class ApdInteract_Vir_Model_Resource_Healthcheck_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        parent::_construct();

        $this->_init(
            'apdinteract_vir/healthcheck',
            'apdinteract_vir/healthcheck'
        );
    }
}
