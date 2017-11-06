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

class SFC_SilverpopTransactional_Block_Adminhtml_Manage_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('silverpoptransactionalGrid');
		$this->setDefaultSort('email_id');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		$collection = Mage::getModel('silverpoptransactional/email')->getCollection();
		if($this->getRequest()->getActionName() == 'history'){
			$collection->addFieldToFilter('status', SFC_SilverpopTransactional_Model_Status::STATUS_SENT);			
		} else {
			$collection->addFieldToFilter('status', array('neq' => SFC_SilverpopTransactional_Model_Status::STATUS_SENT));
		}
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn('email_id', array(
			'header'	=> Mage::helper('silverpoptransactional')->__('ID'),
			'align'	=>'right',
			'width'	=> '50px',
			'index'	=> 'email_id',
		));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('sales')->__('Store'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
            ));
        }

		$this->addColumn('subject', array(
			'header'	=> Mage::helper('silverpoptransactional')->__('Subject'),
			'align'	=>'left',
			'index'	=> 'subject',
		));

		$this->addColumn('created_at', array(
			'header'	=> Mage::helper('silverpoptransactional')->__('Created At'),
			'align'	=>'left',
			'type'	=> 'datetime',
			'index'	=> 'created_at',
		));	
		
		$this->addColumn('sent_at', array(
			'header'	=> Mage::helper('silverpoptransactional')->__('Sent At'),
			'type'	=> 'datetime',
			'align'	=>'left',
			'index'	=> 'sent_at',
		));
		
		$this->addColumn('num_retries', array(
			'header'	=> Mage::helper('silverpoptransactional')->__('Retries'),
			'align'	=>'right',
			'width'	=> '50px',
			'index'	=> 'num_retries',
		));

		$this->addColumn('status', array(
			'header'	=> Mage::helper('silverpoptransactional')->__('Status'),
			'align'	=> 'left',
			'width'	=> '80px',
			'index'	=> 'status',
			'type'		=> 'options',
			'options'	=> SFC_SilverpopTransactional_Model_Status::getOptionArray(),
		));
			
		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

}