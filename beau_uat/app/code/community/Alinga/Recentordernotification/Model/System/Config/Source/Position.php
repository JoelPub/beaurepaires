<?php

class Alinga_Recentordernotification_Model_System_Config_Source_Position
{
    public function toOptionArray()
    {
        return array(
            'bl'    => Mage::helper('recentordernotification')->__('Bottom-Left'),
            'br'     => Mage::helper('recentordernotification')->__('Bottom-Right'),
            'bc'    => Mage::helper('recentordernotification')->__('Bottom-Center'),
            'tl'    => Mage::helper('recentordernotification')->__('Top-Left'),
            'tr'     => Mage::helper('recentordernotification')->__('Top-Right'),
            'tc'    => Mage::helper('recentordernotification')->__('Top-Center'),
        );
    }
}