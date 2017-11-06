<?php
class ApdInteract_Costar_Model_Resource_Costar extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('apdinteract_costar/costar', 'costar_id');        
    }
}

