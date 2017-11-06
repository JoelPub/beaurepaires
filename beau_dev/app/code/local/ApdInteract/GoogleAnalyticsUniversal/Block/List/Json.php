<?php
class ApdInteract_GoogleAnalyticsUniversal_Block_List_Json extends Enterprise_GoogleAnalyticsUniversal_Block_List_Json
{
    protected function _getProductCollection()
    {
        /* For catalog list and search results
         * Expects getListBlock as Mage_Catalog_Block_Product_List
         */
        if (is_null($this->_productCollection) && $this->getListBlock()) {
            $this->_productCollection = $this->getListBlock()->getLoadedProductCollection();
        }

        /* For collections of cross/up-sells and related
         * Expects getListBlock as one of the following:
         * Enterprise_TargetRule_Block_Catalog_Product_List_Upsell | _linkCollection
         * Enterprise_TargetRule_Block_Catalog_Product_List_Related | _items
         * Enterprise_TargetRule_Block_Checkout_Cart_Crosssell | _items
         * Mage_Catalog_Block_Product_List_Related | _itemCollection
         * Mage_Catalog_Block_Product_List_Upsell | _itemCollection
         * Mage_Checkout_Block_Cart_Crosssell, | setter items
         */
        if ($this->_showCrossSells && is_null($this->_productCollection) && $this->getListBlock()) {
            $this->_productCollection = $this->getListBlock()->getItemCollection();
        }

        // Support for CE
        if (is_null($this->_productCollection)
            && ($this->getBlockName() == 'catalog.product.related'
                || $this->getBlockName() == 'checkout.cart.crosssell'))
        {
            $this->_productCollection = $this->getListBlock()->getItems();
        }

        return $this->_productCollection;
    }
    
}
