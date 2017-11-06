<?php

class ApdInteract_Addblock_Block_Addblock extends Mage_Core_Block_Template {

    protected $message;

    protected function _construct() {


        $module = Mage::app()->getRequest()->getModuleName();
        $layer = Mage::getSingleton('catalog/layer');
        $_category = $layer->getCurrentCategory();
        $currentCategoryId = $_category->getId();
        $tyre_widget = Mage::helper('addblock')->getAllTyreWidgetCat();
        $count_filter = count($_GET); //check if there are filter active


        /*
         * Remove for new vehicle selector location
         *
         $categoryPost = $this->_getPost('category');
        if ($currentCategoryId == '' || $currentCategoryId == 2)
            $currentCategoryId = Mage::getSingleton('core/session')->getCategoryF();

        if ($module == 'catalogsearch') {
            $this->setTemplate('addblocks/tyre-finder.phtml');
        } else if ($module == 'wheel-visualiser') {
            $this->setTemplate('addblocks/wheel-finder.phtml');
        } else if ((isset($categoryPost) && $categoryPost == '42') || (!isset($categoryPost) && $currentCategoryId == 42)) {
            $this->setTemplate('addblocks/wheel-selector.phtml');
        } else if ((isset($categoryPost) && in_array($categoryPost, $tyre_widget)) || in_array($currentCategoryId, $tyre_widget)) {
            $this->setTemplate('addblocks/tyre-finder.phtml');
        }
        */
    }

    private function _getPost($parameter) {
        return Mage::app()->getRequest()->getPost($parameter);
    }

    
}
