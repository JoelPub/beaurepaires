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

class SFC_SilverpopTransactional_Model_Email extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('silverpoptransactional/email');
    }
    
    public function sendTransactional()
    {
    	if($this->getId() == 0){
		    Mage::throwException(Mage::helper('silverpoptransactional')->__('Cannot call sendTransactional without a valid email object.'));
	    }
	    
	    // Lookup Magento store which is tied to individual email queue item
	    $store = Mage::getModel('core/store')->load($this->getStoreId());
	    
	    // validate config for this specific store
	    if( Mage::helper('silverpoptransactional')->validateConfig($store) == false){
		    Mage::throwException(Mage::helper('silverpoptransactional')->__('Invalid Silverpop TransactSMTP configuration.'));
	    }
	    
		$server = Mage::getStoreConfig('silverpop/smtp/server', $store);
		
		$recipient_names = unserialize($this->getData('recipient_name'));
		$recipient_emails = unserialize($this->getData('recipient_email'));
		foreach($recipient_emails as $recipientIndex => $recipientEmail){
			try{
				$transport = new Zend_Mail_Transport_Smtp($server);
				
				$mail = new Zend_Mail();
				$mail->setBodyHtml($this->getContent());
				$mail->setFrom($this->getSenderEmail(), $this->getSenderName());
				
				$recipientName = isset($recipient_names[$recipientIndex]) ? $recipient_names[$recipientIndex] : '';
				$mail->addTo($recipientEmail, $recipientName);
				
				$mail->setSubject($this->getSubject());
				if(Mage::getStoreConfig('silverpop/smtp/x_header', $store) != ''){
					$mail->addHeader('X-SP-Transact-Id', Mage::getStoreConfig('silverpop/smtp/x_header', $store));
				}
				$mail->setReturnPath(Mage::getStoreConfig('silverpop/errors/transactemails', $store));
				$mail->send($transport);	
			} catch (Zend_Mail_Transport_Exception $e) {
				Mage::throwException(Mage::helper('silverpoptransactional')->__('Mail send error %s', $e->getMessage()));
			}
		}		
		
		$this->setSentAt(now())->setStatus(SFC_SilverpopTransactional_Model_Status::STATUS_SENT)->save();
		Mage::helper('silverpoptransactional')->logEntry(Mage::helper('silverpoptransactional')->__('Sent Successfully'), $this->getId(), SFC_SilverpopTransactional_Model_Logs::LOGS_STATUS_SUCCESS);
		SFC_SilverpopTransactional_Helper_Paths::log(Mage::helper('silverpoptransactional')->__('Email %s sent successfully.', $this->getId()));
	    
    }
    
}