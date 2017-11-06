<?php

class ApdInteract_Salesforce_Model_System_Config_Source_Dropdown_Mode 
extends Mage_Core_Model_Abstract {
	
	public function toOptionArray()
	{
		return array(
			array(
				'value' => 'usernamePasswordAuth',
				'label' => "Username & Password",
			)
		);
	}
	
}