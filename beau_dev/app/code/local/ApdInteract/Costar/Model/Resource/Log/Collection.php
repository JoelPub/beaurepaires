<?php
class ApdInteract_Costar_Model_Resource_Log_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
	
	protected function _construct()
    {
        parent::_construct();

        $this->_init(
            'apdinteract_costar/log'
                
        );
    }
}