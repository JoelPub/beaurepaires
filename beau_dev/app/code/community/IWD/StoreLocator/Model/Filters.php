<?php
class IWD_StoreLocator_Model_Filters  {
    public function toOptionArray() {
        $collection = Mage::getModel('storelocator/stores')->getCollection();        
        $keys = array_keys($collection->getFirstItem()->getData()); 
        $AllKey = array();
        foreach ($keys as $key): // loop through all the keys (fname, lname, email... 
            $AllKey[] = array('value'=>$key,'label'=>$key);
        endforeach;  
        sort($AllKey);
        return $AllKey;
    }
}
