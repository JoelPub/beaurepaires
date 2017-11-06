<?php

/**
 * ApdInteract_Salesforce_Helper_Mapper_Product
 * 
 * 
 * @author hyan
 *
 */
class ApdInteract_Salesforce_Helper_Mapper_Product extends ApdInteract_Salesforce_Helper_Core_Mapper_Abstract {

    /**
     * 
     */
    public function __construct() {
        $this->protocol = array(
            "name" => "Name",
            "ah" => "AH__c",
            "aspect_ratio" => "aspectratio__c",
            "caliper_profile" => "caliperprofile__c",
            "cca" => "CCA__c",
            "compliance" => "compliance__c",
            "construction" => "construction__c",
            "finish" => "Finish__c",
            "front" => "front__c",
            "gift_wrapping_price" => "giftwrappingprice__c",
            "height" => "Height__c",
            "hub_diameter" => "hubdiameter__c",
            "length" => "Length__c",
            "load_index" => "loadindex__c",
            "mac" => "mac__c",
            "marking" => "marking__c",
            "measuring_rim" => "Measureing_Rim__c",
            "min_qty" => "minqty__c",
            "model" => "model__c",
            "msrp" => "msrp__c",
            "mwl" => "mwl__c",
            "nominal_od" => "Nominal_Od__c",
            "oe" => "OE__c",
            "offset" => "offset__c",
            "other_perimissable_rims" => "Other_Perimissable_Rims__c",
            "overall_diameter" => "Overall_dia__c",
            "overall_width" => "Overall_Width__c",
            "pcd" => "PCD__c",
            "pcd_alternate" => "pcdalternate__c",
            "ply_rating" => "plyrating__c",
            "position_wheels" => "Position_Wheels__c",
            "post" => "Post__c",
            "predecessor_mac" => "predecessormac__c",
            "price" => "price__c",
            "qty" => "qty__c",
            "qty_increments" => "qtyincrements__c",
            "radial_diagonal" => "radialdiagonal__c",
            "rc" => "RC__c",
            "rear" => "rear__c",
            "revs_per_km" => "revsperkm__c",
            "rim" => "rim__c",
            "rim_width" => "rimwidth__c",
            "rim_diameter" => "Rim_Diameter__c",
            "run_flat" => "Run_Flat__c",
            "section_width" => "sectionwidth__c",
            "service_description" => "servicedescription__c",
            "side_wall_lettering" => "sidewalllettering__c",
            "size" => "size__c",
            "sku" => "SKU__c",
            "special_price" => "specialprice__c",
            "speed_load_rating" => "speedloadrating__c",
            "speed_rating" => "speedrating__c",
            "static_loaded_radius" => "staticloadedradius__c",
            "stud_pattern" => "studpattern__c",
            "stud_pattern_alternate" => "studpatternalternate__c",
            "studs" => "studs__c",
            "studs_alternate" => "studsalternate__c",
            "style" => "Style__c",
            "taper" => "taper__c",
            "tax_class_id" => "taxclassid__c",
            "technology" => "Technology__c",
            "tread_depth" => "treaddepth__c",
            "tube_type" => "tubetype__c",
            "type_id" => "Type__c",
            "url_key" => "url_key__c",
            "vimeo_video_id" => "vimeovideoid__c",
            "volts" => "Volts__c",
            "wheel_manufacture" => "wheelmanufacture__c",
            "wheel_construction" => "Wheel_Construction__c",
            "width" => "Width__c",
            "meta_title"=>"meta_title__c",
            "meta_keyword"=>"meta_keyword__c",
            "meta_description"=>"meta_description__c"
        );
    }

    /**
     * 
     * 
     * @param Mage_Customer_Model_Customer $input
     */
    public function map($input) {
        $result = parent::map($input);
        //date date("c",strtotime($input['inspectiondate']));
        $mainProduct = Mage::getModel("catalog/product")->load($input->getId());
        $result = $this->_assignTextValues($mainProduct);
        $result['SAP_Code__c'] = $mainProduct->getSapCode();
        $result['allterrain__c'] = $mainProduct->getData("all_terrain");
        $result['Description'] = ($mainProduct->getData("description") ? substr($mainProduct->getData("description"), 0, 3999) : '');
        $result['Finish__c'] = $mainProduct->getData("finish");
        $result['Magento_ID__c'] = $mainProduct->getId();
        $result['ProductCode'] = $mainProduct->getSku();
        $result['warranty__c'] = ($mainProduct->getData("warranty") ? substr($mainProduct->getData("warranty"), 0, 3999) : '');
        $result['warrantyextended__c'] = ($mainProduct->getData("warranty_extended") ? substr($mainProduct->getData("warranty_extended"), 0, 3999) : '');
        $result['warrantystandard__c'] = ($mainProduct->getData("warranty_standard") ? substr($mainProduct->getData("warranty_standard"), 0, 3999) : '');

        //$result['DisplayUrl'] = Mage::helper("apdwidgets")->getFullProductUrl($mainProduct);
        $parent = $this->_getParentProduct($mainProduct);
        if(isset($parent) && trim($parent)!='' && trim($parent)!='0')
        $result['Parent_Product__c'] = $parent;
        
        $result['Image__c'] = $this->_getImagePath($input->getImage());
        $result['shortdescription__c'] = ($mainProduct->getData("short_description") ? substr($mainProduct->getData("short_description"), 0, 3999) : '');


        //boolean        
        $result['IsActive'] = $this->_getBooleanStatus($mainProduct->getData("status"));
        $result['status__c'] = $this->_getStatus($mainProduct->getData("status"));
        $result['stockstatuschangedautomatically__c'] = $this->_getBooleanValue($mainProduct->getData("stock_status_changed_automatically"));
        $result['isdecimaldivided__c'] = $this->_getBooleanValue($mainProduct->getData("is_decimal_divided"));
        $result['isinstock__c'] = $this->_getBooleanValue($mainProduct->getData("is_in_stock"));
        $result['useconfigenableqtyinc__c'] = $this->_getBooleanValue($mainProduct->getData("use_config_enable_qty_inc"));
        $result['useconfigenableqtyincrements__c'] = $this->_getBooleanValue($mainProduct->getData("use_config_qty_increments"));
        $result['useconfigmanagestock__c'] = $this->_getBooleanValue($mainProduct->getData("use_config_manage_stock"));
        $result['useconfigminqty__c'] = $this->_getBooleanValue($mainProduct->getData("use_config_min_qty"));
        $result['useconfignotifystockqty__c'] = $this->_getBooleanValue($mainProduct->getData("use_config_notify_stock_qty"));
        $result['notifystockqty__c'] = $this->_getBooleanValue($mainProduct->getData("notify_stock_qty"));
        $result['noupdatebywiser__c'] = $this->_getBooleanValue($mainProduct->getData("noupdatebywiser"));
        $result['originalequipment__c'] = $this->_getBooleanValue($mainProduct->getData("original_equipment"));
        $result['managestock__c'] = $this->_getBooleanValue($mainProduct->getData("manage_stock"));
        $result['enableqtyincrements__c'] = $this->_getBooleanValue($mainProduct->getData("enable_qty_increments"));


        //dates
        $result['lowstockdate__c'] = date("c", strtotime($mainProduct->getData("low_stock_date")));
        $result['newsfromdate__c'] = date("c", strtotime($mainProduct->getData("news_from_date")));
        ;
        $result['newstodate__c'] = date("c", strtotime($mainProduct->getData("news_to_date")));
        $result['specialfromdate__c'] = date("c", strtotime($mainProduct->getData("special_from_date")));
        $result['specialtodate__c'] = date("c", strtotime($mainProduct->getData("special_to_date")));
        $result['customdesignfrom__c'] = date("c", strtotime($mainProduct->getData("custom_design_from")));
        $result['customdesignto__c'] = date("c", strtotime($mainProduct->getData("custom_design_to")));


        //attribute text        
        $result['allterrain__c'] = $this->_setValue($mainProduct->getAttributeText("all_terrain"));
        $result['application__c'] = $this->_setValue($mainProduct->getAttributeText("application"));
        $result['brakingperformance__c'] = $this->_setValue($mainProduct->getAttributeText("braking_performance"));
        $result['brand__c'] = $this->_setValue($mainProduct->getAttributeText("brand"));
        $result['categorymain__c'] = $this->_setValue(implode(",", $mainProduct->getAttributeText("category_main")));
        $result['comfortperformance__c'] = $this->_setValue($mainProduct->getAttributeText("comfort_performance"));
        $result['commercialcategorization__c'] = $this->_setValue($mainProduct->getAttributeText("commercial_categorization"));
        $result['consumercategorization__c'] = $this->_setValue($mainProduct->getAttributeText("consumer_categorization"));
        $result['corneringperformance__c'] = $this->_setValue($mainProduct->getAttributeText("cornering_performance"));
        $result['customersegment__c'] = $this->_setValue($mainProduct->getAttributeText("customer_segment"));
        $result['cyclingbattery__c'] = $this->_setValue($mainProduct->getAttributeText("cycling_battery"));
        $result['drivingstyle__c'] = $this->_setValue(implode(",", $mainProduct->getAttributeText("driving_style")));
        $result['dryperformance__c'] = $this->_setValue($mainProduct->getAttributeText("dry_performance"));
        $result['durability__c'] = $this->_setValue($mainProduct->getAttributeText("durability"));
        $result['durabilitybattery__c'] = $this->_setValue($mainProduct->getAttributeText("durability_battery"));
        $result['evenwear__c'] = $this->_setValue($mainProduct->getAttributeText("even_wear"));
        $result['fueleconomy__c'] = $this->_setValue($mainProduct->getAttributeText("fuel_economy"));
        $result['gripperformance__c'] = $this->_setValue($mainProduct->getAttributeText("grip_performance"));
        $result['handlingperformance__c'] = $this->_setValue($mainProduct->getAttributeText("handling_performance"));
        $result['offroadperformance__c'] = $this->_setValue($mainProduct->getAttributeText("off_road_performance"));
        $result['lownoise__c'] = $this->_setValue($mainProduct->getAttributeText("low_noise"));
        $result['mileage__c'] = $this->_setValue($mainProduct->getAttributeText("mileage"));
        $result['overlay__c'] = $this->_setValue($mainProduct->getAttributeText("overlay"));
        $result['rimdiameterconfigurable__c'] = $this->_setValue($mainProduct->getAttributeText("rim_diameter_configurable"));
        $result['roadhazardresistant__c'] = $this->_setValue($mainProduct->getAttributeText("road_hazard_resistant"));
        $result['runonflat__c'] = $this->_setValue($mainProduct->getAttributeText("run_on_flat"));
        $result['slowwear__c'] = $this->_setValue($mainProduct->getAttributeText("slow_wear"));
        $result['traction__c'] = $this->_setValue($mainProduct->getAttributeText("traction"));
        $result['value__c'] = $this->_setValue($mainProduct->getAttributeText("value"));
        $result['sportsperformance__c'] = $this->_setValue($mainProduct->getAttributeText("sports_performance"));
        $result['valuebattery__c'] = $this->_setValue($mainProduct->getAttributeText("value_battery"));
        $result['wetdryperformance__c'] = $this->_setValue($mainProduct->getAttributeText("wet_dry_performance"));
        $result['wetperformance__c'] = $this->_setValue($mainProduct->getAttributeText("wet_performance"));
        $result['winter__c'] = $this->_setValue($mainProduct->getAttributeText("winter"));
        $result['performancebattery__c'] = $this->_setValue($mainProduct->getAttributeText("performance_battery"));
        $result['batterycategorization__c'] = $this->_setValue($mainProduct->getAttributeText("battery_categorization"));
        $result['batteryfeatures__c'] = $this->_setValue(implode(",", $mainProduct->getAttributeText("battery_features")));        
        $result['Custom_Options__c'] = $this->_getOptions($mainProduct);
        $result['Website__c'] = $this->getWebsites($mainProduct->getWebsiteIds());
        
        $result['Visibility__c'] = $mainProduct->getVisibility();
        $result['Category__c'] = $this->_getAllCategories($mainProduct);
        $result['Feature_1__c'] = $mainProduct->getFeature1();
        $result['Feature_2__c'] = $mainProduct->getFeature2();
        $result['Feature_3__c'] = $mainProduct->getFeature3();
        $result['Feature_4__c'] = $mainProduct->getFeature4();
        $result['Feature_5__c'] = $mainProduct->getFeature5();
        $result['Feature_6__c'] = $mainProduct->getFeature6();
        $result['Comment__c'] = $mainProduct->getComment();
        $result['Enable_RMA__c'] = $mainProduct->getIsReturnable();
        $result['newsfromdate__c'] = date("c", strtotime($mainProduct->getData("news_from_date")));
        $result['newstodate__c'] = date("c", strtotime($mainProduct->getData("news_to_date")));
        $result['Search_Weight__c'] = $mainProduct->getSearchindexWeight();
        $result['Apply_MAP__c'] = $mainProduct->getMsrpEnabled();  
        $result['Display_Actual_Price__c'] = $this->_getDisplayActualPrice($mainProduct->getData('msrp_display_actual_price_type')); 
        $result['Manufacturer_s_Suggested_Retail_Price__c'] = $mainProduct->getData('msrp');
        $result['Set_Price_to_manual_update__c'] = $this->_getRma($mainProduct->getData('free_product'));
        $result['Country_of_Manufacture__c'] = $mainProduct->getData('country_of_manufacture');
        $result['Sort_Order__c'] = $mainProduct->getData('sort_order');
        $result['Measuring_Rim__c'] = $mainProduct->getData('measuring_rim');
        
        return $result;
    }

    /**
     * 
     * @param object $input
     */
    protected function _getParentProduct($input) {
        $parent = null;
        if ($input->getTypeId() == 'simple'):
            $class = get_class(Mage::getModel("catalog/product"));
            $config_product_id = Mage::getModel('catalog/product_type_configurable')
                    ->getParentIdsByChild($input->getId());
            $id = isset($config_product_id[0]) ? $config_product_id[0] : '';
            $parent = Mage::helper('apdinteract_salesforce')->getSFId($id, $class);
        endif;
        return $parent;
    }

    protected function _getImagePath($image) {
        $baseImageUrl = "";
        if ($image != ''):
            $productMediaConfig = Mage::getModel('catalog/product_media_config');
            $baseImageUrl = $productMediaConfig->getMediaUrl($image);
        endif;
        return $baseImageUrl;
    }

    private function _getBooleanValue($input) {

        $result = $input ? true : false;
        return $result;
    }

    private function _getStatus($input) {

        $result = $input == 1? 'Enabled' : 'None';
        return $result;
    }
    
    private function _getBooleanStatus($input) {
        $result = $input == 1? true : false;
        return $result;
    }

    private function _setValue($input) {
        return $input ? $input : "";
    }

    private function _assignTextValues($product) {
        $result = array();
        foreach ($this->protocol as $value => $key) {
            $result[$key] = ($product->getData($value)=='N/A' || $product->getData($value)=='n/a')? '' : $product->getData($value);
        }

        return $result;
    }
    
    public function _getOptions($_product) {
        foreach ($_product->getOptions() as $o):

            $optionType = $o->getType();

            $values = '';
            $v = $o->getData();
            $default_title = $o->default_title;
            $option_type_id = $v['option_type_id'];
            $option_id = $v['option_id'];
            $title = $v['title'];
            $price = $v['price'];
            $sku = $v['sku'];

            $option_str .= $sku . '-' . $title . ':' . $price . ",";
            

        endforeach;

        return $option_str;
    }
    public function getWebsites($ids) {
        $data= $this->getAllWebsites();
        $c = count($ids);
        $web = "";
        for($i=0;$i<$c; $i++):            
            $web .=$data[$ids[$i]].",";
        endfor;
        
        return $web;
        
    }
    
    public function getAllWebsites() {
        $array=array();
        foreach (Mage::app()->getWebsites() as $website) {
            $array[$website->getId()] = $website->getName();            
        }
        
       
        return $array;
        
    }
    
    private function _getVisibility($visibility) {
        $visibilities = array(1 => "Not Visible Individually", 2 => "Catalog", 3 => "Search", 4 => "Catalog, Search");
        return $visibilities[$visibility];
    }
    
    private function _getAllCategories($_product) {
        $categoryIds = $_product->getCategoryIds();
        $categories = "";
        foreach($categoryIds as $categoryId) {
           $_category = Mage::getModel('catalog/category')->load($categoryId); 
           $categories .= $_category->getName().', ';
        }
        
        return $categories;
    }
    
    private function _getRma($rma) {
        $rmas = array(0 => "No", 1 => "Yes", 2 => "Use config");
        return $rmas[$rma];
    }
    
    private function _getDisplayActualPrice($dap) {
        $daps = array(2 => "In Cart", 3 => "Before Order Confirmation", 1 => "On Gesture", 4=> "Use Config");
        return $daps[$dap];
    }

}
