<?php
class ApdInteract_Rel_Model_Resource_Canonical extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('apdinteract_rel/canonical', 'url_id');
    }
}

