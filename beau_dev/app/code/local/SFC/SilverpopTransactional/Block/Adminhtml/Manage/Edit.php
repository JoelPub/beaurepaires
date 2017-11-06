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


class SFC_SilverpopTransactional_Block_Adminhtml_Manage_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'email_id';
        $this->_blockGroup = 'silverpoptransactional';
        $this->_controller = 'adminhtml_manage';
        
        $this->_removeButton('delete');
        $this->_removeButton('reset');
	    $this->_removeButton('save');
        

        if(Mage::registry('silverpoptransactional_data') && Mage::registry('silverpoptransactional_data')->getId()) {
        	$email = Mage::registry('silverpoptransactional_data');
        	if($email->getStatus() != SFC_SilverpopTransactional_Model_Status::STATUS_SENT){
				$this->_addButton('process', array(
					'label'		 => Mage::helper('adminhtml')->__('Send'),
					'onclick'	 => 'setLocation(\''.$this->getUrl('*/*/send', array('id' => Mage::registry('silverpoptransactional_data')->getId())).'\')',
					'class'		 => 'save',
				), -100);
	        }
	    }
    }

    public function getHeaderText()
    {
        if( Mage::registry('silverpoptransactional_data') && Mage::registry('silverpoptransactional_data')->getId() ) {
            return Mage::helper('silverpoptransactional')->__("Viewing Email %s", Mage::registry('silverpoptransactional_data')->getId());
        }
    }
}