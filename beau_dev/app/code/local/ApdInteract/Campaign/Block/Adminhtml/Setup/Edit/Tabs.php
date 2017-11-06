<?php
class ApdInteract_Campaign_Block_Adminhtml_Setup_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("campaign_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("campaign")->__("Campaign Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("campaign")->__("Campaign Configuration"),
				"title" => Mage::helper("campaign")->__("Configuration"),
				"content" => $this->getLayout()->createBlock("campaign/adminhtml_setup_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
