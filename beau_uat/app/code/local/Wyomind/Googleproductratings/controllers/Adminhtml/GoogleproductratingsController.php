<?php

class Wyomind_Googleproductratings_Adminhtml_GoogleproductratingsController extends Mage_Adminhtml_Controller_Action
{

    public function generateAction() 
    {


        $websiteCode = $this->getRequest()->getParam('website');
        $storeCode = $this->getRequest()->getParam('store');
        $feed = Mage::getModel("googleproductratings/feeds");
        try {
            $data = $feed->generate($websiteCode, $storeCode);
            Mage::app()->getResponse()->setBody(Mage::helper("core")->jsonEncode($data));
        } catch (Exception $e) {
            $data = array("link" => $e->getMessage());
            Mage::app()->getResponse()->setBody(Mage::helper("core")->jsonEncode($data));
        }
    }

    public function isAllowed() 
    {
        return true;
    }

}
