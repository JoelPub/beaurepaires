<?php 
class ApdInteract_SearchResult_Block_Layer_Filter_Attribute extends Mage_Catalog_Block_Layer_Filter_Attribute
{
    public function __construct()
    {
        parent::__construct();
        $this->_filterModelName = 'apdinteract_searchresult/layer_filter_attribute';
    }
    
     public function getNewIconClass($attribute,$filter)
    {
//        Dynamic way but encountered bugs with old attribute icons, used switch case instead
//        $helper = Mage::helper('apdinteract_hierarchy')->_loadAttributeData();
//        foreach ($helper as $data){
//            if ($filter == 'Driving Style'){
//                if (strpos($data[0], $attribute) !== false) {
//                    Mage::log(substr($data[1], 6));
//                    return substr($data[1], 6);
////                    return $data[1];
//                }
//            }
//
//        }

        // Ugly code but there's no other way unless we implement the Fully-Dynamic Attribute task
        $class = null;
        if ($filter == 'Driving Style' || $filter == 'Features') {
            switch ($attribute) {
                case 'Value':
                    $class = 'icon2 icon-value-tyre';
                    break;
                case 'Even Wear':
                    $class = 'icon-bftevenwear';
                    break;
                case 'Value Battery':
                    $class = 'icon2 icon-value-battery';
                    break;
                case 'Fuel Economy':
                    $class = 'icon2 icon-fuel-economy';
                    break;
                case 'Grip Performance':
                    $class = 'icon2 icon-grip-performance';
                    break;
                case 'Handling Performance':
                    $class = 'icon2 icon-handling-performance';
                    break;
                case 'Off Road Performance':
                    $class = 'icon2 icon-offroad-performance';
                    break;
                case 'Wet Performance':
                    $class = 'icon2 icon-wet-performance';
                    break;
                case 'Low Noise':
                    $class = 'icon2 icon-low-noise';
                    break;
                case 'Comfort Performance':
                    $class = 'icon2 icon-comfort-performance';
                    break;
                case 'Sports Performance':
                    $class = 'icon2 icon-sports-performance';
                    break;
                case 'Braking Performance':
                    $class = 'icon2 icon-breaking-performance';
                    break;
                case 'Dry Performance':
                    $class = 'icon2 icon-dry-performance';
                    break;
                case 'Wet Dry Performance':
                    $class = 'icon2 icon-wetdry-performance';
                    break;
                case 'All Terrain':
                    $class = 'icon2 icon-all-terrain';
                    break;
                case 'Winter':
                    $class = 'icon2 icon-winter-tyres';
                    break;
                case 'Original Equipment':
                    $class = 'icon2 icon-original-equipment';
                    break;
                case 'Run On Flat':
                    $class = 'icon2 icon-run-on-flat';
                    break;
                case 'Traction':
                    $class = 'icon2 icon-traction-tyres';
                    break;
                case 'Cornering Performance':
                    $class = 'icon2 icon-cornering-performance';
                    break;
                case 'Road Hazard Resistant':
                    $class = 'icon2 icon-road-hazard-resistant';
                    break;
                case 'Slow Wear':
                    $class = 'icon2 icon-slow-wear';
                    break;
                default:
                    $arr = explode(' ', trim(strtolower($attribute)));
                    $class = $arr[0];
            }
        }
        return $class;
    }
}