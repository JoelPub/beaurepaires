<?php
class Apdinteract_Customer_MobilityController extends Mage_Core_Controller_Front_Action{
    
    public function indexAction(){

        $helper = Mage::helper('apdinteract_customer');

        if(!$helper->mobilitySubscriber()){
            $this->norouteAction();
            return;
        }

        $this->loadLayout();
        $this->renderLayout();
    }
}