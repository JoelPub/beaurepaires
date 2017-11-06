<?php

class ApdInteract_Geopostcode_IndexController extends Mage_Core_Controller_Front_Action
{
    public function addressAction()
    {
        $items = array();
        $street = Mage::app()->getRequest()->getParam('street');
        $city = Mage::app()->getRequest()->getParam('city');
        $zip = Mage::app()->getRequest()->getParam('zip');
        $callback = Mage::app()->getRequest()->getParam('callback');

        $cacheId = 'geo_' . $street.'_'.$city.'_'.$zip;

        if(false !== ($data = Mage::app()->getCache()->load($cacheId))){
            $items = unserialize($data);
        }else{
            $collection = Mage::getModel('apdinteract_geopostcode/geopostcode')
                ->getCollection()
                ->addFieldToSelect('entity_id')
                ->addFieldToSelect('street')
                ->addFieldToSelect('locality')
                ->addFieldToSelect('post_code')
            ;

            if($street){
                $collection->addFieldToFilter('street', array("like" => "$street%"));
            }
            if($city){
                $collection->addFieldToFilter('locality', array("like" => "$city%"));
            }
            if($zip){
                $collection->addFieldToFilter('post_code', array("like" => "$zip%"));
            }

           $collection->getSelect()->group('street')->limit(10);
           $geoCollection = $collection;

            foreach ($geoCollection as $item) {
                $items['data'][] = array(
                    "entity_id" => $item['entity_id'],
                    "street" => $item['street'],
                    "city" => $item['locality'],
                    "zip" => $item['post_code'],
                );
            }

            Mage::app()->getCache()->save(serialize($items), $cacheId);
        }

        if ($callback){
            $data = Mage::helper('core')->jsonEncode($items);
            echo "{$callback}({$data});";
        }else{
            $items['data'][] = array("message" => "Error, no Callback function define");
            echo Mage::helper('core')->jsonEncode($items);
        }
    }

}

