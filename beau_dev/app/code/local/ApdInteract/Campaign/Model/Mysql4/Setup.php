<?php

class ApdInteract_Campaign_Model_Mysql4_Setup extends Mage_Core_Model_Mysql4_Abstract {

    protected function _construct() {
        $this->_init("campaign/setup", "entity_id");
    }

}
