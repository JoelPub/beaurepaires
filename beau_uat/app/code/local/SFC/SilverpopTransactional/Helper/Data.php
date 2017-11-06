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

class SFC_SilverpopTransactional_Helper_Data extends Mage_Core_Helper_Abstract
{

	/**
	 * Error emails
	 * @return boolean
	 */
	public function getErrorEmails()
	{
		// Emails
		$sEmails = Mage::getStoreConfig('silverpop/errors/emails');
		$sEmails = ($sEmails ? str_replace("\r", '', trim($sEmails)) : '');
		return explode("\n", $sEmails);
	}

	/**
	 * Validate configuration
	 * @return boolean
	 */
	public function validateConfig($store)
	{
		if(Mage::getStoreConfig('silverpop/smtp/enabled', $store) == false){
			return false;
		}

		// Lets get required values
		$aValues = array();
		$aValues[] = Mage::getStoreConfig('silverpop/smtp/server', $store);

		// Validate
		foreach ($aValues as $sValue)
		{
			if (!$sValue || strlen(trim($sValue)) == 0) {
				return false;
			}
		}

		return true;
    }

    /**
     * Get all store Id's
     * @return array
     */
    public function getStoreIds()
    {
        $aStores = Mage::app()->getStores();
        $aRet = array();
        foreach ($aStores as $oStore)
        {
            $aRet[] = $oStore->getId();
        }
        return $aRet;
    }
    
	/**
	 * Add log entry to database
	 */
	public function logEntry($message, $emailId, $status = SFC_SilverpopTransactional_Model_Logs::LOGS_STATUS_INFO)
    {
    	SFC_SilverpopTransactional_Helper_Paths::log(Mage::helper('silverpoptransactional')->__('Adding log entry to database.'));
    	$entry = Mage::getModel('silverpoptransactional/logs');
    	$entry->setData(array(
    		'email_id'		=> $emailId,
    		'log_status'	=> $status,
    		'message'		=> $message,
    		'created_at'	=> now()
    	))->save();
    }
    
    public function sendErrorEmail($message, $subject, $storeId = null)
    {
        if (($storeId === null) && $this->getDesignConfig()->getStore()) {
            $storeId = $this->getDesignConfig()->getStore();
        }
            
    	$recipients = explode("\n", Mage::getStoreConfig('silverpop/errors/transactemails', $storeId));
    	if(count($recipients) == 0) return;
    	
	    $mail = new Zend_Mail('utf-8');
		$mail->setFrom(Mage::getStoreConfig('trans_email/ident_general/email', $storeId), Mage::getStoreConfig('trans_email/ident_general/name', $storeId));
		foreach($recipients as $recipientEmail){
			$mail->addTo($recipientEmail, '');
		}
		$mail->setSubject($subject);
		$mail->setBodyText($message);
		$mail->send();
    }
    
    
}