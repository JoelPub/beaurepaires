<?php
/**
 * StoreFront Silverpop Transaction Email Magento Extension
 * NOTICE OF LICENSE
 *
 * This source file is subject to commercial source code license 
 * of StoreFront Consulting, Inc.
 *
 * @category	SFC
 * @package    	SFC_SilverpopTransactional
 * @website 	http://www.storefrontconsulting.com/
 * @copyright 	Copyright (C) 2009-2013 StoreFront Consulting, Inc. All Rights Reserved.
 */

class SFC_SilverpopTransactional_Block_Adminhtml_Manage_Grid_Log extends Mage_Adminhtml_Block_Widget_Grid
{

	public function __construct()
	{
		parent::__construct();
		$this->setId('item_logs');
		$this->setUseAjax(true);
	}

	protected function _prepareCollection()
	{	
		$emailId = $this->getRequest()->getParam('id');

		// Get the collection
		$collection = Mage::getModel('silverpoptransactional/logs')->getCollection();
		$collection->addFieldToFilter('email_id', $emailId);
		$this->setCollection($collection);
				
		return parent::_prepareCollection();
	}
	
	protected function _prepareColumns()
	{

		$this->addColumn('created_at', array(
			'header' => $this->__('Time'),
			'align' => 'left',
			'type' => 'datetime',
			'index' => 'created_at',
			'sortable' => false
		));
		$this->addColumn('log_status', array(
			'header' => $this->__('Status'),
			'align' => 'left',
			'type' => 'image',
			'index' => 'log_status',
			'sortable' => false,
			'renderer' => 'SFC_SilverpopTransactional_Block_Widget_Grid_Column_Renderer_Logstatus'
		));
		$this->addColumn('message', array(
			'header' => $this->__('Message'),
			'align' => 'left',
			'type' => 'text',
			'index' => 'message',
			'sortable' => false,
		));
		
		$this->setFilterVisibility(false);		
		$this->setPagerVisibility(false);
		
        return parent::_prepareColumns();
	}

    public function getGridUrl()
    {
        return '';
    }

}