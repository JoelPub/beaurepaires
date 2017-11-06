<?php

class ApdInteract_Vir_Block_Index extends Mage_Core_Block_Template {

    public function loadImage() {
        
        $params = Mage::app()->getRequest()->getParams();                

        $imageAr = $this->_getImage($params['type'], $params['id']);

        $image = $imageAr[$params['image']];

        return array('type'=>$params['image'],'img'=>$image);
    }

    private function _getImage($type, $vir_id) {
        if ($type == 'commercial') :
            $model = Mage::getModel('apdinteract_vir/ordercommercial')->load($vir_id);
        else:
            $model = Mage::getModel('apdinteract_vir/order')->load($vir_id);
        endif;

        return $model->getData();
    }

}
