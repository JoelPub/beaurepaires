<?php

class ApdInteract_Salesforce_Model_Dictionary extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('apdinteract_salesforce/dictionary');
    }

    /**
     * save dictionary
     *
     * @param Mage_Core_Model_Abstract $mage_model        	
     * @param string $sid        	
     */
    public function saveDictionary($mage_model, $sid = null, $custom='') {
        
        $helper = Mage::Helper('apdinteract_salesforce');

        if ($mage_model) {
            $now = date("Y-m-d h:m:s", time());

            $class_name = get_class($mage_model);
            $new_class = $helper->getEquivalent($class_name);
            if($custom=='')
            $class_name = ($new_class!='') ? $new_class : $class_name;
            else
            $class_name =  ($new_class!='') ? $new_class."_".$custom : $class_name."_".$custom;
                    
            $this->setData("entity_id", $mage_model->getId());
            $this->setData("entity_type", $class_name);
            $this->setData("updated_at", $now);
            if (!$this->getId())
                $this->setData("created_at", $now);

            if ($sid)
                $this->setData("salesforce_id", $sid);

            $this->save();
        }
        return $this;
    }
    
}
