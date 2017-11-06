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

class SFC_SilverpopTransactional_Block_Adminhtml_System_Config_Form_Field_Schedule extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
	{
		return array(
			array('value' => "", 'label' => 'Off'),
			array('value' => "*/5 * * * *", 'label' => 'Every 5 Minutes'),
			array('value' => "*/10 * * * *", 'label' => 'Every 10 Minutes'),
			array('value' => "*/15 * * * *", 'label' => 'Every 15 Minutes'),
			array('value' => "*/20 * * * *", 'label' => 'Every 20 Minutes'),
			array('value' => "*/30 * * * *", 'label' => 'Every 30 Minutes'),
			array('value' => "0 * * * *", 'label' => 'Hourly'),
		);
	}
}