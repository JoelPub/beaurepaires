<?php
class ApdInteract_Salesforce_Model_Process_Business_SampleWithInput
extends ApdInteract_Salesforce_Model_Core_Process_Abstract {
	
	public function process() {
		$name = $this->input["name"];
		echo "hello $name !";
		$this->result["success"] = true;
		return $this;
	}

}