<?php

class Autocompleteplus_Autosuggest_Adminhtml_PushController extends Mage_Adminhtml_Controller_Action{

    public function startpushAction(){

        $service = Mage::getModel('autocompleteplus_autosuggest/service');

        $service->populatePusher();

        $this->getResponse()->setBody($this->getLayout()->createBlock('autocompleteplus_autosuggest/adminhtml_process')->toHtml());
        $this->getResponse()->sendResponse();
    }
}
