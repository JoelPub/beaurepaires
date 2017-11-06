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

class SFC_SilverpopTransactional_Model_System_Config_Backend_Cron extends Mage_Core_Model_Config_Data
{
    const CRON_STRING_PATH  = 'crontab/jobs/silverpoptransactional_process/schedule/cron_expr';
    const CRON_MODEL_PATH   = 'crontab/jobs/silverpoptransactional_process/run/model';
 
    /**
     * Cron settings after save
     *
     * @return Mage_Adminhtml_Model_System_Config_Backend_Log_Cron
     */
    protected function _afterSave()
    { 
        $cronExprString = $this->getData('groups/smtp/fields/schedule/value');
 
        try {
            Mage::getModel('core/config_data')
                ->load(self::CRON_STRING_PATH, 'path')
                ->setValue($cronExprString)
                ->setPath(self::CRON_STRING_PATH)
                ->save();
 
            Mage::getModel('core/config_data')
                ->load(self::CRON_MODEL_PATH, 'path')
                ->setValue((string) Mage::getConfig()->getNode(self::CRON_MODEL_PATH))
                ->setPath(self::CRON_MODEL_PATH)
                ->save();
        }
        catch (Exception $e) {
            Mage::throwException('Unable to save the cron expression.');
        }
    }
}