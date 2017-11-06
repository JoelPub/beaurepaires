<?php
class ApdInteract_Campaign_Block_Adminhtml_Campaign_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("campaign_form", array("legend"=>Mage::helper("campaign")->__("Item information")));

				
						$fieldset->addField("first_name", "text", array(
						"label" => Mage::helper("campaign")->__("First name"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "first_name",
						));
					
						$fieldset->addField("last_name", "text", array(
						"label" => Mage::helper("campaign")->__("Last name"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "last_name",
						));
					
						$fieldset->addField("email", "text", array(
						"label" => Mage::helper("campaign")->__("Email"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "email",
						));
					
						$fieldset->addField("mobile", "text", array(
						"label" => Mage::helper("campaign")->__("Mobile"),
						"name" => "mobile",
						));
					
						$fieldset->addField("postcode", "text", array(
						"label" => Mage::helper("campaign")->__("Postcode"),
						"name" => "postcode",
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
