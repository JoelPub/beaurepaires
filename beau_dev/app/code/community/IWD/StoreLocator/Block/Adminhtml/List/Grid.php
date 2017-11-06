<?php
class IWD_StoreLocator_Block_Adminhtml_List_Grid extends Mage_Adminhtml_Block_Widget_Grid{
	
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'grid' );
		$this->setDefaultSort ( 'id' );
		$this->setDefaultDir ( 'desc' );
		$this->setSaveParametersInSession ( true );
	}
	
	public function getadmin()
	{
		$admin_user_session = Mage::getSingleton('admin/session');
		$adminuserId = $admin_user_session->getUser()->getUserId();
		$role_data = Mage::getModel('admin/user')->load($adminuserId)->getRole()->getRoleId();
		return $role_data;
	}	
	public function getadminid()
	{
				$admin_user_session = Mage::getSingleton('admin/session');
				$adminuserId = $admin_user_session->getUser()->getUserId();
				return $adminuserId;
	}
	
	protected function _prepareCollection(){
            

		//GET IF ADMIN IS MAIN ADMINISTRATOR
                $isAllowed = Mage::helper('storelocator')->isAllowed();
		if($isAllowed)
		{
			$collection = Mage::getModel('storelocator/stores')->getCollection();
			$this->setCollection($collection);
		}
		else
		{
			$collection = Mage::getModel('storelocator/stores')->getCollection();
			$collection->getSelect()->join('iwd_storelocator_users', 'store_id=entity_id', array('*'),null,'left')
			->where("account_id='".$this->getadminid()."'");
			#exit($collection->getSelect());
			$this->setCollection($collection);

		}
                

		
		return parent::_prepareCollection();
	}

	
	protected function _prepareColumns() {
		$this->addColumn ( 'id',
				array (
						'header' => Mage::helper ( 'storelocator' )->__ ( 'ID' ),
						'align' => 'left', 
						'index' => 'entity_id'
				)
		);
		
		$this->addColumn ( 'title',
				array (
						'header' => Mage::helper ( 'storelocator' )->__ ( 'Title' ),
						'align' => 'left', 
						'index' => 'title'
				)
		);
		
		
		$this->addColumn ( 'country_id',
				array (
						'header' => Mage::helper ( 'storelocator' )->__ ( 'Country' ),
						'align' => 'left', 
						'index' => 'country_id',
						'renderer'  =>  'IWD_StoreLocator_Block_Adminhtml_List_Render_Country',
						'filter'=>false
				)
		);
		
		
		$this->addColumn ( 'region',
				array (
						'header' => Mage::helper ( 'storelocator' )->__ ( 'State' ),
						'align' => 'left', 
						'index' => 'region',
						'renderer'  =>  'IWD_StoreLocator_Block_Adminhtml_List_Render_Region',
						'filter'=>false
				)
		);
		 
		
		$this->addColumn ( 'city',
				array (
						'header' => Mage::helper ( 'storelocator' )->__ ( 'City' ),
						'align' => 'left',
						'index' => 'city'
				)
		);
		
		$this->addColumn ( 'latitude',
		    array (
		        'header' => Mage::helper ( 'storelocator' )->__ ( 'Latitude' ),
		        'align' => 'left',
		        'index' => 'latitude'
		    )
		);
		
		$this->addColumn ( 'longitude',
		    array (
		        'header' => Mage::helper ( 'storelocator' )->__ ( 'Longitude' ),
		        'align' => 'left',
		        'index' => 'longitude'
		    )
		);
		
				

	
	
		$this->addColumn ( 'is_active',
				array (
						'header' => Mage::helper ( 'storelocator' )->__ ( 'Status' ),
						'align' => 'center',
						'width' => '100',
						'index' => 'is_active',
						'renderer'  =>  'IWD_StoreLocator_Block_Adminhtml_List_Render_Status',
						'type'      => 'options',
						'options'   => array(0 => $this->__('Disabled'), 1 => $this->__('Enabled')),
				)
		);
	
		
	
	 	if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('cms')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }
	
	
        $this->addExportType('*/*/exportCsv', Mage::helper('storelocator')->__('CSV'));
        
	
		return parent::_prepareColumns ();
	}
	
	/**
	 * Row click url
	 *
	 * @return string
	 */
	public function getRowUrl($row){
		return $this->getUrl('*/*/edit', array('store_id' => $row->getId()));
	}
	
	protected function _afterLoadCollection(){
		$this->getCollection()->walk('afterLoad');
		parent::_afterLoadCollection();
	}
	
	protected function _filterStoreCondition($collection, $column){
		if (!$value = $column->getFilter()->getValue()) {
			return;
		}
	
		$this->getCollection()->addStoreFilter($value);
	}
	
	
}