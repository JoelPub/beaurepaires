<?php


class ApdInteract_Campaign_Block_Adminhtml_Setup extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_setup";
	$this->_blockGroup = "campaign";
	$this->_headerText = Mage::helper("campaign")->__("Campaign Setup");
	
	parent::__construct(); 
	}

}