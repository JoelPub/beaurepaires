<?php

class ApdInteract_Costar_Block_Adminhtml_Rejected_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();
        $this->setId('apd_rejected_grid');        
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    /**    
    * Will filter according to type param set on the url
    *   
    */
    protected function _prepareCollection() {
                
        $collection = Mage::getResourceModel('sales/order_grid_collection');
        $collection->addFieldToFilter('status',array('eq' => 'costar_rejected'));
        $this->setCollection($collection);
        return parent::_prepareCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns() {
        
       
        $this->addColumn('real_order_id', array(
            'header'=> Mage::helper('costar')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('costar')->__('Purchased From (Store)'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
            ));
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('costar')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('costar')->__('Bill to Name'),
            'index' => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('costar')->__('Ship to Name'),
            'index' => 'shipping_name',
        ));

        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('costar')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));

        $this->addColumn('grand_total', array(
            'header' => Mage::helper('costar')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));

        
        $this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
       
    }
    
    public function getRowUrl($order)
    {
     return $this->getUrl(
        'adminhtml/sales_order/view',
        array(
            'order_id' => $order->getId()
        )
    );
    }
    
    
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);
        
        $this->getMassactionBlock()->addItem('order_resend', array(
             'label'=> Mage::helper('costar')->__('Resend To Costar'),
             'url'  => $this->getUrl('*/adminhtml_costarbackend/massresend'),
        ));
        
    }

}
