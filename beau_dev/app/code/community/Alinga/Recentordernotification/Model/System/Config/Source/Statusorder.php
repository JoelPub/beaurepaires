<?php

class Alinga_Recentordernotification_Model_System_Config_Source_Statusorder
{
    public function toOptionArray()
    {
        return array(
            'completed_order'    => Mage::helper('recentordernotification')->__('Completed Order'),
            'any_order'     => Mage::helper('recentordernotification')->__('Any Order'),
        );
    }
}