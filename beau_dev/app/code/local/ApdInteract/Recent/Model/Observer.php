<?php

class ApdInteract_Recent_Model_Observer {

    public function saveProductVisitHistory(Varien_Event_Observer $observer) {                
        $session = Mage::getSingleton('core/session');
        
        // Get recently viewed products from session
        $recentViewedProducts = $session->getRecentViewedItems();        
        if (!is_array($recentViewedProducts)) {
            $recentViewedProducts = array();
        }
        
        // Prepend currently viewed product to list, preserve keys
        $product_id = $observer->getEvent()->getProduct()->getId();
        $recentViewedProducts = array($product_id => $product_id) + $recentViewedProducts; 
        //$recentViewedProducts = array_unshift($recentViewedProducts,$product_id); 
        
        // Reduce list to maximum count as defined in admin
        $max_count = Mage::getStoreConfig('catalog/recently_products/viewed_count');
        $recentViewedProducts = array_slice($recentViewedProducts, 0, $max_count);
        
        // Store in session
        $session->setRecentViewedItems($recentViewedProducts);
    }

}
