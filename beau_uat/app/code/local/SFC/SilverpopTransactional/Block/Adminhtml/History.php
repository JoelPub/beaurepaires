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

class SFC_SilverpopTransactional_Block_Adminhtml_History extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		parent::__construct();
		$this->_controller = 'adminhtml_manage';
		$this->_blockGroup = 'silverpoptransactional';
		$this->_headerText = Mage::helper('silverpoptransactional')->__('Transactional Email History');
		$this->_removeButton('add');
	}
}