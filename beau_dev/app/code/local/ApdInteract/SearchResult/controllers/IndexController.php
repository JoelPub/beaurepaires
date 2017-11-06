<?php
class ApdInteract_SearchResult_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $param = Mage::app()->getRequest()->getParams();
        if($param['section'] == 'vehicle') {
            Mage::getSingleton('core/session')->setVehicleType(true);
        }elseif ($param['section'] == 'size'){
            Mage::getSingleton('core/session')->setTyreSize(true);
        }

        $this->loadLayout();
        $this->renderLayout();

    }
    
    public function clearAction() {

            Mage::getSingleton('core/session')->unsVehicleType();
            Mage::getSingleton('core/session')->unsTyreSize();
            $type = Mage::app()->getRequest()->getParam('type');
            Mage::getSingleton('core/session')->setSizeF('');
            Mage::getSingleton('core/session')->setSize1F('');
            Mage::getSingleton('core/session')->setSeriesF('');
            Mage::getSingleton('core/session')->setTMakeF('');
            Mage::getSingleton('core/session')->setTModelF('');
            Mage::getSingleton('core/session')->setTYearF('');
            Mage::getSingleton('core/session')->setTSeriesNameF('');
            Mage::getSingleton('core/session')->setTMakeModelF('');
            Mage::getSingleton('core/session')->setSizeF('');
            Mage::getSingleton('core/session')->setWSeriesF('');
            Mage::getSingleton('core/session')->setTMakeF('');
            Mage::getSingleton('core/session')->setTModelF('');
            Mage::getSingleton('core/session')->setTYearF('');
            Mage::getSingleton('core/session')->setTSeriesNameF('');
            Mage::getSingleton('core/session')->setTMakeModelF('');            
            if(isset($type))
                $this->_redirectUrl('/'.$type);
            else
                $this->_redirectUrl('/tyres');
    }
}