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

class SFC_SilverpopTransactional_Model_Logs extends Mage_Core_Model_Abstract
{
    /**
     * Log statuses
     */
    const LOGS_STATUS_INFO              =           1;
    const LOGS_STATUS_ERROR             =           2;
    const LOGS_STATUS_WARNING           =           3;
    const LOGS_STATUS_SUCCESS           =           4;

	/**
	 * Constructor
	 */
	public function _construct()
	{
		parent::_construct();
		$this->_init('silverpoptransactional/logs');
	}
	
    /**
     * Get status name
     * @static
     * @param int Status
     * @return string
     */
    static public function getStatusName($iStatus)
    {
        // What?
        switch ($iStatus) {

            // -- Running
            case self::LOGS_STATUS_INFO:
                return Mage::helper('silverpoptransactional')->__('Info');
            // -- Error
            case self::LOGS_STATUS_ERROR:
                return Mage::helper('silverpoptransactional')->__('Error');
            // -- Warning
            case self::LOGS_STATUS_WARNING:
                return Mage::helper('silverpoptransactional')->__('Warning');
            // -- Success
            case self::LOGS_STATUS_SUCCESS:
                return Mage::helper('silverpoptransactional')->__('Success');
            default:
                return '';
        }
    }
    
}