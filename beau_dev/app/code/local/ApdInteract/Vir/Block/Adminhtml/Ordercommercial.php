<?php
class ApdInteract_Vir_Block_Adminhtml_Ordercommercial
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected function _construct()
    {
        parent::_construct();

        /**
         * The $_blockGroup property tells Magento which alias to use to
         * locate the blocks to be displayed in this grid container.
         * In our example, this corresponds to Vir/Block/Adminhtml.
         */
        $this->_blockGroup = 'apdinteract_vir_adminhtml';

        /**
         * $_controller is a slightly confusing name for this property.
         * This value, in fact, refers to the folder containing our
         * Grid.php and Edit.php - in our example,
         * Vir/Block/Adminhtml/Ordercommercial. So, we'll use 'ordercommercial'.
         */
        $this->_controller = 'ordercommercial';

        /**
         * The title of the page in the admin panel.
         */
        $this->_headerText = Mage::helper('apdinteract_vir')
            ->__('Commercial - Vehicle Inspection Report (VIR In-Store)');
    }

    public function getCreateUrl()
    {
        /**
         * When the "Add" button is clicked, this is where the user should
         * be redirected to - in our example, the method editAction of
         * BrandController.php in BrandDirectory module.
         */
        return $this->getUrl(
            'apdinteract_vir_admin/ordercommercial/edit'
        );
    }
}
