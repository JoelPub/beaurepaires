<?php
class ApdInteract_Rel_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function checkUrlValues(){
        $check = true;
        $collection = Mage::getModel('apdinteract_rel/canonical')->getCollection()
                        ->addFieldToSelect('*');
         
        foreach ($collection as $data){
            if (!$data->getUrl() || empty($data->getUrl())){
                $check = false;
            }
        }
        return $check;
    }
}
