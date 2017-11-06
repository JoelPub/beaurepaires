<?php
class Raveinfosys_Deleteorder_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('sales_order_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

	
	public function getstorelocation()
	{
		$collection = Mage::getModel('storelocator/stores')->getCollection();
		$array = array();
		foreach($collection as $data)
		{
			$array[$data->getId()]  = $data->getTitle();
		}
		return $array;
	}
    /**
     * Retrieve collection class
     *
     * @return string
     */
    protected function _getCollectionClass()
    {
        return 'sales/order_grid_collection';
    }
    
    protected function getAdminId(){
    	$admin_user_session = Mage::getSingleton('admin/session');
    	$adminuserId = $admin_user_session->getUser()->getUserId();
    	return $adminuserId;
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
		$collection->getSelect()->join('sales_flat_order as a', 'main_table.entity_id=a.entity_id', array('storelocation'),null,'left')
                                        ->joinLeft(array('payment'=>'sales_payment_transaction'),'main_table.entity_id=order_id AND txn_type="capture"','txn_id');
//		$collection->getSelect()->join('iwd_storelocator_users as b', 'a.storelocation=b.store_id', array('*'),null,'left')
//								->where("b.account_id='".$this->getAdminId()."'");
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('real_order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'increment_id',
            'filter_index' => 'main_table.increment_id'
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('sales')->__('Purchased From (Store)'),
                'index'     => 'main_table.store_id',                
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
            ));
        }

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',         
            'filter_index' => 'main_table.created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));
/*
        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Bill to Name'),
            'index' => 'billing_name',
        ));

        $this->addColumn('shipping_name', array(
            'header' => Mage::helper('sales')->__('Ship to Name'),
            'index' => 'shipping_name',
        ));

        $this->addColumn('base_grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Base)'),
            'index' => 'base_grand_total',
            'type'  => 'currency',
            'currency' => 'base_currency_code',
        ));
*/

        $this->addColumn('billing_name', array(
            'header' => Mage::helper('sales')->__('Customer Name'),
            'index' => 'billing_name',
            'filter_index' => 'main_table.billing_name',
        ));
		
        $this->addColumn('grand_total', array(
            'header' => Mage::helper('sales')->__('G.T. (Purchased)'),
            'index' => 'grand_total',
            'filter_index' => 'main_table.grand_total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));

        $this->addColumn('storelocation', array(
            'header' => Mage::helper('sales')->__('Store Location'),
            'index' => 'storelocation',
            'filter_index' => 'a.storelocation',
            'type'  => 'options',
            'width' => '70px',
            'options' => $this->getstorelocation(),
        ));
        
        $this->addColumn('txn_id', array(
            'header' => Mage::helper('sales')->__('Payment Reference#'),
            'index' => 'txn_id',
            'filter_index' => 'payment.txn_id',
            'width' => '70px'
        ));
		
		
		
        $this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'filter_index' => 'main_table.status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            $this->addColumn('action',
                array(
                    'header'    => Mage::helper('sales')->__('Action'),
                    'width'     => '50px',
                    'type'      => 'action',
                    'getter'     => 'getId',
// 					'renderer'  => 'deleteorder/adminhtml_sales_order_render_delete',
//					Commented out the delete Order for now since there is no requirement for that 7/20/15
//					to enable it comment out the 'actions' field and uncomment the 'renderer field'
                	'actions'   => array(
                				array(
                						'caption' => Mage::helper('sales')->__('View'),
                						'url'     => array('base'=>'*/sales_order/view'),
                						'field'   => 'order_id',
                						'data-column' => 'action',
                				)
                	),
                    'filter'    => false,
                    'sortable'  => false,
                    'index'     => 'stores',
                    'is_system' => true,
            ));
        }
        $this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('order_ids');
        $this->getMassactionBlock()->setUseSelectAll(false);

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/cancel')) {
            $this->getMassactionBlock()->addItem('cancel_order', array(
                 'label'=> Mage::helper('sales')->__('Cancel'),
                 'url'  => $this->getUrl('*/sales_order/massCancel'),
            ));
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/hold')) {
            $this->getMassactionBlock()->addItem('hold_order', array(
                 'label'=> Mage::helper('sales')->__('Hold'),
                 'url'  => $this->getUrl('*/sales_order/massHold'),
            ));
        }

        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/unhold')) {
            $this->getMassactionBlock()->addItem('unhold_order', array(
                 'label'=> Mage::helper('sales')->__('Unhold'),
                 'url'  => $this->getUrl('*/sales_order/massUnhold'),
            ));
        }

        $this->getMassactionBlock()->addItem('pdfinvoices_order', array(
             'label'=> Mage::helper('sales')->__('Print Invoices'),
             'url'  => $this->getUrl('*/sales_order/pdfinvoices'),
        ));

        $this->getMassactionBlock()->addItem('pdfshipments_order', array(
             'label'=> Mage::helper('sales')->__('Print Packingslips'),
             'url'  => $this->getUrl('*/sales_order/pdfshipments'),
        ));

        $this->getMassactionBlock()->addItem('pdfcreditmemos_order', array(
             'label'=> Mage::helper('sales')->__('Print Credit Memos'),
             'url'  => $this->getUrl('*/sales_order/pdfcreditmemos'),
        ));

        $this->getMassactionBlock()->addItem('pdfdocs_order', array(
             'label'=> Mage::helper('sales')->__('Print All'),
             'url'  => $this->getUrl('*/sales_order/pdfdocs'),
        ));

        $this->getMassactionBlock()->addItem('print_shipping_label', array(
             'label'=> Mage::helper('sales')->__('Print Shipping Labels'),
             'url'  => $this->getUrl('*/sales_order_shipment/massPrintShippingLabel'),
        ));
//		Commented out to remove the Delete Order in the Actions tab 7/20/15, no requirement yet for that	
//         $this->getMassactionBlock()->addItem('delete_order', array(
//              'label'=> Mage::helper('sales')->__('Delete Order'),
//              'url'  => $this->getUrl('deleteorder/adminhtml_deleteorder/massDelete'),
// 			 'confirm'  => Mage::helper('sales')->__('Are you sure you want to delete order?')
//         ));		

        return $this;
    }
    public function getRowUrl($row)
    {
        if (Mage::getSingleton('admin/session')->isAllowed('sales/order/actions/view')) {
            return $this->getUrl('*/sales_order/view', array('order_id' => $row->getId()));
        }
        return false;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

}
?>
