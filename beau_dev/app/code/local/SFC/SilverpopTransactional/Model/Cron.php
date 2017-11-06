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

class SFC_SilverpopTransactional_Model_Cron
{

	protected function getPageSize()
	{
		return Mage::getStoreConfig('silverpop/smtp/pagesize');	
	}

    /**
     * Send emails
     */
    public function processEmail()
    {
        // Logging
        SFC_SilverpopTransactional_Helper_Paths::log(Mage::helper('silverpoptransactional')->__('Starting email cron job.'));
        echo Mage::helper('silverpoptransactional')->__('Starting email cron job.')."\n";
        
       	$model = Mage::getModel("silverpoptransactional/email");        	
		$collection = $model->getCollection()
	    	->addFieldToFilter('status', array('neq' => SFC_SilverpopTransactional_Model_Status::STATUS_SENT))
    		->addFieldToFilter('num_retries', array('lt' => Mage::getStoreConfig('silverpop/smtp/max_retries')))
    		->setPageSize($this->getPageSize());
    	$collection->getSelect()->forUpdate();
    		
    	SFC_SilverpopTransactional_Helper_Paths::log($collection->getSelect()->__toString());

    	$errorsText = array();
		foreach($collection as $model){
			try{
                                $uns_email = unserialize($model->getRecipientEmail());
                                
                                
				$model->sendTransactional();
				SFC_SilverpopTransactional_Helper_Paths::log(Mage::helper('silverpoptransactional')->__('Sending email: %s', $model->getSubject()));
				echo Mage::helper('silverpoptransactional')->__('Sending email: %s', $model->getSubject()), $model->getId();
			} Catch (Exception $e) {
				echo Mage::helper('silverpoptransactional')->__('Error sending email %s: ', $model->getId()) . $e->getMessage()." - ".$uns_email[0], $model->getId()." \n";
				Mage::helper('silverpoptransactional')->logEntry($e->getMessage(), $model->getId(), SFC_SilverpopTransactional_Model_Logs::LOGS_STATUS_ERROR);
				if(!isset($errorsText[$model->getStoreId()])){
					$errorsText[$model->getStoreId()] = '';
				}
				if(strlen($errorsText[$model->getStoreId()]) < 500) {
					$errorsText[$model->getStoreId()] .= Mage::helper('silverpoptransactional')->__('Error sending email %s: ', $model->getId()).$e->getMessage()." - ".$uns_email[0]." \n";
				}
				
				$model
					->setStatus(SFC_SilverpopTransactional_Model_Status::STATUS_ERROR)
					->setNumRetries($model->getNumRetries() + 1)
					->save();
				SFC_SilverpopTransactional_Helper_Paths::log($e->getMessage());
				SFC_SilverpopTransactional_Helper_Paths::log($e->getTraceAsString());						
				
			}		
		}
		
		if(count($errorsText) > 0){
			foreach($errorsText as $storeId => $errorMessage){
				if($errorMessage != ''){                                        
					Mage::helper('silverpoptransactional')->sendErrorEmail($errorMessage, Mage::helper('silverpoptransactional')->__('Silverpop Transactional Email Send Failure.'), $storeId);					
				}
			}
		}
    
                  
    	// Logging
    	SFC_SilverpopTransactional_Helper_Paths::log(Mage::helper('silverpoptransactional')->__('Ending email cron job.'));
    	echo Mage::helper('silverpoptransactional')->__('Ending email cron job.')."\n";
    	
    }

}