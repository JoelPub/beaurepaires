<?php

class ApdInteract_SalesRule_Model_Quote extends Mage_Sales_Model_Quote
{
	protected function _addCatalogProduct(Mage_Catalog_Model_Product $product, $qty = 1)
    {
        $newItem = false;
        $item = $this->getItemByProduct($product);
        if (!$item) {
            $item = Mage::getModel('sales/quote_item');
            $item->setQuote($this);
            if (Mage::app()->getStore()->isAdmin()) {
                $item->setStoreId($this->getStore()->getId());
            }
            else {
                $item->setStoreId(Mage::app()->getStore()->getId());
            }
            $newItem = true;
        }
        

        /**
         * We can't modify existing child items
         */
        if ($item->getId() && $product->getParentProductId()) {
            return $item;
        }

        $item->setOptions($product->getCustomOptions())
            ->setProduct($product);

        // Add only item that is not in quote already (there can be other new or already saved item
        
        $count = $this->_getItemByProduct($product->getId(),$product->getSku());
        if ($newItem && $count<=2) {  		    
            $this->addItem($item);
            Mage::getSingleton('checkout/session')->setUpdateCart('1');	
        } else {
			 Mage::getSingleton('checkout/session')->setUpdateCart('0');
		}

        return $item;
    }
    
    public function getItemByProduct($product)
    {
       
       if(Mage::getSingleton('core/session')->getToadd()=='') {
	        foreach ($this->getAllItems() as $item) {
	            if ($item->representProduct($product)) {
	                return $item;	         
	            }
	        }
	        return false;
       } 
       
       return false;
        
    }
    
    
    public function _getItemByProduct($id,$sku)
    {
    		
      		$x= 0;
      		$cart = Mage::getSingleton('checkout/cart')->getQuote();
	        foreach ($cart->getAllItems() as $item) {	                
	            if ($item->getProductId()==$id && $item->getProductSku()==$sku) {
	                $x++;
	            }
	        }
	        return $x;
      
    }

  
}
