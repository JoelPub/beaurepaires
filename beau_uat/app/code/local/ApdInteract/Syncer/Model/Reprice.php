<?php
class ApdInteract_Syncer_Model_Reprice  extends Wisepricer_Syncer_Model_Reprice {
	 public function updatePricesBySku($prodArr,$price_field){
        $connection     = $this->_getConnection('core_write');

        if(!is_array($prodArr)){
            $sku            = $prodArr->sku;
            $newPrice       = $prodArr->price;
        }else{
            $sku            = $prodArr['sku'];
            $newPrice       = $prodArr['price'];
        }
      try{
        $productId      = $this->_getIdFromSku($sku);
        $attributeId    = $this->_getAttributeId($price_field);
        $spAttributeId  = $this->_getAttributeId('special_price');
        $specialPrice   = $this->_getSpecialPrice($productId,$spAttributeId);
        $product = Mage::getModel('catalog/product')->load($productId);
        $flag = (int)$product->getNoupdatebywiser();

        if($specialPrice){
          $attributeId= $spAttributeId;
        }

          if($newPrice <= 0){
              throw new Exception("Price [$newPrice] is invalid Product Id: $productId");
          }
          
		  if($flag<=0){ //check if Set to manual price update
	          $sql = "UPDATE " . $this->_getTableName('catalog_product_entity_decimal') . " cped
	                    SET  cped.value = ?
	                WHERE  cped.attribute_id = ?
	                AND cped.entity_id = ?";
	          $connection->query($sql, array($newPrice, $attributeId, $productId));
	          
	          Mage::log($flag->getSku(),null,'skipped.log');
	          
	      }
        $this->_getConfigurableIds($productId, $newPrice);

      }catch(Exception $e){
          Mage::log($e->getMessage(),null,'wplog.log');
          echo $e->getMessage();
      }
    }
    
    public function updatePricesById($prodArr,$price_field){
        $connection     = $this->_getConnection('core_write');

        if(!is_array($prodArr)){
            $productId      = $prodArr->sku;
            $newPrice       = $prodArr->price;
        }else{
            $productId      = $prodArr['sku'];
            $newPrice       = $prodArr['price'];
        }
        try{
        	 $productIdfromSku      = $this->_getIdFromSku($productId);
             $attributeId    = $this->_getAttributeId($price_field);

             $spAttributeId  = $this->_getAttributeId('special_price');
             $specialPrice   = $this->_getSpecialPrice($productId,$spAttributeId);
             
             $product = Mage::getModel('catalog/product')->load($productIdfromSku);
        	 $flag = (int)$product->getNoupdatebywiser();

             if($specialPrice){
                $attributeId= $spAttributeId;
             }

            if($newPrice <= 0){
                throw new Exception("Price [$newPrice] is invalid Product Id: $productId");
            }

			if($flag<=0){ //check if Set to manual price update
              $sql = "UPDATE " . $this->_getTableName('catalog_product_entity_decimal') . " cped
                    SET  cped.value = ?
                WHERE  cped.attribute_id = ?
                AND cped.entity_id = ?";
              $connection->query($sql, array($newPrice, $attributeId, $productId));
              Mage::log($flag->getSku(),null,'skipped.log');
            }
             $this->_getConfigurableIds($productId, $newPrice);

        }catch(Exception $e){
            Mage::log($e->getMessage(),null,'wplog.log');
            echo $e->getMessage();
        }
    }
}