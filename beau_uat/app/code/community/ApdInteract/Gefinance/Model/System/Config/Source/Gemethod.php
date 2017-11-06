<?php class ApdInteract_Gefinance_Model_System_Config_Source_Gemethod 
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(            
            array('value' => 'Interest Free', 'label' => Mage::helper('adminhtml')->__('Interest Free')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(            
            'Interest Free' => Mage::helper('adminhtml')->__('Interest Free'),
            
        );
    }
}