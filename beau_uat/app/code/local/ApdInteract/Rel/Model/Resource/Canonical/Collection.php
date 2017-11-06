<?php
class ApdInteract_Rel_Model_Resource_Canonical_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        parent::_construct();

        $this->_init(
            'apdinteract_rel/canonical',
            'apdinteract_rel/canonical'
        );
    }
}
