<?php
class ApdInteract_Vir_Block_Adminhtml_Report_Commercial_Grid extends Mage_Adminhtml_Block_Widget_Grid{


    public function __construct()
    {
        parent::__construct();
        $this->setFilterVisibility(false);
       // $this->setPagerVisibility(false);

    }


    /**
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $session_data =  Mage::getSingleton('admin/session')->getReportFilter();
        $status    = isset($session_data['status']) ? $session_data['status'] : '';
        $from_date = isset($session_data['from']) ? $session_data['from'] : '';
        $to_date   = isset($session_data['to']) ? $session_data['to'] : '';

        $collection = Mage::getResourceModel('apdinteract_vir/ordercommercial_collection')
                      ->addFieldToSelect(array('Invoicenumber','customername','regonumber'));

        // Filter created date
        if($from_date != '' && $to_date != ''){
            $from_date = date('Y-m-d',strtotime($from_date));
            $to_date = date('Y-m-d',strtotime($to_date));
            $filter_date = array('from'=>$from_date,'to'=>$to_date);
            $collection->addFieldToFilter('created_at',array($filter_date));
        }elseif($from_date != '' && $to_date == ''){
            $from_date = date('Y-m-d',strtotime($from_date));
            $collection->addFieldToFilter('created_at',array('gteq' => $from_date));
        }elseif($from_date == '' && $to_date != ''){
            $to_date = date('Y-m-d',strtotime($to_date));
            $collection->addFieldToFilter('created_at',array('lteq' => $to_date));
        }

        //Filter Status
        if($status != '' && $status != 'empty'){
            $collection->addFieldToFilter('status',array('eq'=>$status));
        }elseif($status == 'empty'){
            // show all
        }else{
            $collection->addFieldToFilter('parent_id',array('eq'=>0));;
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns()
    {

        $this->addColumn('parent_id', array(
            'header' => $this->_getHelper()->__('ID'),
            'type' => 'number',
            'index' => 'parent_id',
            'sortable'      => false,
            'filter' => false,
        ));

        $this->addColumn('Invoicenumber', array(
            'header' => $this->_getHelper()->__('Invoice Number'),
            'type' => 'number',
            'index' => 'Invoicenumber',
            'sortable'      => false,
            'filter' => false,
        ));

        $this->addColumn('customername', array(
            'header' => $this->_getHelper()->__('Customer Name'),
            'type' => 'text',
            'index' => 'customername',
            'sortable'      => false,
            'filter' => false,
        ));

        $this->addColumn('regonumber', array(
            'header' => $this->_getHelper()->__('Rego Number'),
            'type' => 'text',
            'index' => 'regonumber',
            'sortable'      => false,
            'filter' => false,
        ));

        $this->addExportType('*/*/exportReportCommercialCsv', Mage::helper('adminhtml')->__('CSV'));


        return parent::_prepareColumns();
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */
    protected function _getHelper()
    {
        return Mage::helper('apdinteract_vir');
    }
}
