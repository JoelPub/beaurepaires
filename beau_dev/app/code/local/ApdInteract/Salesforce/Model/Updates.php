<?php

class ApdInteract_Salesforce_Model_Updates extends Mage_Core_Model_Abstract {

    protected function _construct() {
        $this->_init('apdinteract_salesforce/updates');
    }

    /**
     * save updates
     *
     * @param Mage_Core_Model_Abstract $mage_model        	
     * @param string $updates        	
     */

    public function saveUpdates($mage_model, $class=false) {        
        if ($mage_model || $class) {
            if($class)
                $class_name = $class;
            else                
                $class_name = get_class($mage_model);
            
            $id = $this->_checkIfExist($class_name);
            if ($id) {
                $this->load($id);
            }
            $now = Mage::getModel('core/date')->gmtDate('Y-m-d H:i:s');            
            $this->setData("entity_type", $class_name);
            $this->setData("updated_at", $now);
            $this->save();
        }
        return $this;
    }

    private function _checkIfExist($class) {
        $dateTime = $this->getCollection()
                ->addFieldToFilter("entity_type", $class)
                ->setOrder('updated_at', 'DESC')
                ->getFirstItem()
                ->getData();

        return $dateTime['updated_at'] ? $dateTime['id'] : false;
    }

}
