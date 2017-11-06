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

class SFC_SilverpopTransactional_Helper_Images extends Mage_Core_Helper_Abstract
{
    /**
     * Log statuses
     */
    const IMAGES_LOGSTATUS_ERROR                =           'status_error.png';
    const IMAGES_LOGSTATUS_INFO                 =           'status_info.png';
    const IMAGES_LOGSTATUS_WARNING              =           'status_warning.png';
    const IMAGES_LOGSTATUS_SUCCESS              =           'status_success.png';

    /*
    * Get log status image
    * @static
    * @param int Type
    * @return string
    */
    static public function getLogStatusImage($iType)
    {
        // What?
        switch ($iType) {

            // -- Error
            case SFC_SilverpopTransactional_Model_Logs::LOGS_STATUS_ERROR:
                return self::IMAGES_LOGSTATUS_ERROR;
            // -- Info
            case SFC_SilverpopTransactional_Model_Logs::LOGS_STATUS_INFO:
                return self::IMAGES_LOGSTATUS_INFO;
            // -- Warning
            case SFC_SilverpopTransactional_Model_Logs::LOGS_STATUS_WARNING:
                return self::IMAGES_LOGSTATUS_WARNING;
            // -- Success
            case SFC_SilverpopTransactional_Model_Logs::LOGS_STATUS_SUCCESS:
                return self::IMAGES_LOGSTATUS_SUCCESS;
            default:
                return '';
        }
    }
}