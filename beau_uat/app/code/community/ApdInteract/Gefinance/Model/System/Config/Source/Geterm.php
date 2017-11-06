<?php class ApdInteract_Gefinance_Model_System_Config_Source_Geterm 
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 6, 'label' => Mage::helper('adminhtml')->__('6 months with no monthly repayments')),
            array('value' => 12, 'label' => Mage::helper('adminhtml')->__('12 months with minimum monthly repayments')),
            array('value' => 18, 'label' => Mage::helper('adminhtml')->__('18 months with minimum monthly repayments')),
            array('value' => 24, 'label' => Mage::helper('adminhtml')->__('24 months with minimum monthly repayments')),
            array('value' => 36, 'label' => Mage::helper('adminhtml')->__('36 months with equal monthly repayments')),
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
            6 => Mage::helper('adminhtml')->__('6 months with no monthly repayments'),
            12 => Mage::helper('adminhtml')->__('12 months with minimum monthly repayments'),
            18 => Mage::helper('adminhtml')->__('18 months with minimum monthly repayments'),
            24 => Mage::helper('adminhtml')->__('24 months with minimum monthly repayments'),
            36 => Mage::helper('adminhtml')->__('36 months with equal monthly repayments'),
        );
    }
}