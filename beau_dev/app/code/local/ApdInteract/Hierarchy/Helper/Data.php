<?php

class ApdInteract_Hierarchy_Helper_Data extends Mage_Core_Helper_Abstract {

    private $_sortedAttributes;
    private $_hierarchy;
    private $_attributeData;
    
    private function _strToLowerNoSpaces($string) {
        // Lowercase and remove spaces
        $noise = array('-', '/', ' ', '&');
        $replace = str_replace('4', 'four', strtolower($string));
        return str_replace($noise, '', strtolower($replace));
    }

    private function _getCommercialData() {
        $options = $this->_getAttributeOptions('commercial_categorization');

        foreach ($options as $option){
            $commercialSegment[$option['label']] = array('commercial', $this->_strToLowerNoSpaces($option['label']));
        }

        return $commercialSegment;
    }

    private function _getConsumerData() {
        $options = $this->_getAttributeOptions('consumer_categorization');

        foreach ($options as $option){
            $consumerSegment[$option['label']] = array('consumer', $this->_strToLowerNoSpaces($option['label']));
        }

        return $consumerSegment;
    }

    private function _getAttributeOptions($categorization){
        $storeId   = Mage::app()->getStore()->getStoreId();
        $config    = Mage::getModel('eav/config');
        $attribute = $config->getAttribute(Mage_Catalog_Model_Product::ENTITY, $categorization);
        $values    = $attribute->setStoreId($storeId)->getSource()->getAllOptions();

        return $values;
    }

    private function _getCommercialHierarchyLookupData($commercial_categorization) {
        $commercialSegment = $this->_getCommercialData();
        return $this->_getHierarchyLookupData($commercial_categorization, $commercialSegment);
    }

    private function _getConsumerHierarchyLookupData($consumer_categorization) {
        $consumerSegment = $this->_getConsumerData();
        return $this->_getHierarchyLookupData($consumer_categorization, $consumerSegment);
    }

    private function _getHierarchyLookupData($categorization, $segment) {
        $key = $categorization;
        if (!empty($key) && array_key_exists($key, $segment)) {
            $hierarchy_lookup = $segment[$key];
            return $hierarchy_lookup;
        }

        return false;
    }

    public function getSortedAttributes($attribute_values, $product) {

        $commercial_category = $attribute_values['commercial_categorization'];
        $consumer_category = $attribute_values['consumer_categorization'];
       
        if (!isset($this->_sortedAttributes[$commercial_category][$consumer_category])) {
         
            $sortedAttributes = false;
            
            $hierarchy_lookup = $this->_getCommercialHierarchyLookupData($commercial_category);
            if (!$hierarchy_lookup) {
                $hierarchy_lookup = $this->_getConsumerHierarchyLookupData($consumer_category);
            }

            if ($hierarchy_lookup) {
                $sortedAttributes = $this->getHierarchy($hierarchy_lookup[0], $hierarchy_lookup[1]);
            }
            $this->_sortedAttributes[$commercial_category][$consumer_category] = $sortedAttributes;
        }

        return $this->_sortedAttributes[$commercial_category][$consumer_category];
    }

    public function getHierarchy($segment, $categorization) {
        $flat = array();
        $sortedAttributes = array();
        $attributes = array_keys($this->loadAttributeData()); //array('durability', 'even_ware', 'fuel_saver', 'superior_braking_grip', 'handling', 'mileage', 'sports_performance_handling', 'quiet_comfort_comfort');
        foreach ($attributes as $attribute) {
            $value = (int) Mage::getStoreConfig($segment . '/' . $categorization . '/' . $attribute);
            if ($value != null) {
                $sortedAttributes[$value] = array($attribute => $value);
            }
        }
        ksort($sortedAttributes);
        
        foreach ($sortedAttributes as $array) {
            $flat[] = key($array);
        }
        
        return $flat;
    }

    
    public function getAttributeText($attribute_key) {
        return $this->_getAttributeData($attribute_key, 0);
    }
    
    public function getAttributeIcon($attribute_key) {
        return $this->_getAttributeData($attribute_key, 1);
    }
    
    public function getAttributeCode($attribute_key) {
    	$data = $this->loadAttributeData();
    	if (array_key_exists($attribute_key, $data)) {
    		return $attribute_key;
    	}
    }
    
    public function _getAttributeData($attribute_key, $index) {
        $data = $this->loadAttributeData();
        if (array_key_exists($attribute_key, $data)) {
            return $data[$attribute_key][$index];
        }
    }
    
    public function loadAttributeData() {

//        Archived - some of these aren't used yet
//        $durabilityIcon = 'icon-bftdurability';
//        $superiorIcon = 'icon-bftgrip';
//        $sportIcon = 'icon-bfthandling';
//        $quietIcon = 'icon-bftcomfort';
//        $fuelIcon = 'icon-bftfuelsaver';
//        $mileageIcon = 'icon-bftmileage';
//        $handlingIcon = 'icon-bfthandling';
//        $gripIcon = 'icon-bftgrip';
//        $offIcon = 'icon-bftoffroad';
//        $valueIcon = 'icon-bftvalue';
//        $slowIcon = 'icon-bftslowwear';
//        $evenIcon = 'icon-bftevenwear';
//        $performanceIcon = 'icon-bftperformance';
//        $cyclingIcon = 'icon-bftcyclingability';
//        $tyreIcon = 'icon-bfttyre';
        
        $storeCode = Mage::app()->getStore()->getCode();
        
        if ($storeCode == 'default'): // check if default
            
            if (!isset($this->_attributeData)):
                $this->_attributeData = array(
                    'durability' => array('Durability (toughness)', 'fa fa-2x icon-bftdurability'),
                    'even_ware' => array('Even Wear', 'fa fa-2x icon-bftevenwear'),
                    'fuel_saver' => array('Fuel Saver', 'fa fa-2x icon-bftfuelsaver'),
                    'superior_braking_grip' => array('Superior Braking + Grip', 'fa fa-2x icon-bftgrip'),
                    'handling' => array('Handling', 'fa fa-2x icon-bfthandling'),
                    'mileage' => array('Mileage', 'fa fa-2x icon-bftmileage'),
                    'sports_performance_handling' => array('Sports Performance & Handling', 'fa fa-2x icon-bfthandling'),
                    'performance' => array('Performance', 'fa fa-2x icon-bftperformance'),
                    'quiet_comfort' => array('Quiet', 'fa fa-2x icon-bftcomfort'),
                    'quiet' => array('Quiet', 'icon2 icon-2x icon-low-noise'),
                    'cycling' => array('Cycling Ability', 'fa fa-2x icon-bftcyclingability'),
                    'sports_performance_handling' => array('Sports Performance & Handling', 'fa fa-2x icon-sbftperformance'),
                    // for batteries only
                    'durability_battery' => array('Durability', 'fa fa-2x icon-bftdurability'),
                    'performance_battery' => array('Performance', 'fa fa-2x icon-bftperformance'),
                    'value_battery' => array('Value', 'icon2 icon-2x icon-value-battery'),
                    'cycling_battery' => array('Cycling', 'fa fa-2x icon-bftcyclingability'),
                    // new icons and attributes
                    'fuel_economy' => array('Fuel Economy', 'icon2 icon-2x icon-fuel-economy'),
                    'grip_performance' => array('Grip Performance', 'icon2 icon-2x icon-grip-performance'),
                    'grip' => array('Grip', 'icon2 icon-2x icon-grip-performance'),
                    'excellent_handling' => array('Handling', 'icon2 icon-2x icon-handling-performance'),
                    'handling_performance' => array('Handling Performance', 'icon2 icon-2x icon-handling-performance'),
                    'off_road_performance' => array('Off Road Performance', 'icon2 icon-2x icon-offroad-performance'),
                    'wet_performance' => array('Wet Performance', 'icon2 icon-2x icon-wet-performance'),
                    'low_noise' => array('Low Noise', 'icon2 icon-2x icon-low-noise'),
                    'comfort_performance' => array('Comfort Performance', 'icon2 icon-2x icon-comfort-performance'),
                    'sports_performance' => array('Sports Performance', 'icon2 icon-2x icon-sports-performance'),
                    'braking_performance' => array('Braking Performance', 'icon2 icon-2x icon-breaking-performance'),
                    'value' => array('Value', 'icon2 icon-2x icon-value-tyre'),
                    'even_wear' => array('Even Wear', 'fa fa-2x icon-bftevenwear'),
                    'dry_performance' => array('Dry Performance', 'icon2 icon-2x icon-dry-performance'),
                    'wet_dry_performance' => array('Wet Dry Performance', 'icon2 icon-2x icon-wetdry-performance'),
                    'all_terrain' => array('All Terrain', 'icon2 icon-2x icon-all-terrain'),
                    'winter' => array('Winter', 'icon2 icon-2x icon-winter-tyres'),
                    'original_equipment' => array('Original Equipment', 'icon2 icon-2x icon-original-equipment'),
                    'run_on_flat' => array('Run On Flat', 'icon2 icon-2x icon-run-on-flat'),
                    'traction' => array('Traction', 'icon2 icon-2x icon-traction-tyres'),
                    'cornering_performance' => array('Cornering Performance', 'icon2 icon-2x icon-cornering-performance'),
                    'road_hazard_resistant' => array('Road Hazard Resistant', 'icon2 icon-2x icon-road-hazard-resistant'),
                    'slow_wear' => array('Slow Wear', 'icon2 icon-2x icon-slow-wear'),
                );
            endif;

        else:
            if (!isset($this->_attributeData)):                
                $this->_attributeData = array(
                    'durability' => array('Durability (toughness)', 'icon2 icon-2x icon-durability'),
                    'even_ware' => array('Even Wear', 'icon2 icon-2x icon-even-wear'),
                    'fuel_saver' => array('Fuel Saver', 'icon2 icon-2x icon-fuel-economy'),
                    'superior_braking_grip' => array('Superior Braking + Grip', 'icon-grip'),
                    'superior_grip' => array('Superior Braking + Grip', 'icon2 icon-2x icon-grip-performance'),
                    'excellent_handling' => array('Handling', 'icon2 icon-2x icon-handling-performance'),
                    'mileage' => array('Mileage', 'icon2 icon-2x icon-mileage'),
                    'sports_performance_handling' => array('Sports Performance & Handling', 'icon2 icon-2xicon-handling'),
                    'performance' => array('Performance', 'icon2 icon-2x icon-performance'),
                    'quiet_comfort' => array('Quiet and Comfort', 'icon2 icon-2x icon-comfort'),
                    'quiet' => array('Quiet', 'icon2 icon-2x icon-low-noise'),
                    'cycling' => array('Cycling Ability', 'icon2 icon-2x icon-cyclingability'),
                    'sports_performance_handling' => array('Sports Performance & Handling', 'icon2 icon-2x icon-sportperformance'),
                    // for batteries only
                    'durability_battery' => array('Durability', 'icon2 icon-2x icon-durability'),
                    'performance_battery' => array('Performance', 'icon2 icon-2x icon-performance'),
                    'value_battery' => array('Value', 'icon2 icon-2x icon-value'),
                    'cycling_battery' => array('Cycling', 'icon2 icon-2x icon-cyclingability'),
                    // new icons and attributes
                    'fuel_economy' => array('Fuel Economy', 'icon2 icon-2x icon-fuel-economy'),
                    'grip_performance' => array('Grip Performance', 'icon2 icon-2x icon-grip-performance'),
                    'grip' => array('Grip', 'icon2 icon-2x icon-grip-performance'),
                    'handling_performance' => array('Handling Performance', 'icon2 icon-2x icon-handling-performance'),
                    'handling' => array('Handling', 'icon2 icon-2x icon-handling-performance'),
                    'off_road_performance' => array('Off Road Performance', 'icon2 icon-2x icon-offroad-performance'),
                    'wet_performance' => array('Wet Performance', 'icon2 icon-2x icon-wet-performance'),
                    'low_noise' => array('Low Noise', 'icon2 icon-2x icon-low-noise'),
                    'comfort_performance' => array('Comfort Performance', 'icon2 icon-2x icon-comfort-performance'),
                    'sports_performance' => array('Sports Performance', 'icon2 icon-2x icon-sport-performance'),
                    'braking_performance' => array('Braking Performance', 'icon2 icon-2x icon-braking-performance'),
                    'value' => array('Value', 'icon2 icon-2x icon-value'),
                    'even_wear' => array('Even Wear', 'icon-even-wear'),
                    'dry_performance' => array('Dry Performance', 'icon2 icon-2x icon-dry-performance'),
                    'wet_dry_performance' => array('Wet Dry Performance', 'icon2 icon-2x icon-wetdry-performance'),
                    'all_terrain' => array('All Terrain', 'icon2 icon-2x icon-all-terrain'),
                    'winter' => array('Winter', 'icon2 icon-2x icon-winter'),
                    'original_equipment' => array('Original Equipment', 'icon2 icon-2x icon-original-equipment'),
                    'run_on_flat' => array('Run On Flat', 'icon2 icon-2x icon-rof'),
                    'traction' => array('Traction', 'icon2 icon-2x icon-traction'),
                    'cornering_performance' => array('Cornering Performance', 'icon2 icon-2x icon-cornering-performance'),
                    'road_hazard_resistant' => array('Road Hazard Resistant', 'icon2 icon-2x icon-road-hazard-resistant'),
                    'slow_wear' => array('Slow Wear', 'icon2 icon-2x icon-slow-wear'),
                );
            endif;

        endif;

        return $this->_attributeData;
    }
    
    private function _loadAttributeData() {
        
        

//        Archived - some of these aren't used yet
//        $durabilityIcon = 'icon-bftdurability';
//        $superiorIcon = 'icon-bftgrip';
//        $sportIcon = 'icon-bfthandling';
//        $quietIcon = 'icon-bftcomfort';
//        $fuelIcon = 'icon-bftfuelsaver';
//        $mileageIcon = 'icon-bftmileage';
//        $handlingIcon = 'icon-bfthandling';
//        $gripIcon = 'icon-bftgrip';
//        $offIcon = 'icon-bftoffroad';
//        $valueIcon = 'icon-bftvalue';
//        $slowIcon = 'icon-bftslowwear';
//        $evenIcon = 'icon-bftevenwear';
//        $performanceIcon = 'icon-bftperformance';
//        $cyclingIcon = 'icon-bftcyclingability';
//        $tyreIcon = 'icon-bfttyre';
        
        $storeCode = Mage::app()->getStore()->getCode();
        
        if ($storeCode == 'default'): // check if default
            
            if (!isset($this->_attributeData)):
                $this->_attributeData = array(
                    'durability' => array('Durability (toughness)', 'fa fa-2x icon-bftdurability'),
                    'even_ware' => array('Even Wear', 'fa fa-2x icon-bftevenwear'),
                    'fuel_saver' => array('Fuel Saver', 'fa fa-2x icon-bftfuelsaver'),
                    'superior_braking_grip' => array('Superior Braking + Grip', 'fa fa-2x icon-bftgrip'),
                    'handling' => array('Handling', 'fa fa-2x icon-bfthandling'),
                    'mileage' => array('Mileage', 'fa fa-2x icon-bftmileage'),
                    'sports_performance_handling' => array('Sports Performance & Handling', 'fa fa-2x icon-bfthandling'),
                    'performance' => array('Performance', 'fa fa-2x icon-bftperformance'),
                    'quiet_comfort' => array('Quiet', 'fa fa-2x icon-bftcomfort'),
                    'quiet' => array('Quiet', 'icon2 icon-2x icon-low-noise'),
                    'cycling' => array('Cycling Ability', 'fa fa-2x icon-bftcyclingability'),
                    'sports_performance_handling' => array('Sports Performance & Handling', 'fa fa-2x icon-sbftperformance'),
                    // for batteries only
                    'durability_battery' => array('Durability', 'fa fa-2x icon-bftdurability'),
                    'performance_battery' => array('Performance', 'fa fa-2x icon-bftperformance'),
                    'value_battery' => array('Value', 'icon2 icon-2x icon-value-battery'),
                    'cycling_battery' => array('Cycling', 'fa fa-2x icon-bftcyclingability'),
                    // new icons and attributes
                    'fuel_economy' => array('Fuel Economy', 'icon2 icon-2x icon-fuel-economy'),
                    'grip_performance' => array('Grip Performance', 'icon2 icon-2x icon-grip-performance'),
                    'grip' => array('Grip', 'icon2 icon-2x icon-grip-performance'),
                    'excellent_handling' => array('Handling', 'icon2 icon-2x icon-handling-performance'),
                    'handling_performance' => array('Handling Performance', 'icon2 icon-2x icon-handling-performance'),
                    'off_road_performance' => array('Off Road Performance', 'icon2 icon-2x icon-offroad-performance'),
                    'wet_performance' => array('Wet Performance', 'icon2 icon-2x icon-wet-performance'),
                    'low_noise' => array('Low Noise', 'icon2 icon-2x icon-low-noise'),
                    'comfort_performance' => array('Comfort Performance', 'icon2 icon-2x icon-comfort-performance'),
                    'sports_performance' => array('Sports Performance', 'icon2 icon-2x icon-sports-performance'),
                    'braking_performance' => array('Braking Performance', 'icon2 icon-2x icon-breaking-performance'),
                    'value' => array('Value', 'icon2 icon-2x icon-value-tyre'),
                    'even_wear' => array('Even Wear', 'fa fa-2x icon-bftevenwear'),
                    'dry_performance' => array('Dry Performance', 'icon2 icon-2x icon-dry-performance'),
                    'wet_dry_performance' => array('Wet Dry Performance', 'icon2 icon-2x icon-wetdry-performance'),
                    'all_terrain' => array('All Terrain', 'icon2 icon-2x icon-all-terrain'),
                    'winter' => array('Winter', 'icon2 icon-2x icon-winter-tyres'),
                    'original_equipment' => array('Original Equipment', 'icon2 icon-2x icon-original-equipment'),
                    'run_on_flat' => array('Run On Flat', 'icon2 icon-2x icon-run-on-flat'),
                    'traction' => array('Traction', 'icon2 icon-2x icon-traction-tyres'),
                    'cornering_performance' => array('Cornering Performance', 'icon2 icon-2x icon-cornering-performance'),
                    'road_hazard_resistant' => array('Road Hazard Resistant', 'icon2 icon-2x icon-road-hazard-resistant'),
                    'slow_wear' => array('Slow Wear', 'icon2 icon-2x icon-slow-wear'),
                );
            endif;

        else:
            if (!isset($this->_attributeData)):                
                $this->_attributeData = array(
                    'durability' => array('Durability (toughness)', 'icon2 icon-2x icon-durability'),
                    'even_ware' => array('Even Wear', 'icon2 icon-2x icon-even-wear'),
                    'fuel_saver' => array('Fuel Saver', 'icon2 icon-2x icon-fuel-economy'),
                    'superior_braking_grip' => array('Superior Braking + Grip', 'icon-grip'),
                    'superior_grip' => array('Superior Braking + Grip', 'icon2 icon-2x icon-grip-performance'),
                    'excellent_handling' => array('Handling', 'icon2 icon-2x icon-handling-performance'),
                    'mileage' => array('Mileage', 'icon2 icon-2x icon-mileage'),
                    'sports_performance_handling' => array('Sports Performance & Handling', 'icon2 icon-2xicon-handling'),
                    'performance' => array('Performance', 'icon2 icon-2x icon-performance'),
                    'quiet_comfort' => array('Quiet and Comfort', 'icon2 icon-2x icon-comfort'),
                    'quiet' => array('Quiet', 'icon2 icon-2x icon-low-noise'),
                    'cycling' => array('Cycling Ability', 'icon2 icon-2x icon-cyclingability'),
                    'sports_performance_handling' => array('Sports Performance & Handling', 'icon2 icon-2x icon-sportperformance'),
                    // for batteries only
                    'durability_battery' => array('Durability', 'icon2 icon-2x icon-durability'),
                    'performance_battery' => array('Performance', 'icon2 icon-2x icon-performance'),
                    'value_battery' => array('Value', 'icon2 icon-2x icon-value'),
                    'cycling_battery' => array('Cycling', 'icon2 icon-2x icon-cyclingability'),
                    // new icons and attributes
                    'fuel_economy' => array('Fuel Economy', 'icon2 icon-2x icon-fuel-economy'),
                    'grip_performance' => array('Grip Performance', 'icon2 icon-2x icon-grip-performance'),
                    'grip' => array('Grip', 'icon2 icon-2x icon-grip-performance'),
                    'handling_performance' => array('Handling Performance', 'icon2 icon-2x icon-handling-performance'),
                    'handling' => array('Handling', 'icon2 icon-2x icon-handling-performance'),
                    'off_road_performance' => array('Off Road Performance', 'icon2 icon-2x icon-offroad-performance'),
                    'wet_performance' => array('Wet Performance', 'icon2 icon-2x icon-wet-performance'),
                    'low_noise' => array('Low Noise', 'icon2 icon-2x icon-low-noise'),
                    'comfort_performance' => array('Comfort Performance', 'icon2 icon-2x icon-comfort-performance'),
                    'sports_performance' => array('Sports Performance', 'icon2 icon-2x icon-sport-performance'),
                    'braking_performance' => array('Braking Performance', 'icon2 icon-2x icon-braking-performance'),
                    'value' => array('Value', 'icon2 icon-2x icon-value'),
                    'even_wear' => array('Even Wear', 'icon-even-wear'),
                    'dry_performance' => array('Dry Performance', 'icon2 icon-2x icon-dry-performance'),
                    'wet_dry_performance' => array('Wet Dry Performance', 'icon2 icon-2x icon-wetdry-performance'),
                    'all_terrain' => array('All Terrain', 'icon2 icon-2x icon-all-terrain'),
                    'winter' => array('Winter', 'icon2 icon-2x icon-winter'),
                    'original_equipment' => array('Original Equipment', 'icon2 icon-2x icon-original-equipment'),
                    'run_on_flat' => array('Run On Flat', 'icon2 icon-2x icon-rof'),
                    'traction' => array('Traction', 'icon2 icon-2x icon-traction'),
                    'cornering_performance' => array('Cornering Performance', 'icon2 icon-2x icon-cornering-performance'),
                    'road_hazard_resistant' => array('Road Hazard Resistant', 'icon2 icon-2x icon-road-hazard-resistant'),
                    'slow_wear' => array('Slow Wear', 'icon2 icon-2x icon-slow-wear'),
                );
            endif;

        endif;

        return $this->_attributeData;
    }
    
    /* This will provide the class name of the filter
     * 
     * @return array
     */ 
    public function checkClass($attribute) {
        
        $data = $this->_loadAttributeData();        
        return (isset($data[$attribute]))  ? $data[$attribute] : array();
        
    }

}