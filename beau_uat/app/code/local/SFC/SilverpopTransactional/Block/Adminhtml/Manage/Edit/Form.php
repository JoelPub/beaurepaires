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


class SFC_SilverpopTransactional_Block_Adminhtml_Manage_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form(array(
				'id' => 'edit_form',
				'enctype' => 'multipart/form-data'
			)
		);
		
		$this->setForm($form);
		$fieldset = $form->addFieldset('silverpoptransactional_form', array('legend'=>Mage::helper('silverpoptransactional')->__('Email information')));
			
		if ( Mage::registry('silverpoptransactional_data') ) {
				$emailData = Mage::registry('silverpoptransactional_data')->getData();
		} else {
			Mage::throwException(Mage::helper('silverpoptransactional')->__("Email item not found."));
		}
	
		$fieldset->addField('subject', 'text', array(
			'label'	=> Mage::helper('silverpoptransactional')->__('Subject'),
			'readonly'	=> true,
			'name'		=> 'subject',
		));
		
		$fieldset->addField('sender', 'text', array(
			'label'	=> Mage::helper('silverpoptransactional')->__('Sender'),
			'readonly'	=> true,
			'name'		=> 'sender'
		));
		
		$fieldset->addField('recipients', 'textarea', array(
			'label'	=> Mage::helper('silverpoptransactional')->__('Recipients'),
			'name'		=> 'recipients',
			'style'	=> 'height:60px;',
			'readonly'	=> true,
		));
		
		$dateFormatIso = "MMM d, y h:mm:ss a";
		$fieldset->addField('created_at', 'date', array(
			'label'		=> Mage::helper('silverpoptransactional')->__('Created At'),
			'name'		=> 'created_at',
			'format'	=> $dateFormatIso,
			'style'		=> 'width:160px;',
			'readonly'	=> true,
		));
 
		$fieldset->addField('sent_at', 'date', array(
			'label'		=> Mage::helper('silverpoptransactional')->__('Sent At'),
			'name'		=> 'sent_at',
			'format'	=> $dateFormatIso,
			'style'		=> 'width:160px;',
			'readonly'	=> true,
		));
 
	
		$fieldset->addField('content', 'textarea', array(
			'name'		=> 'content',
			'label'		=> Mage::helper('silverpoptransactional')->__('Content'),
			'title'		=> Mage::helper('silverpoptransactional')->__('Content'),
			'style'		=> 'width:700px; height:200px;',
			'wysiwyg'	=> false,
			'readonly'	=> true,
		));
		
		$fieldset->addField('status', 'select', array(
			'label'		=> Mage::helper('silverpoptransactional')->__('Status'),
			'name'		=> 'status',
			'values'	=> SFC_SilverpopTransactional_Model_Status::getOptionArray(),
			'readonly'	=> true,
			'disabled'	=> true,
		));
		
		$emailData['sender'] = $emailData['sender_email'] . ($emailData['sender_name'] != '' ? " (".$emailData['sender_name'].")" : '');
		
		$recipient_names = unserialize($emailData['recipient_name']);		
		$recipient_emails = unserialize($emailData['recipient_email']);
		if(is_array($recipient_emails) && is_array($recipient_names)){
			$recipients = array_combine($recipient_emails, $recipient_names);
			$emailData['recipients'] = array();
			foreach($recipients as $recipient_email => $recipient_name){
				$emailData['recipients'][] = $recipient_email . ($recipient_name != '' ? " (".$recipient_name.")" : '');				
			}
			$emailData['recipients'] = implode("\n", $emailData['recipients']);
		}	
		
		if(is_array($recipient_emails)) $emailData['recipient_email'] = implode("\n", $recipient_emails);

		$fieldset = $form->addFieldset('email_log', array(
			'legend'	=>	Mage::helper('adminhtml')->__('Logs')));	
						
		$logBlock = $this->getLayout()->createBlock('silverpoptransactional/adminhtml_manage_grid_log');
				
		$fieldset->addField('log_box', 'note', array(
			'label'	=> $this->__('Logs'),
			'text'		=> '<div id="log_details">' . $logBlock->toHtml() . '</div>',
		));

		$form->setValues($emailData);
		
		return parent::_prepareForm();
	}
}
