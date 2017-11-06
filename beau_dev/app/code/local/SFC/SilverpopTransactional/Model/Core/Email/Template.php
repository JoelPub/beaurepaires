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

class SFC_SilverpopTransactional_Model_Core_Email_Template extends Mage_Core_Model_Email_Template
{

	protected $isSilverpopDisabled = false;

    /**
     * Send transactional email to recipient
     *
     * @param   int $templateId
     * @param   string|array $sender sneder informatio, can be declared as part of config path
     * @param   string $email recipient email
     * @param   string $name recipient name
     * @param   array $vars varianles which can be used in template
     * @param   int|null $storeId
     * @return  Mage_Core_Model_Email_Template
     */
    public function sendTransactional($templateId, $sender, $email, $name, $vars=array(), $storeId=null)
    {
    	// get default store id
        if (($storeId === null) && $this->getDesignConfig()->getStore()) {
            $storeId = $this->getDesignConfig()->getStore();
        }

		// BFT-2246 - Includes the store email address to order confirmation emails
		$isEnable = Mage::getStoreConfig('vir/order/send_to');
		if(($isEnable) && ($templateId == 'sales_email_order_template' || $templateId == 'sales_email_order_guest_template' || $templateId == '3' || $templateId =='4')){
			//@ $templateId 3 = New Request Price - Logged In ; $templateId 4 = New Request Price - Guest

			$storeLocationId = Mage::getSingleton('core/session')->getRequestStoreId();
			if($storeLocationId){
				$id = $storeLocationId; // get store selection in PDP (price request/book a fitting)
			}else{
				$id = Mage::getSingleton("core/session")->getStorelocation(); // get store selected in checkout page
			}

			$store = Mage::getModel('storelocator/stores')->load($id);
			if ($store->getEmail()){
				$email[] = $store->getEmail(); // get store email address
				$name[] = $store->getTitle();
			}

			Mage::getSingleton('core/session')->unsRequestStoreId();
		}
        // Validate config for this specific store
        if(Mage::helper('silverpoptransactional')->validateConfig($storeId)){
        	try{   
		        SFC_SilverpopTransactional_Helper_Paths::log("============================");
		        SFC_SilverpopTransactional_Helper_Paths::log(Mage::helper('silverpoptransactional')->__('Storing transactional email'));
		        
		        // load template by id        
		        if (is_numeric($templateId)) {
		            $this->load($templateId);
		        } else {
		            $localeCode = Mage::getStoreConfig('general/locale/code', $storeId);
		            $this->loadDefault($templateId, $localeCode);
		        }
		        
		        // check for template code
		        if (!$this->getId()) {
			        throw Mage::exception('Mage_Core', Mage::helper('core')->__('Invalid transactional email code: ' . $templateId));
			    }
			    	        
        		// build default sender array     		
		        if (!is_array($sender)) {
		            $sender = array(
		            	'name' 	=> Mage::getStoreConfig('trans_email/ident_' . $sender . '/name', $storeId),
		            	'email' => Mage::getStoreConfig('trans_email/ident_' . $sender . '/email', $storeId)
		            );
		        }
		        
		        // add store data to template vars
                if (!isset($vars['store'])) {
		            $vars['store'] = Mage::app()->getStore($storeId);
		        }
			    		        
		        // setup multiple recipients, add recipients to data
		        $emails = array_values((array)$email);
		        $names = is_array($name) ? $name : (array)$name;
		        $names = array_values($names);
		        foreach ($emails as $key => $email) {
		            if (!isset($names[$key])) {
		                $names[$key] = substr($email, 0, strpos($email, '@'));
		            }
		        }		
		        $vars['email'] = reset($emails);
		        $vars['name'] = reset($names);
				
				// setup return email
		        $setReturnPath = Mage::getStoreConfig(self::XML_PATH_SENDING_SET_RETURN_PATH);
		        switch ($setReturnPath) {
		            case 1:
		                $returnPathEmail = $sender['email'];
		                break;
		            case 2:
		                $returnPathEmail = Mage::getStoreConfig(self::XML_PATH_SENDING_RETURN_PATH_EMAIL);
		                break;
		            default:
		                $returnPathEmail = null;
		                break;
		        }
		        
		        // get message body and subject
		        $this->setUseAbsoluteLinks(true);
		        $text = $this->getProcessedTemplate($vars, true);
		        $subject = $this->getProcessedTemplateSubject($vars);
		        SFC_SilverpopTransactional_Helper_Paths::log(Mage::helper('silverpoptransactional')->__($subject));
		        		        
		        // general send test
		        if(
		        	$sender['name'] == '' ||
		        	$sender['email'] == '' ||
		        	$subject == ''		        
		        ) {
			        Mage::throwException(Mage::helper('silverpoptransactional')->__("Mail cannot be sent."));
		        }
		        
		        $silverpopEmail = Mage::getModel('silverpoptransactional/email')->setData(array(
		        	'store_id'			=> $storeId,
		        	'sender_name'		=> $sender['name'],
		        	'sender_email'		=> $sender['email'],
		        	'recipient_email'	=> serialize($emails),
		        	'recipient_name'	=> serialize($names),
		        	'subject'			=> $subject,
		        	'content'			=> $text,
		        	'created_at'		=> now(),
		        	'num_retries'		=> 0,
		        	'status'			=> 0
		        ))->save();
		        Mage::helper('silverpoptransactional')->logEntry(Mage::helper('silverpoptransactional')->__('Storing transactional email'), $silverpopEmail->getId());
		        SFC_SilverpopTransactional_Helper_Paths::log(Mage::helper('silverpoptransactional')->__('Save Successful'));		    
			           
        	} Catch (Exception $e) {
        		SFC_SilverpopTransactional_Helper_Paths::log($e->getMessage());
        		SFC_SilverpopTransactional_Helper_Paths::log($e->getTraceAsString());
        		Mage::helper('silverpoptransactional')->sendErrorEmail($subject."\nError: ".$e->getMessage(), Mage::helper('silverpoptransactional')->__('Silverpop Transactional Email Save Failure.'), $storeId);	
	        	SFC_SilverpopTransactional_Helper_Paths::log(Mage::helper('silverpoptransactional')->__("Using standard Magento mailer."));
	        	Mage::throwException($e->getMessage());
        	}
        } else {

			//When silverpop disabled then include header,footer and promoblock in email template BCC-528
			$vars['is_silverpop_disbled'] = 1;
			$this->isSilverpopDisabled = true;

        	SFC_SilverpopTransactional_Helper_Paths::log(Mage::helper('silverpoptransactional')->__("Using standard Magento mailer."));
	        parent::sendTransactional($templateId, $sender, $email, $name, $vars, $storeId=null);
        }
    }

	/**
	 * Return true if template type eq text
	 *
	 * @return boolean
	 */
	public function isPlain()
	{
		if($this->isSilverpopDisabled){
			return false;
		}

		parent::isPlain();

	}

}