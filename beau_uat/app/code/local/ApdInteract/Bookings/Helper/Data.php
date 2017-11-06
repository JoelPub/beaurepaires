<?php
class ApdInteract_Bookings_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * @param $adminId
     * @return mixed
     */
    public function getStoreIds($adminId){

        $storeIds = array();
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $storeModel =  "SELECT store_id FROM   {$resource->getTableName('iwd_storelocator_users')} WHERE account_id = {$adminId}";
        $results = $readConnection->fetchAll($storeModel);

        if((bool)count($results)){
            foreach($results as $storeId){
                $storeIds[] =  $storeId['store_id'];
            }
        }
        return $storeIds;
    }
}
	 