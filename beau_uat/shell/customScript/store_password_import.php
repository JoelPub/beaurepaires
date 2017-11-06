<?php

require_once '../../app/Mage.php';
Mage::app();

$csv_data = 'GDT_API_LIveID_for_July17.csv';
$csv_path_location = Mage::getBaseDir('var') . DS . 'import' . DS . 'GDT_API_LIveID_for_July17.csv';
$data_array = array();

if (($handle = fopen($csv_path_location, "r")) !== FALSE) { //Read CSV File
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { //Loop trough CSV

        $data_array[] =  update_store($data);
    }
    fclose($handle);

    echo json_encode($data_array);
}

/**
 * @param $data
 * @return array
 * @throws Exception
 */
function update_store($data){

    $costar_live_id = (int)$data[1];
    $api_password = $data[4];

    if($costar_live_id){
        $collection = Mage::getModel('storelocator/stores')->getCollection()
            ->addFieldToSelect(array('title','entity_id','p_branch_password'))
            ->addFieldToFilter('p_costar_live_id', array('eq' => $costar_live_id))
            ->getFirstItem();

        $entity_id = $collection->getData('entity_id');
        if(!empty($entity_id)){

            //update Password
            $store = Mage::getModel('storelocator/stores')->load($entity_id);
            $store->setPBranchPassword($api_password);
            $store->save();
        }

        return array('store_old_data_password' => $collection->getData());
    }
}