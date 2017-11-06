
<?php


class ApdInteract_ImportExport_Model_Import_Entity_Order extends Mage_ImportExport_Model_Import_Entity_Abstract
{

    const COL_EMAIL = 'customer_email';
    const COL_WEBSITE = '_website';
    const COL_STORE = '_store';
    const COL_COSTAR_ID = 'costar_sales_order_number';
    const COL_COSTAR_INVOICE_ID = 'costar_invoice_id';
    const COL_COSTAR_INVOICE_DATE = 'costar_invoice_date';
    const CUSTOM_ATTRIBUTE_PREFIX = '_custom_';

    /**
     * Error codes.
     */
    const ERROR_INVALID_WEBSITE = 'invalidWebsite';
    const ERROR_INVALID_EMAIL = 'invalidEmail';
    const ERROR_VALUE_IS_REQUIRED = 'valueIsRequired';
    const ERROR_INVALID_STORE = 'invalidStore';
    const ERROR_INVALID_COSTARID = 'invalidCostarId';


    const SCOPE_DEFAULT = 1;
    const SCOPE_ITEMS = -1;

    protected $_websiteCodeToId = array();

    protected $_storeCodeToId = array();

    protected $_salesData = array();

    protected $_currencyCode;

    protected $_salesItemArray = array();

    protected $_customOptionArray= array();

    protected $_updated = 0;



    public function __construct()
    {

        parent::__construct();

        $this->_initWebsites()->_initStores();
        $this->_currencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();
    }


    /**
     * @return $this
     */
    protected function _initStores()
    {
        foreach (Mage::app()->getStores(true) as $store) {
            $this->_storeCodeToId[$store->getCode()] = $store->getId();
        }
        return $this;
    }


    /**
     * Initialize website values.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Order
     */
    protected function _initWebsites()
    {
        /** @var $website Mage_Core_Model_Website */
        foreach (Mage::app()->getWebsites() as $website) {
            $this->_websiteCodeToId[$website->getCode()] = $website->getId();
        }
        return $this;
    }


    /** Append Only
     * @return bool
     */
    protected function _importData()
    {
        if (Mage_ImportExport_Model_Import::BEHAVIOR_APPEND == $this->getBehavior()) {

            $this->_salesItem();
            $this->_saveOrders();

        }

        return true;
    }

    /**
     * Group related product
     * @return array
     */
    private function _salesItem(){

        $index = 0;
        $fields = $this->getSalesItem();


        $headerCol = $this->_getSource()->getColNames();

        // get custom attribute from csv
        foreach($headerCol as $headerTitle){
            if(strpos($headerTitle,self::CUSTOM_ATTRIBUTE_PREFIX) !== false){
                $this->_customOptionArray[] = $headerTitle;
            }
        }
       // print_r($this->_customOptionArray);


        while ($bunch = $this->_dataSourceModel->getNextBunch()) {

            foreach ($bunch as $rowNum => $rowData) {

                if($rowData[self::COL_COSTAR_ID] != ""){
                    $index = $rowData[self::COL_COSTAR_ID];
                }

                $temp = array();
                $temOption = array();
                //sales item gather value
                foreach($fields as $num => $data){
                    $temp[$data] = $rowData[$num];
                }

                //custom attribute gather value
                $temOption['info_buyRequest']['product'] = "";
                $temOption['info_buyRequest']['qty'] = $rowData['qty_ordered'];
                if((bool)count($this->_customOptionArray)){
                    foreach ($this->_customOptionArray as $customOption){
                        $temOption['attributes_info'][] =  $this->_validateCustomOption($customOption,$rowData);
                    }
                }
                $temOption['simple_name'] = $rowData['product_name'];
                $temOption['simple_sku'] = $rowData['product_sku'];
                $temp['product_options'] = serialize($temOption); //convert array

                $this->_salesItemArray[$index][] = $temp;

            }

        }

        return $this->_salesItemArray;
    }


    /**
     * @param $option
     * @param $rowData
     * @return array
     */
    protected function _validateCustomOption($option,$rowData){

        if($rowData[$option] != ""){

            $noise = array(self::CUSTOM_ATTRIBUTE_PREFIX,"_");
            $stringTitle = str_replace($noise," ",$option);
            $stringTitle = ucfirst(trim($stringTitle));

            $valueArray = explode("|",$rowData[$option]);
            $title = "";
            $value = "";
                if(count($valueArray) == 2){
                    $formattedPrice = Mage::helper('core')->currency($valueArray[1], true, false);
                    $title = "{$stringTitle} +{$formattedPrice}";
                    $value = $valueArray[0];
                }else{
                    $title = $stringTitle;
                    $value = $rowData[$option];
                }

            return array("label" => $title,
                         "value" => $value
                );
        }
    }

    /**
     * Gather and save information about order entities.
     *
     * @return Mage_ImportExport_Model_Import_Entity_Order
     */
    protected  function _saveOrders(){

        while ($bunch = $this->_dataSourceModel->getNextBunch()) {

            foreach ($bunch as $rowNum => $rowData) {

                if (!$this->validateRow($rowData, $rowNum)) {

                    continue;
                }

                //check costar ID exist
                $isCostartExist = $this->_checkSalesOrderExist($rowData[self::COL_COSTAR_ID]);

                if($isCostartExist){
                    $this->addRowError("Costar Number already exist. Costar Number: {$rowData[self::COL_COSTAR_ID]}", $rowNum);
                }

                if(!$isCostartExist && $rowData[self::COL_COSTAR_ID] != ""){

                    //Check Customer  information
                    $customer = $this->_checkCustomerEmailExist($rowData[self::COL_EMAIL]);
                    if(empty($customer->getId())){
                        //save customer information if not exist
                        $newCustomerId = $this->_saveCustomerInfo($rowData,$rowNum);
                        if(!empty($newCustomerId)){
                            //save customer address if not exist
                            $this->_saveCustomerAddress($newCustomerId,$rowData,$rowNum);
                        }

                        $customer  = $this->_customerResource()->load($newCustomerId);
                    }

                    $billingAddress = $customer->getPrimaryBillingAddress();
                    $shippingAddress = $customer->getPrimaryBillingAddress();

                    $rowData['customer_id']        = $customer->getId();
                    $rowData['billing_address_id'] = $billingAddress->getId();
                    $rowData['shipping_address_id'] = $shippingAddress->getId();
                    $rowData['store_id']           = $this->_storeCodeToId[$rowData[self::COL_STORE]];
                    $storeId = $this->_storeCodeToId[$rowData[self::COL_STORE]];

                    $billingDataAddress = array_merge($this->_addDataToSalesAddress($rowData),
                        array('address_type'  => Mage_Sales_Model_Quote_Address::TYPE_BILLING,
                            'customer_address_id' => $billingAddress->getId()));

                    $shippingDataAddress = array_merge($this->_addDataToSalesAddress($rowData),
                        array('address_type'  => Mage_Sales_Model_Quote_Address::TYPE_SHIPPING,
                            'customer_address_id' => $shippingAddress->getId()));

                    $orderIncrementId = Mage::getSingleton('eav/config')->getEntityType('order')->fetchNewIncrementId(1);
                    $salesData = array_merge($this->_addDataToOrder($rowData),
                        array('quote_id'  => 0,
                            'increment_id' => $orderIncrementId,
                            'customer_is_guest' => 0,
                            'store_currency_code' => $this->_currencyCode,
                            'base_currency_code'  => $this->_currencyCode,
                            'order_currency_code' => $this->_currencyCode,
                            'customer' =>  $customer));

                    $transaction = Mage::getModel('core/resource_transaction');
                    // Customer and sales data

                    $order = $this->_salesOrderResource()->setData($salesData);

                    // set Billing Address
                    $billingAddress = Mage::getModel('sales/order_address')->setData($billingDataAddress);
                    $order->setBillingAddress($billingAddress);

                    //set Shipping Address
                    $shippingAddress = Mage::getModel('sales/order_address')->setData($shippingDataAddress);
                    $order->setShippingAddress($shippingAddress)->setShipping_method('flatrate_flatrate');

                    //you can set your payment method name here as per your need
                    $salesPayment = $this->_addDataToSalesPayment($rowData);
                    $orderPayment = Mage::getModel('sales/order_payment')->setData($salesPayment);
                    $order->setPayment($orderPayment);


                    //save sales item into order
                    foreach($this->_salesItemArray[$rowData[self::COL_COSTAR_ID]] as $product){
                        $orderItem = Mage::getModel('sales/order_item')->setData($product);
                        $order->addItem($orderItem);
                    }

                    try{

                        $transaction->addObject($order);
                        $transaction->addCommitCallback(array($order, 'place'));
                        $transaction->addCommitCallback(array($order, 'save'));
                        $transaction->save();

                        //Create Invoice after Order was placed
                        $this->_createInvoice($orderIncrementId,$rowNum,$rowData);

                        $this->_updated++;

                    }catch (Exception $e) {

                        $this->addRowError($e->getMessage(), $rowNum);

                    }
                }


            }

        }
        $this->_log();

        return $this;

    }


    /**
     * Save Invoice
     * @param $orderIncrementId
     * @param $rowNum
     * @param $rowData
     */
    protected function _createInvoice($orderIncrementId,$rowNum,$rowData){

        $order = $this->_salesOrderResource()->loadByIncrementId($orderIncrementId);
        $order->setStatus($rowData['order_status'], true);

        $error = false;
        try {
            if(!$order->canInvoice())
            {
                $this->addRowError("Cannot create an invoice. Order ID:{$order->getId()}", $rowNum);
                $error = true;
            }

            $invoice = Mage::getModel("sales/service_order", $order)->prepareInvoice();

            if (!$invoice->getTotalQty()) {
                $this->addRowError("Cannot create an invoice without products. Order number:{$order->getId()}", $rowNum);
                $error = true;
            }

            if(!$error){
                $invoice->setCreatedAt($rowData[self::COL_COSTAR_INVOICE_DATE]);
                $invoice->setUpdatedAt($rowData[self::COL_COSTAR_INVOICE_DATE]);
                $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
                $invoice->register();
                $order->addStatusHistoryComment('Automatically INVOICED via import.', false);

                $transactionSave = Mage::getModel('core/resource_transaction')
                    ->addObject($invoice)
                    ->addObject($invoice->getOrder());

                $transactionSave->save();
            }

        }
        catch (Mage_Core_Exception $e) {

            $this->addRowError($e->getMessage(), $rowNum);
        }
    }


    /**
     *
     * Save customer Billing and Shipping into array
     * @param $rowData
     * @param string $prefix
     * @return array
     */
    protected Function _addDataToSalesAddress($rowData,$prefix = 'billing_'){
        $record = array();
        $fields = $this->getSalesBillingAndShippingFields();
        foreach($fields as $num => $data){
            $record[$data] = $rowData[$prefix . $data];
        }

        $record['customer_id'] = $rowData['customer_id'];
        $record['store_id'] = $rowData['store_id'];

        return $record;
    }


    /**
     *
     * Save sales order into array
     * @param $rowData
     * @return array
     */
    protected function _addDataToOrder($rowData){

        $order = $this->_salesOrderResource();

        $salesTable = array_merge($this->getSalesTable(),$this->additionalFields());

        foreach($salesTable as $num => $data){

            if(isset($rowData[$data])){
                $this->_salesData[$data] = $rowData[$data];
            }

        }

        return $this->_salesData;

    }


    /**
     * Save customer sales payment into array
     * @param $rowData
     * @return array
     */
    protected function _addDataToSalesPayment($rowData){

        $record = array();
        $fields = $this->getSalesPayment();
        foreach($fields as $num => $data){
            $record[$num] = $rowData[$data];
        }

        $record['customer_payment_id'] = 0;

        return $record;

    }

    /**
     * Save Customer information
     * @param $rowData
     * @return mixed
     */
    protected function _saveCustomerInfo($rowData,$rowNum){

        $websiteId = $rowData[self::COL_WEBSITE];
        $store     = $rowData[self::COL_STORE];
        $email     = $rowData[self::COL_EMAIL];
        $customer  = $this->_customerResource();

        if(!empty($websiteId)  && !empty($email)){

            $customer->setWebsiteId($websiteId)
                ->setFirstname($rowData['customer_firstname'])
                ->setLastname($rowData['customer_firstname'])
                ->setEmail($email)
                ->setDormantFlag(1)
                ->setMiddleName($rowData['customer_middlename']);
            try{
                $customer->save();
            }catch (Exception $e) {
                $this->addRowError($e->getMessage(), $rowNum);
            }

            return $customer->getId();
        }
    }

    /**
     * Save Customer address
     * @param $customer_id
     * @param $rowData
     * @return mixed
     */
    protected function _saveCustomerAddress($customer_id,$rowData,$rowNum){

        $address = Mage::getModel("customer/address");
        $address->setCustomerId($customer_id)
            ->setFirstname($rowData['billing_firstname'])
            ->setMiddleName($rowData['billing_middlename'])
            ->setLastname($rowData['billing_lastname'])
            ->setCountryId($rowData['billing_country'])
            ->setPostcode($rowData['billing_postcode'])
            ->setCity($rowData['billing_city'])
            ->setTelephone($rowData['billing_telephone'])
            ->setFax($rowData['billing_fax'])
            ->setCompany($rowData['billing_company'])
            ->setStreet($rowData['billing_street_full'])
            ->setIsDefaultBilling('1')
            ->setIsDefaultShipping('1')
            ->setSaveInAddressBook('1');

        try{
            $address->save();
        }
        catch (Exception $e) {
            $this->addRowError($e->getMessage(), $rowNum);
        }

        return $address->getId();
    }


    /**
     * Check if Customer is existing
     * @param $email
     * @return false|Mage_Core_Model_Abstract
     * @throws Mage_Core_Exception
     */
    protected function _checkCustomerEmailExist($email){

        $customer = $this->_customerResource()
                    ->getCollection()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('email', $email )
                    ->getFirstItem();

        return $customer;
    }

    /**
     * Check if Sales order Exist
     * @param $costarSalesNumber
     * @return bool
     */
    protected function _checkSalesOrderExist($costarSalesNumber){

        if($costarSalesNumber != ""){
            $collection = $this->_salesOrderResource()->getCollection()
                ->addAttributeToFilter('costar_sales_order_number',array('eq' => $costarSalesNumber));

            if($collection->count()){
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function _salesOrderResource(){
        return Mage::getModel('sales/order');
    }

    /**
     * @return false|Mage_Core_Model_Abstract
     */
    protected function _customerResource(){
        return Mage::getModel('customer/customer');
    }



    /**
     * EAV entity type code getter.
     *
     * @abstract
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'order';
    }

    /**
     * Obtain scope of the row from row data.
     *
     * @param array $rowData
     * @return int
     */
    public function getRowScope(array $rowData) {
        return strlen(trim($rowData[self::COL_COSTAR_ID])) ? self::SCOPE_DEFAULT : self::SCOPE_ITEMS;
    }


    /**
     * Validate data row.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return boolean
     */
    public function validateRow(array $rowData, $rowNum) {

        static $email = null; // e-mail is remembered through all customer rows
        static $website = null; // website is remembered through all customer rows
        if (isset($this->_validatedRows[$rowNum])) { // check that row is already validated
            return !isset($this->_invalidRows[$rowNum]);
        }

        $rowScope = $this->getRowScope($rowData);

        $email = $rowData[self::COL_EMAIL];
        $emailToLower = strtolower($rowData[self::COL_EMAIL]);
        $website = $rowData[self::COL_WEBSITE];

        if(self::SCOPE_DEFAULT == $rowScope){
            if (!Zend_Validate::is($email, 'EmailAddress')) {
                $this->addRowError(self::ERROR_INVALID_EMAIL, $rowNum);
            } elseif (!isset($this->_websiteCodeToId[$website])) {
                $this->addRowError(self::ERROR_INVALID_WEBSITE, $rowNum);
            }
            if($rowData[self::COL_COSTAR_ID] == ''){
                $this->addRowError(self::ERROR_INVALID_COSTARID, $rowNum);
            }

        }

        return !isset($this->_invalidRows[$rowNum]);
    }

    public function validateData()
    {

        if (!$this->_dataValidated) {
            // does all permanent columns exists?
            if (($colsAbsent = array_diff($this->_permanentAttributes, $this->_getSource()->getColNames()))) {

                Mage::throwException(
                    Mage::helper('importexport')->__('Can not find required columns: %s', implode(', ', $colsAbsent))
                );

            }

            // initialize validation related attributes
            $this->_errors = array();
            $this->_invalidRows = array();

            // check attribute columns names validity
            $invalidColumns = array();

            foreach ($this->_getSource()->getColNames() as $colName) {
                if (!preg_match('/^[a-z][a-z0-9_]*$/', $colName) && !$this->isAttributeParticular($colName)) {
                    $invalidColumns[] = $colName;
                }
            }
            $this->_saveValidatedBunches();
            $this->_dataValidated = true;

        }

        //$this->_log();


    }

    protected function _log(){

        if(Mage::helper('costar/costar')->isAllowedToLog()){
            $operationId = Mage::app()->getRequest()->getParam('operation');
            $logFilename = 'import_data_' . Mage::getModel('core/date')->date('Ymd') . '_' . $operationId . '.log';
            Mage::log($this->getErrorMessages(), null, $logFilename);
        }

        $log_data = array(
            'updated' => $this->_updated,
            'skipped' => $this->getInvalidRowsCount(),
            'error'  =>  $this->getInvalidRowsCount()
        );

        Mage::register('log_data', $log_data);

    }


    public function getSalesTable()
    {
        return array(
            'customer_email',
            'customer_firstname',
            'customer_lasttname',
            'customer_prefix',
            'customer_middlename',
            'customer_id',
            'billing_address_id',
            'customer_suffix',
            'taxvat',
            'created_at',
            'updated_at',
            'invoice_created_at',
            'creditmemo_created_at',
            'tax_amount',
            'base_tax_amount',
            'discount_amount',
            'base_discount_amount',
            'subtotal_incl_tax',
            'base_subtotal_incl_tax',
            'coupon_code',
            'subtotal',
            'base_subtotal',
            'grand_total',
            'base_grand_total',
            'adjustment_positive',
            'adjustment_negative',
            'refunded_subtotal',
            'base_refunded_subtotal',
            'refunded_tax_amount',
            'base_refunded_tax_amount',
            'refunded_discount_amount',
            'base_refunded_discount_amount',
            'store_id',
            'hold_before_state',
            'hold_before_status',
            'store_currency_code',
            'base_currency_code',
            'order_currency_code',
            'total_paid',
            'base_total_paid',
            'is_virtual',
            'total_qty_ordered',
            'remote_ip',
            'total_refunded',
            'base_total_refunded',
            'total_canceled',
            'total_invoiced');
    }

    public function getSalesBillingAndShippingFields()
    {
        return array(
            'prefix',
            'firstname',
            'middlename',
            'lastname',
            'suffix',
            'street',
            'city',
            'region',
            'country_id',
            'postcode',
            'telephone',
            'company',
            'fax');
    }

    public function getSalesPayment()
    {
        return array(
            'method' => 'payment_method',
            'ge_method' => 'payment_ge_method',
            'ge_term'   => 'payment_ge_term',
            'po_number' => 'payment_po_number',
        );
    }

    public function getSalesItem()
    {
        return array(
            'product_sku' => 'sku',
            'product_name'=> 'name',
            'qty_ordered'=> 'qty_ordered',
            'qty_invoiced'=> 'qty_invoiced',
            'qty_refunded'=> 'qty_refunded',
            'qty_canceled'=> 'qty_canceled',
            'product_type'=> 'product_type',
            'original_price'=> 'original_price',
            'base_original_price'=> 'base_original_price',
            'row_total'=> 'row_total',
            'base_row_total'=> 'base_row_total',
            'row_weight'=> 'row_weight',
            'price_incl_tax'=> 'price_incl_tax',
            'base_price_incl_tax'=> 'base_price_incl_tax',
            'product_tax_amount'=> 'tax_amount',
            'product_base_tax_amount'=> 'base_tax_amount',
            'product_tax_percent'=> 'tax_percent',
            'product_discount'=> 'discount',
            'product_base_discount'=> 'base_discount',
            'product_discount_percent'=> 'discount_percent',
            'is_child'=> 'is_child',
            //'product_option'=> 'product_options'
        );
    }

    public function additionalFields(){

        return array(
            'costar_sales_order_number',
            'costar_invoice_id',
            'costar_invoice_date',
            'storelocation',
            'store_details'
        );
    }


}