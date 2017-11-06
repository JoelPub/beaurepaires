<?php
class ApdInteract_Vir_Model_Orderstoremapping
    extends Mage_Core_Model_Abstract
{
    const VISIBILITY_HIDDEN = '0';
    const VISIBILITY_DIRECTORY = '1';

    protected function _construct()
    {
        /**
         * This tells Magento where the related resource model can be found.
         *
         * For a resource model, Magento will use the standard model alias -
         * in this case 'apdinteract_vir' - and look in
         * config.xml for a child node <resourceModel/>. This will be the
         * location that Magento will look for a model when
         * Mage::getResourceModel() is called - in our case,
         * ApdInteract_Vir_Model_Resource.
         */
        $this->_init('apdinteract_vir/orderstoremapping');
    }

    /**
     * This method is used in the grid and form for populating the dropdown.
     */
    public function getAvailableVisibilies()
    {
        return array(
            self::VISIBILITY_HIDDEN
                => Mage::helper('apdinteract_vir')
                       ->__('Hidden'),
            self::VISIBILITY_DIRECTORY
                => Mage::helper('apdinteract_vir')
                       ->__('Visible in Directory'),
        );
    }

    protected function _beforeSave()
    {
        parent::_beforeSave();

        /**
         * Perform some actions just before a orderstoremapping is saved.
         */
        //$this->_updateTimestamps();
        //$this->_prepareUrlKey();

        return $this;
    }    

    
}

