<?php

class ApdInteract_ImportExport_Model_Import_Entity_Customer_Vehicle extends Mage_ImportExport_Model_Import_Entity_Abstract {

    /**
     * Prefix for source file column name, which displays that column contains address data.
     */
    const COL_NAME_PREFIX = '';

    protected $_attributes = array();

    /**
     * Customer import entity.
     *
     * @var Mage_ImportExport_Model_Import_Entity_Customer
     */
    protected $_customer;

    /**
     * Customer entity DB table name.
     *
     * @var string
     */
    protected $_entityTable;
    
    /**
     * Website code-to-ID
     *
     * @var array
     */
    protected $_websiteCodeToId = array();

    /**
     * Website ID-to-code
     *
     * @var array
     */
    protected $_websiteIdToCode = array();

    /**
     * Attributes with index (not label) value.
     *
     * @var array
     */
    
    public function __construct(Mage_ImportExport_Model_Import_Entity_Customer $customer) {
        parent::__construct();

        $this->_initAttributes();
        $this->_initWebsites();
        $this->_customer = $customer;
        foreach ($this->_messageTemplates as $errorCode => $message) {
            $this->_customer->addMessageTemplate($errorCode, $message);
        }
    }

    /**
     * EAV entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode() {
        return 'customer';
    }

    /**
     * Import data rows.
     *
     * @return boolean
     */
    protected function _importData() {

        /** @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer');
        /** @var $resource Mage_Customer_Model_Address */
        $customerId = null;

        $isAppendMode = Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->_customer->getBehavior();

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {
            $entityRows = array();
            $attributes = array();
            $defaults = array(); // customer default addresses (billing/shipping) data

            foreach ($bunch as $rowNum => $rowData) {
                if (!empty($rowData[Mage_ImportExport_Model_Import_Entity_Customer::COL_EMAIL]) && !empty($rowData[Mage_ImportExport_Model_Import_Entity_Customer::COL_WEBSITE])
                ) {
                    $customerId = $this->_customer->getCustomerId(
                            $rowData[Mage_ImportExport_Model_Import_Entity_Customer::COL_EMAIL], $rowData[Mage_ImportExport_Model_Import_Entity_Customer::COL_WEBSITE]
                    );
                }

                /* Get Row values */
                if($this->_websiteCodeToId[$rowData[Mage_ImportExport_Model_Import_Entity_Customer::COL_WEBSITE]]!='')
                $storeId =   $this->_websiteCodeToId[$rowData[Mage_ImportExport_Model_Import_Entity_Customer::COL_WEBSITE]];                
                
                $make = isset($rowData[$this->getColNameForAttrCode('make')]) ? $rowData[$this->getColNameForAttrCode('make')] : '';
                $manufactureYear = isset($rowData[$this->getColNameForAttrCode('manufacturer_year')]) ?  $rowData[$this->getColNameForAttrCode('manufacturer_year')] : '';
                $model = isset($rowData[$this->getColNameForAttrCode('model')]) ?  $rowData[$this->getColNameForAttrCode('model')] : '';
                $series = isset($rowData[$this->getColNameForAttrCode('series')]) ?  $rowData[$this->getColNameForAttrCode('series')] : '';
                $registration = isset($rowData[$this->getColNameForAttrCode('registration')]) ?  $rowData[$this->getColNameForAttrCode('registration')] : '';                
                $url = isset($rowData[$this->getColNameForAttrCode('url')]) ?  $rowData[$this->getColNameForAttrCode('url')] : '';

                /* Check required values */
                if ($make != '' || $registration != '' || $series != '' || $model != '') {

                    $countDuplicate = Mage::helper('apdinteract_vehicle')->checkIfVehicleIsExisting($customerId, $registration); // check duplicate and skip if entry already exists

                    if ($countDuplicate->count() <= 0) { //if unique or new                    
                        $resource = Mage::getModel('apdinteract_vehicle/vehicle');
                    } else { //if existing
                        $data = $countDuplicate->getFirstItem();
                        $resource = Mage::getModel('apdinteract_vehicle/vehicle')->load($data->getId());
                    }
                                        
                    $resource->setWebsiteId($storeId)
                             ->setCustomerId($customerId)
                             ->setMake($make)
                             ->setManufactureYear($manufactureYear)
                             ->setModel($model)
                             ->setSeries($series)
                             ->setRegistration($registration)                            
                             ->setUrl($url)
                             ->save(); // save or update details
                }
            }
        }
        return true;
    }

    /**
     * Initialize customer vehicle attributes.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Customer_Address
     */
    protected function _initAttributes() {
        $this->_attributes = array('make' => '1', 'manufacturer_year' => '1', 'model' => '1', 'series' => '1', 'registration' => '1','url' => '0');

        return $this;
    }

    /**
     * Check if there are vehicle data on the row.
     *
     * @return boolean
     */
    protected function _isRowWithVehicle(array $rowData) {

        foreach (array_keys($this->_attributes) as $colName) {

            if (isset($rowData[$colName]) && strlen($rowData[$colName])) {

                return true;
            }
        }
        return false;
    }

    /**
     * Check if all fields are empty for vehicle so we can skip checking.
     *
     * @return boolean
     */
    protected function _isVehicleAllempty(array $rowData,$num) {

        $count = count($this->_attributes);
        $i = 0;        
        foreach (array_keys($this->_attributes) as $colName) {

            if (!isset($rowData[$colName]) || strlen($rowData[$colName]) <= 0) {                
                $i++;
            }
        }

        $result = ($i == $count) ? true : false;

        return $result;
    }

    public static function getColNameForAttrCode($attrCode) {
        return self::COL_NAME_PREFIX . $attrCode;
    }

    /**
     * Validate data row.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return boolean
     */
    public function validateRow(array $rowData, $rowNum) {


        $rowIsValid = true;        
        if (!$this->_isVehicleAllempty($rowData, $rowNum) && $this->_isRowWithVehicle($rowData)) {
            
            foreach ($this->_attributes as $colName => $attrParams) {
                $column = $this->getColNameForAttrCode($colName);

                if (isset($rowData[$column]) && strlen($rowData[$column])) {
                    $rowIsValid = true;
                } elseif ($attrParams == 1 && strlen($rowData[$column]) <= 0) {                    
                    $this->_customer->addRowError(
                            Mage_ImportExport_Model_Import_Entity_Customer::ERROR_VALUE_IS_REQUIRED, $rowNum, $column
                    );
                    $rowIsValid = false;
                }
            }
        }

        return $rowIsValid;
    }
    
    protected function _initWebsites() {
        /** @var $website Mage_Core_Model_Website */
        foreach (Mage::app()->getWebsites(true) as $website) {
            $this->_websiteCodeToId[$website->getCode()] = $website->getId();
            $this->_websiteIdToCode[$website->getId()] = $website->getCode();
        }
        return $this;
    }


}

