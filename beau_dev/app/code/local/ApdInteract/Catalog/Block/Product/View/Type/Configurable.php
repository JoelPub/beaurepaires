<?php

class ApdInteract_Catalog_Block_Product_View_Type_Configurable extends Mage_Catalog_Block_Product_View_Type_Configurable
{

    private $_wheel_location;
    private $_wheel_or_tyre;
    private $_filtered_options;
    private $_product_options_by_size;

    const TYRE = 1;
    const WHEEL = 2;
    const FRONT = 1;
    const REAR = 2;
    const ALL = 0;

    private function _getProductOptionsThatFitChosenVehicleFromSession()
    {
        // get options from session
        // $vehicle_id = Mage::getSingleton('core/session')->getSeriesF(); // eg 30551

        if (!isset($this->_filtered_options)) {

            $product_name = $this->getProduct()->getName();

            if ($this->isWheel()) {
                $wheel_options = Mage::helper('searchtyre')->getVehicleWheelOptions($product_name);

                foreach ($wheel_options as $option) {
                    // Separates the link of front/rear wheel sizes
                    // Not sure this is what we want. (eg 10x4 on front might only work with 11x5 on rear).
                    // But it's how it's going to display on FE. (eg Select front size. Select rear size).
//                    $this->_filtered_options['front'][$option->FrontWheel->Code] = $option->FrontWheel->Size->Description;
//                    $this->_filtered_options['rear'][$option->RearWheel->Code] = $option->RearWheel->Size->Description;
                    // To filter, we just use the SKUs, and duplicate both values across front and rear selectors
                    $this->_filtered_options[$option->FrontWheel->Code] = $option->FrontWheel->Code;
                    $this->_filtered_options[$option->RearWheel->Code] = $option->RearWheel->Code;
                }
            }

            if ($this->isTyre()) {
                $tyre_options = Mage::helper('searchtyre')->getVehicleTyreOptions($product_name);
                if (is_array($tyre_options)) {
                    foreach ($tyre_options as $option) {
                        // To filter, we just use the SKUs, and duplicate both values across front and rear selectors
                        $this->_filtered_options[$option->FrontTyre->Code] = $option->FrontTyre->Code;
                        $this->_filtered_options[$option->RearTyre->Code] = $option->RearTyre->Code;
                    }
                }
            }
        }

        return $this->_filtered_options;
    }

    private function _setWheelLocation($location = self::ALL)
    {
        $this->_wheel_location = $location;
    }

    private function _setWheelOrTyre($type = self::WHEEL)
    {
        $this->_wheel_or_tyre = $type;
    }

    private function _getWheelLocation()
    {
        return $this->_wheel_location;
    }

    private function _getWheelOrTyre()
    {
        return $this->_wheel_or_tyre;
    }

    public function getTyreSizeOptions()
    {
        $this->_setWheelLocation(self::ALL);
        $this->_setWheelOrTyre(self::TYRE);

        return $this->_getSizeOptions();
    }

    public function getWheelSizeOptions()
    {
        $this->_setWheelLocation(self::ALL);
        $this->_setWheelOrTyre(self::WHEEL);

        return $this->_getSizeOptions();
    }

//    public function getRearSizeOptions() {
//        $this->_setWheelLocation(self::REAR);
//        return $this->_getSizeOptions();
//    }


    public function isWheel()
    {
        return ($this->isWheelOrTyre() == self::WHEEL);
    }

    public function isTyre()
    {
        return ($this->isWheelOrTyre() == self::TYRE);
    }

    public function isWheelOrTyre()
    {
        if (null == $this->_getWheelOrTyre()) {
            $this->_setWheelOrTyreFromProductCategory();
        }
        return $this->_getWheelOrTyre();
    }

    private function _getCategoryName($_product)
    {
        $categoryIds = $_product->getCategoryIds();

        if (count($categoryIds)) {
            $firstCategoryId = $categoryIds[0];
            $_category = Mage::getModel('catalog/category')->load($firstCategoryId);

            return $_category->getName();
        }
    }

    private function _isWheelOrTyre()
    {
        $product = $this->getProduct();

        $category_name = $this->_getCategoryName($product);
        if ($category_name == 'Wheels') {
            return self::WHEEL;
        }
        if ($category_name == 'Tyres') {
            return self::TYRE; // or wheel
        }
//
//        $categories = $product->getCategoryCollection()->load();
//        foreach ($categories as $category) {
//            if ($category->getName() == 'Wheels') {
//                return self::WHEEL; // or wheel
//                break;
//            }
//            if ($category->getName() == 'Tyres') {
//                return self::TYRE; // or wheel
//                break;
//            }
//        }

        return false;
    }

    private function _setWheelOrTyreFromProductCategory()
    {
        $this->_setWheelOrTyre($this->_isWheelOrTyre());
    }

    private function _formatWheelSize($string)
    {
        // input "14x6 | pcd100 | offset38"
        // output "14 x 6"
        return str_replace('x', ' x ', strtok($string, ' |'));
    }

    private function _formatMoney($amount)
    {
        return sprintf("%01.2f", $amount);
    }

    public function getSimpleProductAttributesAsArray()
    {
        $product = $this->getProduct();

        $attributes['free_product'] = $product->getFreeProduct();
        $attributes['price'] = $this->_formatMoney($product->getPrice()); // test // 99.88 + (rand(0, 300));
        $attributes['final_price'] = $this->_formatMoney($product->getFinalPrice()); // test // 99.99 + (rand(0, 300));
        $attributes['json'] = $this->getUsedAttributesAsJson($product->getData());

        return $attributes;
    }
    private function _sortOptionspdp($options)
    {
		if($this->isTyre())
		{
			return $options;
		}
        if (count($options)) {
                $resource = Mage::getSingleton('core/resource');
                $read = $resource->getConnection('core_read');
                $tbl_eav_attribute_option = $resource->getTableName('eav_attribute_option');

            // Gather the option_id for all our current options
            $option_ids = array();
			$option_ids_values = array();
            foreach ($options as $index => $child) {
				if ($child->isSaleable()) {
					$child_product_id = $child->getId();
					$child = Mage::getModel('catalog/product')->load($child_product_id);
					$option_ids[] =  $child->getRimDiameterConfigurable();
					$var_name  = 'option_id_'.$child->getRimDiameterConfigurable();
					$$var_name = $child_product_id;

					if ($this->isWheel()) {
						$size_formatted = $this->_formatWheelSize($child->getAttributeText('rim_diameter_configurable'));
					}

					if ($this->isTyre()) {
						$size_formatted = $child->getAttributeText('size');
					}
					
					$option_ids_values[$var_name]['size_formatted'] = $size_formatted;
					$option_ids_values[$var_name]['index'] = $index;
					$option_ids_values[$var_name]['final_price'] = $this->_formatMoney($child->getFinalPrice());;
					$option_ids_values[$var_name]['regular_price'] = $this->_formatMoney($child->getPrice());;
					$option_ids_values[$var_name]['cdata'] = $child->getData();
				}
				
            }
            $sql = "SELECT * FROM `{$tbl_eav_attribute_option}` as a JOIN eav_attribute_option_value as b WHERE b.option_id=a.option_id AND a.option_id IN('".implode('\',\'', $option_ids)."') ORDER BY a.sort_order ASC";
            $result = $read->fetchAll($sql);
            foreach ($result as $val) {
                $var_name  = 'option_id_'.$val['option_id'];
				$index_v = $option_ids_values[$var_name]['index']; 
				
				$options->front[$index_v] = $option_ids_values[$var_name]['cdata'];
                $options->front[$index_v]['price'] = $option_ids_values[$var_name]['regular_price'];
                $options->front[$index_v]['final_price'] = $option_ids_values[$var_name]['final_price'];
                $options->front[$index_v]['size'] = $option_ids_values[$var_name]['size_formatted'];
				$options->front[$index_v]['sort'] = $val['sort_order'];
  
				$options->rear[$index_v] = $option_ids_values[$var_name]['cdata'];
                $options->rear[$index_v]['price'] = $option_ids_values[$var_name]['regular_price'];
                $options->rear[$index_v]['final_price'] = $option_ids_values[$var_name]['final_price'];
                $options->rear[$index_v]['size'] = $option_ids_values[$var_name]['size_formatted'];
				$options->rear[$index_v]['sort'] = $val['sort_order'];				
				
				#echo $val['option_id']."==".$val['value']."==".$val['sort_order']."<br>";
            }
			
			
        }

        return $options; 
    }
	
    private function _getSizeOptionsFormattedArray($children)
    {
//		if ($this->isWheel()) {
//			return $children;
//		}
        foreach ($children as $index => $child) {
            if ($child->isSaleable()) {
                $child_product_id = $child->getId();
                $child = Mage::getModel('catalog/product')->load($child_product_id);

                $cdata = $child->getData();
                $final_price = $this->_formatMoney($child->getFinalPrice()); // test // 99.99 + (rand(0, 300));
                $regular_price = $this->_formatMoney($child->getPrice()); // test // 99.88 + (rand(0, 300));

                if ($this->_isWheelOrTyre() == 1) {
                    $size_formatted = $child->getAttributeText('size');
                }else{
                    $size_formatted = $child->getAttributeText('rim_diameter_configurable');
                }

                $options->front[$index] = $cdata;
                $options->front[$index]['price'] = $regular_price;
                $options->front[$index]['final_price'] = $final_price;
                $options->front[$index]['size'] = $size_formatted;
                $options->front[$index]['sap'] = $child->getSapCode();
                $options->front[$index]['badge_text'] = $child->getAttributeText('overlay');
                $options->front[$index]['product'] = $child_product_id;
				$options->front[$index]['sort'] = 1;
				
                $options->rear[$index] = $cdata;
                $options->rear[$index]['price'] = $regular_price;
                $options->rear[$index]['final_price'] = $final_price;
                $options->rear[$index]['size'] = $size_formatted;
                $options->rear[$index]['sap'] = $child->getSapCode();
                $options->rear[$index]['badge_text'] = $child->getAttributeText('overlay');
                $options->front[$index]['product'] = $child_product_id;
				$options->rear[$index]['sort'] = 1;
            }
        }
        return $options;
    }

    private function _showAllSizeOptions()
    {
        $parent = $this->getProduct();
        $children = $parent->getTypeInstance()->getUsedProducts();
        $options = $this->_getSizeOptionsFormattedArray($children);
        return $options;
    }

    private function _getSizeOptions()
    {
        //$this->_setWheelOrTyreFromProductCategory(); // to set from product category

        if (!isset($this->_product_options_by_size)) {
            
            $filter_by = $this->_getProductOptionsThatFitChosenVehicleFromSession();
            $options = false;

            $parent = $this->getProduct();

            $children_ids = $parent->getTypeInstance()->getChildrenIds($parent->getId()); // ->getUsedProducts();
            $children = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('entity_id', array('in' => $children_ids[0]));

            if (isset($filter_by) && !is_array($children)) {
                // Filter $children collection down to filter_by products
                $children->addAttributeToFilter('sku', array('in' => $filter_by));
            }

 			if($children->count()==0)
			{
				$children = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('entity_id', array('in' => $children_ids[0]));
			}
            
			if($this->isWheel())
			{
				$children = $this->_sortOptionspdp($children);
			}
			
				$options = $this->_getSizeOptionsFormattedArray($children);
                                
        }

        if (count($options->front) > 0 && count($options->rear) > 0) {
            $this->_product_options_by_size = $options;
        } else {
            $this->_product_options_by_size = $this->_showAllSizeOptions();
        }

        if ($this->_getWheelLocation() == self::FRONT) {
            return $this->_product_options_by_size->front;
        }

        if ($this->_getWheelLocation() == self::REAR) {
            return $this->_product_options_by_size->rear;
        }

        // return those options
        return $this->_product_options_by_size;
    }

    private function _getParam($field){
        return Mage::app()->getRequest()->getParam($field);
    }

    public function getUsedAttributesAsJson($attributes_array)
    {
        // BFT-1966
        $product = $this->getProduct();
        $allAttributes = $product->getAttributes();
        foreach ($allAttributes as $attribute) {

                $attribute_code = $attribute->getAttributeCode();
                if ($attribute->getFrontendInput() == 'multiselect'){
                    $attributeText  = $product->getAttributeText($attribute_code);
                    if(is_array($attributeText)) {
                        $attributes_array[$attribute_code] = implode(" ", $attributeText);
                    }else{
                        $attributes_array[$attribute_code] = $attributeText;
                    }

                }elseif ($attribute->getFrontendInput() == 'select'){
                    $attributes_array[$attribute_code] = $product->getAttributeText($attribute_code);
                }

        }
        $attributes_array = $this->removeUnusedAttributes($attributes_array);
        $attributes_array = $this->loadAttributesOptionText($attributes_array);

        return json_encode($attributes_array);
    }
    
    public function getLowestOnSaleSize(){
        
        $parent = $this->getProduct();
    
        $children_ids = $parent->getTypeInstance()->getChildrenIds($parent->getId());
        
         $children = Mage::getModel('catalog/product')->getCollection()
                 ->addAttributeToSelect('entity_id')
                 ->addAttributeToFilter('entity_id', array('in' => $children_ids[0]))
                 ->addAttributeToFilter('overlay',712)
                 ->addAttributeToSort('price', 'asc');
         
       
        $min =  $children->getFirstItem()->getId();
        
        return $min;
    }
    
    
    public function loadAttributesOptionText($attributes)
    {
        $attributes_to_load = array(
            "durability", "superior_braking_grip", "sports_performance_handling", "quiet_comfort", "slow_ware",
            "performance", "cycling", "value"
        );

        $product = Mage::getModel('catalog/product');
        $store_id = Mage::app()->getStore()->getStoreId();

        foreach ($attributes_to_load as $attribute_code) {

            if (isset($attributes[$attribute_code])) {

                $option_id = $attributes[$attribute_code];

                // Not loading the product - just creating a simple instance
                $product
                    ->setStoreId($store_id)
                    ->setData($attribute_code, $option_id);

                $option_text = $product->getAttributeText($attribute_code);
                $attributes[$attribute_code] = $option_text;
            }
        }

        return $attributes;
    }

    public function removeUnusedAttributes($attributes)
    {
                
        $keep = array("sku", "application","width", "battery_features", "technology", "length", "volts",
            "height", "cca", "rc", "ah", "post", "brand",
            "battery_categorization", "performance", "cycling", "value");
        
       /* if ($this->isTyre()) {
            $keep = array(
                'sku','section_width', 'aspect_ratio', 'rim', 'position_wheels', 'measuring_rim', 'tread_depth',
                'overall-width', 'speed-rating', 'load-index', 'durability', 'superior_braking_grip', 'sports_performance_handling',
                'quiet_comfort', 'slow_ware',
                'performance', 'cycling', 'value');
            
            
            
            
        }*/

        if ($this->isWheel()) {
            $keep = array("sku","finish", "rim", "rim_width", "studs", "pcd", "offset", "wheel_construction", "style");
        }
        
        if ($this->isTyre()) {
            $keep = array("sku","application","position_wheels","section_width","aspect_ratio","radial_diagonal","rim_diameter","load_index","speed_rating","tread_depth","measuring_rim","other_perimissable_rims","weight","overall_width","overall_diameter","static_loaded_radius","revs_per_km");
            
                                                
        }
        $count = count($keep);
        $new = array();
        for($i=0;$i<$count;$i++) {
            $value = $attributes[$keep[$i]];
            $new[$keep[$i]] = $value;
        }
        

        return $new;
    }


    /**
     * @param $isManageStock
     * @param $stockQty
     * @return int
     */
    public function getAvailableStockQty($isManageStock,$stockQty){

        $defaultMaxQty  = 6;
        if($isManageStock && $stockQty < 1){
            $defaultMaxQty = 0;
        }else{
            if($stockQty < $defaultMaxQty && $stockQty > 0){
                $defaultMaxQty = $stockQty;
            }
        }

        return $defaultMaxQty;
    }
    
    public function getSizeArray($type) {
        
        $helper = Mage::Helper('apdinteract_searchresult');
        $series = Mage::getSingleton('core/session')->getSeriesF();
        $sizes = $helper->extractWheelSizes($series);
                
        return $this->_sanitize($sizes[$type]);;
              
    }
    
    private function _sanitize($sizes) {
        $helper = Mage::Helper('apdinteract_searchresult');
        $size_array = array();
        foreach($sizes as $size) {
            $size_array[] = $helper->sanitizeValue($size);
        }
        
        return $size_array;
    }
    
    
}