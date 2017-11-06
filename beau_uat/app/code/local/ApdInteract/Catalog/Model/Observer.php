<?php

class ApdInteract_Catalog_Model_Observer {


    public function makeSeoFriendlyProductUrlKey($observer) {
        // [brand]-[product-name]
        
        if ($_product=$observer->getEvent()->getProduct()) {
            
            // BCC-25. Duplicate URLs on simple products causing SQL errors.
            $visibility = $_product->getVisibility();
            if ($visibility == Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE) {
                return;
            }
            
            $url_bits = array();
            
            $product_name = $_product->getData('name');
            
            if (is_null( $brand = $_product->getData('brand_value') )) {                              
                $brand = $_product->getAttributeText('brand');
            }
            
            if (!is_null($brand) && strpos($product_name, $brand) === false) {
                $url_bits[] = $brand;
            }            
            
            $url_bits[] = $product_name;      
            
            $url = $this->stringToUrlKey(implode('-', $url_bits));
            
            //Mage::log('My log entry'.$url, null, 'mylogfile.log'); 

            $_product->setData('url_key', $url);
        }
        
    }
    
    public function stringToUrlKey($string) {
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $string);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", '-', $clean);
        return $clean;
    }
    
    public function changeTemplateIfWheels($observer) {
       /* $action = $observer->getEvent()->getAction(); 
        $layout = $observer->getEvent()->getLayout();
        $layer = Mage::getSingleton('catalog/layer');
        $Category = $layer->getCurrentCategory();
        $currentCatId= $Category ->getId(); //will give current categeory id 
        $appliedFilters = Mage::getSingleton('catalog/layer')->getState()->getFilters();
        
        $countFilter = count($appliedFilters);
        Mage::unregister('filter_counter');
        Mage::register('filter_counter', $countFilter);
        
        if ($action->getFullActionName() == 'catalog_category_view' && $currentCatId==42 && $countFilter>0) {
            $root = $layout->getBlock('root');
            $layer = $layout->getBlock('listing.finder.all');
            if ($root) {

                $root->setTemplate('page/2columns-left.phtml');

            }
                        
                               
        } elseif ($action->getFullActionName() == 'catalog_category_view' && $currentCatId==42 && $countFilter <= 0) {
            $layout = Mage::getSingleton('core/layout');
            $layout->getUpdate()->addUpdate('<remove name="product_list"/><remove name="category.products"/>');
            $layout->getUpdate()->load();
            $layout->generateXml();
            $layout->generateBlocks();
        }*/
    }
    
    public function deleteToSF($observer) {
        
        if (Mage::helper('addblock')->checkSF()): //check if salesforce module is enabled
            $_product=$observer->getEvent()->getProduct();                    
            try {
                $dictionary = Mage::getModel("apdinteract_salesforce/dictionary");
                $connector = Mage::getModel("apdinteract_salesforce/core_salesforce_connector_entityConnector", array("entity" => "Product2"));
                $connector->authorize();
                $sf_id = Mage::Helper('apdinteract_salesforce')->getSFId($_product->getId(), get_class($model));
                $result = $connector->delete($sf_id)->getResult();
                
                if (!isset($result->id)):
                    Mage::log($result[0]->errorCode . ':' . $result[0]->message, null, 'product_error.log');
                else:
                    Mage::log($result->id, null, 'prduct_success.log');
                endif;
            } catch (Exception $e) {
                Mage::logException($e);
            }
        endif;
    }

    /**
     * Update searchfilter
     *
     * @param $observer
     * @throws Exception
     *
     */
    public function updateFilter($observer){

        $includeController = array('catalog_product');

        if(in_array(Mage::app()->getRequest()->getControllerName(),$includeController)){
            $size = "";
            $_product=$observer->getEvent()->getProduct();
            if($_product->getAttributeText('size') != ""){
                $size = $_product->getAttributeText('size');
            }else{
                $size = $_product->getAttributeText('rim_diameter_configurable');
            }

            if($_product->getTypeId() == "simple" && $size != ""){
                $parentIds = Mage::getModel('catalog/product_type_grouped')->getParentIdsByChild($_product->getId());
                if(!$parentIds)
                    $parentIds = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($_product->getId());
                if(isset($parentIds[0])){

                    $parent = Mage::getModel('catalog/product')->load($parentIds[0]);
                    $searchFilter = explode(",",$parent->getSearchfilter());

                    if($_product->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_DISABLED){

                        $newSizeList = array_diff($searchFilter, array($size));
                        $parent->setSearchfilter(implode(",", $newSizeList));
                        $parent->save();
                    }elseif($_product->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_ENABLED){

                        if(!in_array($size,$searchFilter)){
                            $searchFilter[] = $size;
                            $parent->setSearchfilter(implode(",", $searchFilter));
                            $parent->save();
                        }
                    }

                }
            }
        }

    }

    public function ChangeCategoryView(Varien_Event_Observer $observer)
    {
       
        $category = $observer->getEvent()->getCategory();
        $block = Mage::getModel('cms/block')->load('category-page-how-it-works');
        $categoryList = array(
            ApdInteract_Catalog_Model_Category_Beau::TYRES_ID,
            ApdInteract_Catalog_Model_Category_Beau::WHEELS_ID
        );

        if((bool) $block->getIsActive()) {
            if (empty(Mage::getSingleton('core/session')->getVehicleType()) &&  empty(Mage::getSingleton('core/session')->getTyreSize()) && $category->getId() == ApdInteract_Catalog_Model_Category_Beau::TYRES_ID){
                $category->setData('description', '');
                $category->setData('landing_page', $block->getId());
                $category->setData('display_mode', 'PAGE');

            }elseif (empty(Mage::getSingleton('core/session')->getVehicleType()) && $category->getId() == ApdInteract_Catalog_Model_Category_Beau::WHEELS_ID) {
                $category->setData('description', '');
                $category->setData('landing_page', $block->getId());
                $category->setData('display_mode', 'PAGE');
            }
        }

    }
    
    public  function categoryPromoBanner(Varien_Event_Observer $observer){

        $category = $observer->getEvent()->getCategory();
        $title = strtolower($category->getName());
        $identifier = "promo-{$title}-cdp";
        $blocks = Mage::getModel('dynamicblock/dynamicblock')->getAllBlocksPerPosition($identifier);

        if((bool) count($blocks)){
            $block = $blocks->getData()[0];
            $category->setData('landing_page', $block['block_id']);
        }

    }
}

