<?php

class ApdInteract_Onsale_Model_Layer extends Mage_Catalog_Model_Layer {

    public function getStateKey() {
        if ($this->_stateKey === null) {
            $this->_stateKey = 'STORE_' . Mage::app()->getStore()->getId() . '_ONSALEN_' . '_CUSTGROUP_' . Mage::getSingleton('customer/session')->getCustomerGroupId();
        }

        return $this->_stateKey;
    }

    public function getStateTags(array $additionalTags = array()) {
        $additionalTags = array_merge($additionalTags, array(
            'onsalen'
        ));
        return $additionalTags;
    }

    public function getProductCollection() {
        if (isset($this->_productCollections['onsalen'])) {
            $collection = $this->_productCollections['onsalen'];
        } else {
            $collection = $this->_getCollection();
            $this->prepareProductCollection($collection);
            $this->_productCollections['onsalen'] = $collection;
        }

        return $collection;
    }

    public function prepareProductCollection($collection) {

        $collection
                ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
        ;

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        return $this;
    }

    protected function _getCollection() {
        $collection = Mage::getModel('catalog/product')
                	 ->getCollection()
                     /*->joinField('category_id', 'catalog/category_product', 'category_id', 'product_id = entity_id', null, 'left')*/
                     ->addAttributeToSelect('*')                     
                     ->addAttributeToFilter('overlay', '712');
                     
                    //$collection->getSelect()->group('e.entity_id');
        #echo $collection->getSelect()->__toString();		

        return $collection;
    }

}
