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


class SFC_Silverpoptransactional_Adminhtml_ManageController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('silverpop/manage')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {	
		SFC_SilverpopTransactional_Helper_Paths::log("ManageController::indexAction()");
		$this->_initAction()
			->renderLayout();
	}

	public function historyAction() {
		$this->_initAction()
			->renderLayout();
	}
		
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('silverpoptransactional/email')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('silverpoptransactional_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('silverpoptransactional/manage');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('silverpoptransactional/adminhtml_manage_edit'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('silverpoptransactional')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}	
	
	public function sendAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('silverpoptransactional/email')->load($id);	
		
		if ($model->getId() || $id == 0) {
			try{
				$model->sendTransactional();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('silverpoptransactional')->__('Sent successfully.'));
			} Catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::helper('silverpoptransactional')->logEntry($e->getMessage(), $model->getId(), SFC_SilverpopTransactional_Model_Logs::LOGS_STATUS_ERROR);
				$model
					->setStatus(SFC_SilverpopTransactional_Model_Status::STATUS_ERROR)
					->setNumRetries($model->getNumRetries() + 1)
					->save();
				SFC_SilverpopTransactional_Helper_Paths::log($e->getMessage());
				SFC_SilverpopTransactional_Helper_Paths::log($e->getTraceAsString());
			}
			$this->_redirect('*/*/edit', array('id' => $id));
			return;
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('silverpoptransactional')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
	
	public function processAction() {
		try {
		
			$session = Mage::getSingleton('adminhtml/session');
			$collection = Mage::getModel("silverpoptransactional/email")->getCollection()
	    		->addFieldToFilter('status', array('neq' => SFC_SilverpopTransactional_Model_Status::STATUS_SENT));
	    	$collection->setDataToAll('num_retries', 0)->save();	    	
			$session->setData('silverpoptransactional_process_count', $collection->count());				
					
		    $this->getResponse()->setBody($this->getLayout()->createBlock('silverpoptransactional/adminhtml_run')->toHtml());
	        $this->getResponse()->sendResponse();
	        
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			$this->_redirect('*/*/');
		}
	}
	
	public function runAction() {
		$collection = Mage::getModel("silverpoptransactional/email")->getCollection()
    		->addFieldToFilter('status', array('neq' => SFC_SilverpopTransactional_Model_Status::STATUS_SENT))
    		->addFieldToFilter('num_retries', array('lt' => Mage::getStoreConfig('silverpop/smtp/max_retries')));
    	if($collection->count() == 0){
	    	echo 'done';
	    	return;
    	}
		try{
			$model = $collection->getFirstItem();
			$model->sendTransactional();
			echo "1";
		} Catch (Exception $e) {
			echo Mage::helper('silverpoptransactional')->__('Error sending email %s: ', $model->getId()) . $e->getMessage();
			Mage::helper('silverpoptransactional')->logEntry($e->getMessage(), $model->getId(), SFC_SilverpopTransactional_Model_Logs::LOGS_STATUS_ERROR);
			$model
				->setStatus(SFC_SilverpopTransactional_Model_Status::STATUS_ERROR)
				->setNumRetries($model->getNumRetries() + 1)
				->save();
			SFC_SilverpopTransactional_Helper_Paths::log($e->getMessage());
			SFC_SilverpopTransactional_Helper_Paths::log($e->getTraceAsString());
		}
	}
	
	
}