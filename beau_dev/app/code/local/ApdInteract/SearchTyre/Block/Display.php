<?php
class ApdInteract_SearchTyre_Block_Display extends Mage_Core_Block_Template {

    const TYRES_ID = 41;

    const WHEELS_ID = 42;

    protected $_categoryName = array(self::TYRES_ID => 'Tyres', self::WHEELS_ID => 'Wheels');

    protected $_vehicleMake = array();

    protected $_vehicleYear = array();

    protected $_vehicleSaved = array();

    protected $_title;

    protected $_tabTitle;

    protected $_category;

    protected $_forms;

    protected $_showClearLink = false;


    /**
     * Initialize Current Category/active tab
     */
    public function _prepareLayout()
    {
        $param = Mage::app()->getRequest()->getParams();
        $scope = array('tyres','wheels');

        if(empty($this->_category())){
            if(in_array($param['type'],$scope) && $param['section'] == "size") {
                $category = Mage::getModel('catalog/category')->load($param['category']);
                Mage::register('current_category',$category);
                $this->_showClearLink = true;

            }elseif(in_array($param['type'],$scope) && ($param['section'] == "vehicles" || $param['section'] == "vehicle")){
                $category = Mage::getModel('catalog/category')->load($param['category']);
                Mage::register('current_category',$category);
                $this->_showClearLink = true;
            }
        }

        $this->_title = $this->_categoryName[$this->_category()->getId()];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return (string) $this->_title;
    }

    /**
     * Tab Title
     * @return string
     */
    public function getTabLabel()
    {
        $this->_tabTitle->_byVehicles = "Search {$this->getTitle()} By Vehicle";
        $this->_tabTitle->_bySize = "Search {$this->getTitle()} By Size";
        $this->_tabTitle->_saveVehicles = "Select Vehicles";

        return $this->_tabTitle;
    }

    /**
     * Vehicle Makes
     * @return arrayve
     */
    public function getMakeData()
    {
        $make = Mage::helper('searchtyre')->getMakes();
        foreach ($make as $m => $v) {
            $this->_vehicleMake[] = array("label" => $v, "value" => $m);
        }

        return $this->_vehicleMake;
    }

    /**
     * Vehicle Year
     * @return array
     */
    public function getYearData()
    {
        for($i=date("Y");$i>=1990;$i--) {
            $this->_vehicleYear[] = array("label" => $i, "value" => $i);
        }

        return $this->_vehicleYear;
    }

    /**
     * Vehicle saved
     * @return array
     */
    public function getSaveVehicle()
    {
        $vehicleSave = Mage::helper('searchtyre')->getAllVehicleByUser();
        foreach($vehicleSave as $item){
            $details = json_decode($item->getDetails(),true);
            $this->_vehicleSaved[] = array(
                    "value" => "{$details['make-tyres']}:{$details['year-tyres']}:{$details['model-tyres']}:{$details['series-tyres']}:{$item->getSeries()}",
                    "label" => $item->getSeries()
                );
        }

        return $this->_vehicleSaved;
    }

    /**
     * @param null $key
     * @return array|mixed
     */
    public function getValues($key = null)
    {
        $makeTyre = $this->_session()->getTMakeF();
        $makeYear = $this->_session()->getTYearF();
        $makeSeries = $this->_session()->getSeriesF();
        $makeModel = $this->_session()->getTModelF();
        $defaultVehicle = array(
            "make-tyres"     =>  !empty($makeTyre) ? (string) $makeTyre : (string) $_COOKIE['make-tyres'],
            "year-tyres"     =>  !empty($makeYear) ? (string) $makeYear : (string) $_COOKIE['year-tyres'],
            "model-tyres"    =>  !empty($makeModel)? (string) $makeModel : (string) $_COOKIE['model-tyres'],
            "series-tyres"   =>  !empty($makeSeries)? (string) $makeSeries : (string) $_COOKIE['series-tyres'],
            "width-tyres"    => (string) $this->_session()->getWidthF(),
            "profile-tyres"  => (string) $this->_session()->getProfileF(),
            "diameter-tyres" => (string) $this->_session()->getDiameterF(),
            "saved_vehicles" => ""
        );

        if(empty($key)){
            return $defaultVehicle;
        }else{
            return (string)$defaultVehicle[$key];
        }
    }

    /**
     * @return mixed
     */
    public function getForms()
    {
        $formLink = array(
            self::TYRES_ID => "/tyresearch/[series-tyres:label]/id/[series-tyres:value]/y/[year-tyres:value]",
            self::WHEELS_ID => "/wheelsearch/[series-tyres:label]/id/[series-tyres:value]/y/[year-tyres:value]"
        );

        $category = $this->_category();
        $categoryTitle = strtolower($this->_categoryName[$category->getId()]);
        $this->_forms->search_vehicle_form_action = $formLink[$category->getId()];
        $this->_forms->search_tyres_vehicle = array(
                array("name" => "type_hidden", "value" =>$categoryTitle),
                array("name" => "type", "value" => $categoryTitle),
                array("name" => "section", "value" => "vehicles"),
                array("name" => "tab", "value" => "search-tyres-vehicle"),
                array("name" => "category", "value" => $category->getId())
            );

        $this->_forms->search_tyres_size = array(
                array("name" => "type_hidden", "value" => $categoryTitle),
                array("name" => "type", "value" => $categoryTitle),
                array("name" => "tab", "value" => "search-tyres-size"),
                array("name" => "category", "value" => $category->getId())
            );

        $this->_forms->saved_vehicles = array(
                array("name" => "type", "value" =>  $categoryTitle),
                array("name" => "section", "value" => "vehicles"),
                array("name" => "tab", "value" => "saved-vehicles"),
                array("name" => "category", "value" => $category->getId())
        );

        return $this->_forms;
    }

    /**
     * @return mixed
     */
    protected function _category()
    {
        if(Mage::registry('current_category')) {
            return Mage::registry('current_category');
        }
    }

    /**
     * @return Mage_Core_Model_Abstract
     */
    protected function _session()
    {
        return Mage::getSingleton('core/session');
    }

    /**
     * Get Active tab ID
     * @return mixed
     */
    public function getActiveTab()
    {
        $param = Mage::app()->getRequest()->getParams();
        $defaultTabId = 'search-tyres-vehicle';

        if(isset($param['tab']) && !empty($param['tab'])){
            $defaultTabId =  $param['tab'];
        }

        return $defaultTabId;
    }

    /**
     * @return string
     */
    public function getClearLinkUrl()
    {
        $defaultLink = 'searchtyre/index/clear/type/tyres';
        if($this->_category()->getId() == self::WHEELS_ID){
            $defaultLink = 'searchtyre/index/clear/type/wheels';
        }

        return Mage::getBaseUrl() . $defaultLink;
    }

    /**
     * @return string
     */
    public function getBrowseLinkUrl()
    {
        $defaultLink = 'searchtyre/index/browse/type/tyres';
        if($this->_category()->getId() == self::WHEELS_ID){
            $defaultLink = 'searchtyre/index/browse/type/wheels';
        }

        return Mage::getBaseUrl() . $defaultLink;
    }

    /**
     * @return mixed
     */
    public function getVehicleSearchToken()
    {
       return Mage::helper('searchtyre')->getToken();
    }

    /**
     * @return string
     */
    public function getVehicleSearchUrl()
    {
        return "https://api.vehiclelogic.com.au";
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return (bool) Mage::getSingleton('customer/session')->isLoggedIn();
    }

    /**
     * @return mixed
     */
    public function showClearLink()
    {
        $_scoop = array(0 => 'false', 1 => 'true');
        return $_scoop[$this->_showClearLink];
    }

    /**
     * @return bool
     */
    public function isTyres(){
        $active = false;
        if($this->_category()->getId() == self::TYRES_ID){
            $active = true;
        }
        return $active;
    }

    /**
     * @return bool
     */
    public function isWheels(){
        $active = false;
        if($this->_category()->getId() == self::WHEELS_ID){
            $active = true;
        }
        return $active;
    }
}