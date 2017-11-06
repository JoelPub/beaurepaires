<?php
class ApdInteract_Pickupinstore_Block_Storesbylatlng extends Mage_Core_Block_Template {   
    public function getStores() {        
        $request = Mage::app()->getRequest();
        $lat = $request->getParam('lat');
        $lng = $request->getParam('lng');
        $area = $request->getParam('area');
        
        // eg 3000 -> Lat: -37.8152065 Lng: 144.963937
        
        return Mage::helper('storelocator')->getStoresByLatLng($lat, $lng, $area);
    }
    
    public function getStoresAsJson() {
        $stores = $this->getStores()->toArray();
        // This format for autocomplete: {"id":"Perdix perdix","label":"Grey Partridge","value":"Grey Partridge"}
        // {"id":"2698","label":"Grey Partridge","value":"Grey Partridge"}
        
        // want {"id":"2698","label":"BFT WEST MELBOURNE (1.16km)","value":"2698"}
        // current: {"entity_id":"2698","title":"BFT WEST MELBOURNE","distance":"1.1617150968349133"}
        
        foreach ($stores['items'] as $store) {
            
            $label = $store['title'] . ' (' . sprintf('%0.1f', $store['distance']) . ' km)';
            
            $dropdown[] = array(
                'id' => $store['entity_id'],
                'value' => $store['title'],            
                'label' => $label
                );          
        }
        
        echo json_encode($dropdown);
    }

}
