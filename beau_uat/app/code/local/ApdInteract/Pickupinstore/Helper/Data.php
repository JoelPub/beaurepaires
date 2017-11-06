<?php
class ApdInteract_Pickupinstore_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getStorebyId($store_id) {
		$model = Mage::getModel('storelocator/stores');
        $store = $model->load($store_id);
                
        return $store;
	}
}
	 