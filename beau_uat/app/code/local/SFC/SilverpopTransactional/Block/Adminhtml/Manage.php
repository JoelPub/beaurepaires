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

class SFC_SilverpopTransactional_Block_Adminhtml_Manage extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_controller = 'adminhtml_manage';
		$this->_blockGroup = 'silverpoptransactional';
		$this->_headerText = Mage::helper('silverpoptransactional')->__('Transactional Email Queue');
		$this->_removeButton('add');
		
		$this->_addButton('process', array(
			'label'		 => Mage::helper('adminhtml')->__('Process Unsent'),
			'onclick'	 => 'window.open(\''.$this->getUrl('*/*/process', array('_current' => true)).'\')',
			'class'		 => 'save',
		), -100);	
	}
}