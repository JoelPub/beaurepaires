<?php
class ApdInteract_Vir_Block_Adminhtml_Report_Consumer
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected function _construct()
    {

        $this->_headerText = Mage::helper('apdinteract_vir')->__('Consumer Vehicle Inspection Report');
        $this->_controller = 'report_consumer';

        parent::_construct();

        $this->_blockGroup = 'apdinteract_vir_adminhtml';
        $this->setTemplate('report/grid/container.phtml');
        $this->addButton('filter_form_submit', array(
            'label'     => Mage::helper('reports')->__('Show Report'),
            'onclick'   => 'filterFormSubmit()'
        ));

    }
    
    /**
     * Remove Add new Button
     */
    protected function _prepareLayout()
    {
        $this->_removeButton('add');
        return parent::_prepareLayout();
    }

    /**
     *
     * @return string
     */
    public function getFilterUrl()
    {
        $this->getRequest()->setParam('filter', null);
        return $this->getUrl('*/*/*', array('_current' => true));
    }
}