<?php
//BFT-2356: COSTAR API - As a Magento Administrator, I require the ability to manage Costar Credentials from within the Store Locator (Admin)
 $costar_stores = Mage::getModel('storelocator/costar')->getCollection();
 
 foreach($costar_stores as $stores):
     $model = Mage::getModel('storelocator/stores'); 
     $name = $stores->getTitle();
     $collection = $model->getCollection()->addFieldToFilter('title', array('eq'=>$name));
     $store_details = $collection->getFirstItem();
     $id = $store_details->getId();
     if($id>0):        
        $model = $model->load($id);
        $model->setData('costar_store_code',$stores->getStoreid());
        $model->setData('p_costar_live_id',$stores->getCostarliveid());
        $model->setData('p_branch_password',$stores->getBranchpassword());
        $model->save();

        $stores->setData('magento_store_id',$model->getId()); // update costar table
        $stores->save();
     endif;
 endforeach; 


