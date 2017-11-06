<?php

class ApdInteract_Wheels_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function wheelCodesAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function savecarAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function clearAction() {

        Mage::getSingleton('core/session')->setSizeF('');
        Mage::getSingleton('core/session')->setSize1F('');
        Mage::getSingleton('core/session')->setSeriesF('');
        Mage::getSingleton('core/session')->setTMakeF('');
        Mage::getSingleton('core/session')->setTModelF('');
        Mage::getSingleton('core/session')->setTYearF('');
        Mage::getSingleton('core/session')->setTSeriesNameF('');
        Mage::getSingleton('core/session')->setTMakeModelF('');
        Mage::getSingleton('core/session')->setWSeriesF('');

        $this->_redirectUrl('/wheels/');
    }

}
