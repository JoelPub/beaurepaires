<?php
class ApdInteract_Campaign_Block_Adminhtml_Setup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("campaign_form", array("legend"=>Mage::helper("campaign")->__("Configuration")));

				
						
                                                $fieldset->addField("campaign_name", "text", array(
						"label" => Mage::helper("campaign")->__("Name"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "campaign_name",
						));
                                                
                                                $fieldset->addField("sku", "text", array(
						"label" => Mage::helper("campaign")->__("Product Sku"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "sku",
						));
                                                
                                                $fieldset->addField('cms_page', 'select', array(
                                                'label'     => Mage::helper('campaign')->__('CMS Page'),
                                                'class'     => 'required-entry',
                                                'required'  => true,
                                                'name'      => 'cms_page',
                                                'values'    => Mage::getModel('cms/page')->getCollection()->toOptionArray()
                                                ));
                                                
                                                
                                                $fieldset->addField('thank_you', 'select', array(
                                                'label'     => Mage::helper('campaign')->__('Confirmation Page'),
                                                'class'     => 'required-entry',
                                                'required'  => true,
                                                'name'      => 'thank_you',
                                                'values'    => Mage::getModel('cms/page')->getCollection()->toOptionArray()
                                                ));
					
						
					
						/*$fieldset->addField('edm', 'select', array(
                                                'label'     => Mage::helper('campaign')->__('Email Template'),
                                                'class'     => 'required-entry',
                                                'required'  => true,
                                                'name'      => 'edm',
                                                'values'    => Mage::helper('campaign')->getAllEmailTemplates()
                                                ));*/
                                                
                                                $fieldset->addField('store_id', 'select', array(
                                                    'name' => 'store_id',
                                                    'label' => Mage::helper('cms')->__('Store View'),
                                                    'title' => Mage::helper('cms')->__('Store View'),
                                                    'required' => true,
                                                    'values' => Mage::helper('campaign')->getAllStoreViews()
                                                ));
                                                
                                                $fieldset->addField('active', 'select', array(
                                                'label'     => Mage::helper('campaign')->__('Status'),
                                                'class'     => 'required-entry',
                                                'required'  => true,
                                                'name'      => 'active',
                                                'values'    =>  Mage::getModel('catalog/product_status')->getOptionArray()
                                                ));
                                                
                                                 
  
											
					

				if (Mage::getSingleton("adminhtml/session")->getCampaignData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getCampaignData());
					Mage::getSingleton("adminhtml/session")->setCampaignData(null);
				} 
				elseif(Mage::registry("campaign_data")) {
				    $form->setValues(Mage::registry("campaign_data")->getData());
				}
				return parent::_prepareForm();
		}
}
