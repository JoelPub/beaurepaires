<?php
class ApdInteract_Sync_Model_Observer
{
    public function updateStore()
    {
        try {
            $method         = "add_magento_store";
            $client         = Mage::Helper('sync')->connect_api($method);
            $magento_stores = Mage::getModel('storelocator/stores')->getCollection();
            foreach ($magento_stores as $mstore) {
                $magento_id          = $mstore->getId();
                $magento_title       = $mstore->getTitle();
                $magento_email       = $mstore->getEmail();
                $magento_MonOpen  = $mstore->getMonOpen();
                $magento_MonClose = $mstore->getMonClose();
                $magento_TueOpen  = $mstore->getTueOpen();
                $magento_TueClose = $mstore->getTueClose();
                $magento_WedOpen  = $mstore->getWedOpen();
                $magento_WedClose = $mstore->getWedClose();
                $magento_ThuOpen  = $mstore->getThuOpen();
                $magento_ThuClose = $mstore->getThuClose();
                $magento_FriOpen  = $mstore->getFriOpen();
                $magento_FriClose = $mstore->getFriClose();
                $magento_SatOpen     = $mstore->getSatOpen();
                $magento_SatClose    = $mstore->getSatClose();
                $magento_SunOpen     = $mstore->getSundayOpen();
                $magento_SunClose    = $mstore->getSundayClose();
                $state = Mage::Helper('sync')->getRegionById($mstore->getRegionId());
                
                /*Set Value for empty column*/
                if(trim($magento_MonFriOpen)=='')
                    $magento_MonFriOpen = 'Closed';
                if(trim($magento_MonFriClose)=='')
                    $magento_MonFriClose = 'Closed';
                if(trim($magento_SatOpen)=='')
                    $magento_SatOpen = 'Closed';
                if(trim($magento_SatClose)=='')
                    $magento_SatClose = 'Closed';
                if(trim($magento_SunOpen)=='')
                    $magento_SunOpen = 'Closed';
                if(trim($magento_SunClose)=='')
                    $magento_SunClose = 'Closed';                
                if(trim($magento_MonOpen)=='')
                    $magento_MonOpen = 'Closed';
                if(trim($magento_MonClose)=='')
                    $magento_MonClose = 'Closed';                
                if(trim($magento_TueOpen)=='')
                    $magento_TueOpen = 'Closed';
                if(trim($magento_TueClose)=='')
                    $magento_TueClose = 'Closed';                
                if(trim($magento_WedOpen)=='')
                    $magento_WedOpen = 'Closed';
                if(trim($magento_WedClose)=='')
                    $magento_WedClose = 'Closed';                
                if(trim($magento_ThuOpen)=='')
                    $magento_ThuOpen = 'Closed';
                if(trim($magento_ThuClose)=='')
                    $magento_ThuClose = 'Closed';                
                if(trim($magento_FriOpen)=='')
                    $magento_FriOpen = 'Closed';
                if(trim($magento_FriClose)=='')
                    $magento_FriClose = 'Closed';
                                    
                $data = json_encode(array(
                    "name" => $magento_title,
                    "email" => $magento_email,
                    "magento_store_id" => $magento_id,
                    "mon_open" => $magento_MonOpen,
                    "mon_close" => $magento_MonClose,
                    "tue_open" => $magento_TueOpen,
                    "tue_close" => $magento_TueClose,
                    "wed_open" => $magento_WedOpen,
                    "wed_close" => $magento_WedClose,
                    "thu_open" => $magento_ThuOpen,
                    "thu_close" => $magento_ThuClose,
                    "fri_open" => $magento_FriOpen,
                    "fri_close" => $magento_FriClose,
                    "sat_open" => $magento_SatOpen,
                    "sat_close" => $magento_SatClose,
                    "sun_open" =>$magento_SunOpen,
                    "sun_close" => $magento_SunClose,
                    "state" => $state

                ));

                // set some parameters  
                $client->setParameterPost('data', $data);
                // POST request  
                $response = $client->request(Zend_Http_Client::POST);
                
                /*if($response>0){
                $response = $magento_title." has been added/updated.<br>";
                } else {
                $response = $magento_title." has been skipped.<br>";
                }*/               
                
                Mage::log($response);
                
            }
        }
        catch (Exception $exc) {
            Mage::log($exc);
        }
        
    }
        
}

