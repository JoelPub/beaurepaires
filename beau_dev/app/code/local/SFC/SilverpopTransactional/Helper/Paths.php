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

class SFC_SilverpopTransactional_Helper_Paths extends Mage_Core_Helper_Abstract
{
	/**
	 * Constants
	 */
	const SILVERPOP_LOG_FILE    =   'silverpop.log';


	/**
	 * Generic logger method
	 */
	static public function log($message)
	{
		Mage::log($message, Zend_Log::INFO, SFC_SilverpopTransactional_Helper_Paths::SILVERPOP_LOG_FILE);
	}

	/**
	 * Get data path
	 * @return string or nil
	 */
	public function getDataPath()
	{
		try {

			// Io
			$oIo = new Varien_Io_File();

			// Get path
			$sPath = $oIo->getCleanPath(Mage::getConfig()->getVarDir() . DS . Mage::getStoreConfig('silverpop/paths/data'));

			// Exist?
			$oIo->checkAndCreateFolder($sPath);

			return $sPath;
		}
		catch (Exception $e) {
			Mage::logException($e);
			Mage::log($e->getMessage(), Zend_Log::ERR, self::SILVERPOP_LOG_FILE);
		}

		return nil;
	}

	/**
	 * Get export path
     * @param string Export key
	 * @return string or nil
	 */
	public function getExportKeyPath($sExportKey)
	{
		// Get data directory
		$sDataPath = $this->getDataPath();
		if (!$sDataPath) {
			return nil;
		}

		try {

			// Io
			$oIo = new Varien_Io_File();

			// Get path
			$sPath = $oIo->getCleanPath($sDataPath . $sExportKey . DS);

			// Exist?
			$oIo->checkAndCreateFolder($sPath);

			return $sPath;
		}
		catch (Exception $e) {
			Mage::logException($e);
			Mage::log($e->getMessage(), Zend_Log::ERR, self::SILVERPOP_LOG_FILE);
		}

		return nil;
	}

    /**
     * Remove export keypath
     * @param Path
     * @return boolean
     */
    public function removeExportKeyPath($sExportKey)
    {
        try {

            // Make path
            $sPath = $this->getExportKeyPath($sExportKey);

            // Remove
            $this->removePath($sPath);

            return true;
        }
        catch (Exception $e) {
            Mage::logException($e);
            Mage::log($e->getMessage(), Zend_Log::ERR, self::SILVERPOP_LOG_FILE);
        }

        return false;
    }

	/**
	 * Remove path
	 * @param Path
	 * @return boolean
	 */
	public function removePath($sPath)
	{
		try {

            // Log
            Mage::log("Removing path: $sPath", Zend_Log::INFO, self::SILVERPOP_LOG_FILE);

			// Remove
			$oIo = new Varien_Io_File();
			$sPath = $oIo->getCleanPath($sPath);
			if (!$oIo->rmdirRecursive($sPath)) {
                Mage::throwException('Unable to remove directory at: ' . $sPath);
			}

			return true;
		}
		catch (Exception $e) {
			Mage::logException($e);
			Mage::log($e->getMessage(), Zend_Log::ERR, self::SILVERPOP_LOG_FILE);
		}

		return false;
	}
}