<?php

class ApdInteract_ImportExport_Model_Export_Entity_Order extends Mage_ImportExport_Model_Export_Entity_Abstract
{

    const DATE_RANGE = 450;
    const STORE_ID   =  2;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    protected function _getHeadRowSalesItems(){

        return array(
            'product_id'            => 'product_id',
            'product_type'          => 'product_type',
            'product_sku'           => 'sku',
            'product_name'          => 'name',
            'product_description'   => 'description',
            'qty_ordered'           => 'qty_ordered',
            'qty_invoiced'          => 'qty_invoiced',
            'qty_shipped'           => 'qty_shipped',
            'qty_refunded'          => 'qty_refunded',
            'qty_canceled'          => 'qty_canceled',
            'product_price'         => 'price',
            'product_base_price'    => 'base_price',
            'product_original_price'=> 'original_price',
            'base_row_total'        => 'base_row_total',
            'product_row_total'     => 'row_total',
            'row_weight' => 'row_weight',
            'price_incl_tax' => 'price_incl_tax',
            'base_price_incl_tax' => 'base_price_incl_tax',
            'product_base_tax_amount' => 'base_tax_amount',
            'product_tax_percent' => 'tax_percent',
            'product_tax_amount'    => 'tax_amount',
            'product_discount_amount' => 'discount_amount',
            'product_base_discount_amount' => 'base_discount_amount',
            'product_discount_percent' => 'discount_percent',
            'product_options'       => 'product_options',

        );

    }
    protected function _getHeadRowsBilling(){

        return array(

            'billing_prefix'        => 'prefix',
            'billing_firstname'     => 'firstname',
            'billing_middlename'    => 'middlename',
            'billing_lastname'      => 'lastname',
            'billing_suffix'        => 'suffix',
            'billing_street_full'   => 'street',
            'billing_city'          => 'city',
            'billing_region'        => 'region',
            'billing_country'       => 'country_id',
            'billing_postcode'      => 'postcode',
            'billing_telephone'     => 'telephone',
            'billing_company'       => 'company',
            'billing_fax'           => 'fax'
        );
    }


    /**
     * @return array
     */
    protected function _getHeadRowSalesOrder(){

        return array(
            'entity_id'                  => 'entity_id',
            'order_id'              => 'increment_id',
            'paypal_transaction_id'       => 'paypal_transaction_id',
            'customer_email'            => 'customer_email',
            'customer_firstname'        => 'customer_firstname',
            'customer_lastname'         => 'customer_lastname',
            'prefix' => 'prefix',
            'customer_middlename'       => 'customer_middlename',
            'suffix' => 'suffix',
            'customer_taxvat'           => 'customer_taxvat',
            'created_at'                => 'created_at',
            'updated_at' => 'updated_at',
            'invoice_created_at' => 'invoice_created_at',
            'creditmemo_created_at' => 'creditmemo_created_at',
            'tax_amount'                => 'tax_amount',
            'base_tax_amount'           => 'base_tax_amount',
            'discount_amount'           => 'discount_amount',
            'base_discount_amount' => 'base_discount_amount',
            'base_to_global_rate' => 'base_to_global_rate',
            'base_to_order_rate' => 'base_to_order_rate',
            'store_to_base_rate' => 'store_to_base_rate',
            'store_to_order_rate' => 'store_to_order_rate',
            'subtotal_incl_tax' => 'subtotal_incl_tax',
            'base_subtotal_incl_tax' => 'base_subtotal_incl_tax',
            'coupon_code'               => 'coupon_code',
            'subtotal' => 'subtotal',
            'base_subtotal' => 'base_subtotal',
            'grand_total' => 'grand_total',
            'base_grand_total' => 'base_grand_total',
            'adjustment_positive' => 'adjustment_positive',
            'adjustment_negative' => 'adjustment_negative',
            'base_subtotal_refunded' => 'base_subtotal_refunded',
            'base_subtotal_refunded' => 'base_subtotal_refunded',
            'base_tax_refunded' => 'base_tax_refunded',
            'gw_base_tax_amount' => 'gw_base_tax_amount',
            'gw_tax_amount' => 'gw_tax_amount',
            'gw_items_base_tax_amount' => 'gw_items_base_tax_amount',
            'gw_items_tax_amount' => 'gw_items_tax_amount',
            'gw_card_tax_amount' => 'gw_card_tax_amount',
            'store_id' => 'store_id',
            'status' => 'status',
            'state' => 'state',
            'hold_before_state' => 'hold_before_state',
            'hold_before_status' => 'hold_before_status',
            'store_currency_code'       => 'store_currency_code',
            'base_currency_code'        => 'base_currency_code',
            'order_currency_code'       => 'order_currency_code',
            'total_paid'                => 'total_paid',
            'base_total_paid' => 'base_total_paid',
            'is_virtual' => 'is_virtual',
            'total_qty_ordered'         => 'total_qty_ordered',
            'remote_ip'                 => 'remote_ip',
            'total_refunded' => 'total_refunded',
            'base_total_refunded' => 'base_total_refunded',
            'total_canceled' => 'total_canceled',
            'total_invoiced' => 'total_invoiced',
            'customer_id' => 'customer_id',
            'weight'                    => 'weight',
            'customer_note'             => 'customer_note',
            'storelocation'             => 'storelocation'            
        );
    }

    /**
     * @return array
     */
    protected function _getPaymentDetails(){

        return array(
            'payment_method'        => 'method',
            'payment_amount_paid'   => 'amount_paid',
            'payment_amount_ordered'=> 'amount_ordered',
            'payment_cc_last4'      => 'cc_last4',
            'payment_cc_owner'      => 'cc_owner',
            'payment_cc_type'       => 'cc_type',
            'payment_po_number'     => 'po_number',
            'payment_ge_method'     => 'ge_method',            


        );
    }

    /**
     * @return array
     */
    protected function _getStoreLocation(){

        return array(
            'storelocation_id'    => 'entity_id',
            'storelocation_title' =>  'title',
        );

    }

    /**
     * Export process.
     *
     * @return string
     */
    public function export()
    {

        $skip_attr = array();
        $operation = Mage::app()->getRequest()->getParam('operation');
        $param = Mage::app()->getRequest()->getParam('export_filter');
        $skip_attr = Mage::app()->getRequest()->getParam('skip_attr');

        if($operation != "") {
            $operation = Mage::getModel('enterprise_importexport/scheduled_operation')->load($operation);
            $entity_attributes =  $operation->getEntityAttributes();

            $param = $entity_attributes['export_filter'];
            $skip_attr = $entity_attributes['skip_attr'];
        }

        $resource = Mage::getSingleton('core/resource');
        $writer = $this->getWriter();

        //CSV header name
        $writer->setHeaderCols(array_merge(array_keys($this->_getHeadRowSalesOrder()),
                array_keys($this->_getPaymentDetails()),
                array_keys($this->_getStoreLocation()),
                array_keys($this->_getHeadRowsBilling()),
                array_keys($this->_getHeadRowSalesItems())
            )
        );

        $collection = Mage::getModel('sales/order')->getCollection()->addAttributeToSelect('*');

        // Filter created date
        if(!in_array(self::DATE_RANGE,$skip_attr)){
            $from_date = $this->_fixDateFormat($param['date_range'][0] . ' 00:00:00');
            $to_date =   $this->_fixDateFormat($param['date_range'][1] . ' 23:59:59');

            if($from_date != '' && $to_date != ''){
                $filter_date = array('from'=>$from_date,'to'=>$to_date);
                $collection->addAttributeToFilter('main_table.created_at',array($filter_date));
            }elseif($from_date != '' && $to_date == ''){
                $collection->addAttributeToFilter('main_table.created_at',array('gteq' => $from_date));
            }elseif($from_date == '' && $to_date != ''){
                $collection->addAttributeToFilter('main_table.created_at',array('lteq' => $to_date));
            }
        }

        //filter store
        if(!in_array(self::STORE_ID,$skip_attr)){
            if(!empty($param['store_id'])){
                $collection->addAttributeToFilter('main_table.store_id',array('eq'=> $param['store_id']));
            }
        }

        $collection->getSelect()->join(array('item'=> 'sales_flat_order_item'), 'item.order_id=main_table.entity_id', $this->_getHeadRowSalesItems());
        $collection->getSelect()->join(array('billing' => $resource->getTableName('sales/order_address')), 'main_table.billing_address_id = billing.entity_id', $this->_getHeadRowsBilling());
        $collection->getSelect()->join(array('payment' => $resource->getTableName('sales/order_payment')), 'payment.parent_id = main_table.entity_id', $this->_getPaymentDetails());
        $collection->getSelect()->joinLeft(array('storelocator'=> 'iwd_storelocator'), 'main_table.storelocation = storelocator.entity_id', $this->_getStoreLocation());
        $collection->getSelect()->joinLeft(array('payment_transaction'=> 'sales_payment_transaction'), 'main_table.entity_id=payment_transaction.order_id', 'txn_id as paypal_transaction_id');


        $entity_id = "";
        $date_array = array('created_at','updated_at');

        foreach($collection->getData() as $order){
            $row = array();

            //sales items information
            foreach($this->_getHeadRowSalesItems() as $product_key => $item){
                $row[$product_key] = $order[$product_key];
            }

            //sales order information
            if($entity_id != $order['entity_id']){ // display once


                foreach($this->_getHeadRowSalesOrder() as $key => $item){
                    if($key == 'order_id'){
                        $row['order_id'] = $order['increment_id'];
                    }elseif(in_array($key,$date_array)){
                        $row[$key] = $this->_convertDateTime($order[$key],'GMT',Mage::getStoreConfig('general/locale/timezone'));
                    }else{
                        $row[$key] = $order[$key];
                    }

                }

                foreach($this->_getPaymentDetails() as $key => $payment){
                    $row[$key] = $order[$key];
                }
                foreach($this->_getHeadRowsBilling() as $key => $billing_address){
                    $row[$key] = $order[$key];
                }
                foreach($this->_getStoreLocation() as $key => $storelocation){
                    $row[$key] = $order[$key];
                }

                $entity_id = $order['entity_id'];
            }

            $writer->writeRow($row);
        }


        return $writer->getContents();
    }

    /**
     * @param $datetime
     * @return string
     */
    protected function _fixDateFormat($datetime){

        $datetime = explode(" ",$datetime);
        if($datetime[0] != ""){
            $format_date = explode("/",$datetime[0]);
            $day = $format_date[0];
            $month = $format_date[1];
            $year = $format_date[2];

            $date =  $year . "-" . $month . "-" . $day . ' ' . $datetime[1];

            return $this->_convertDateTime($date,Mage::getStoreConfig('general/locale/timezone'),'GMT');
        }
    }

    /**
     * @param $dateTime
     * @param $from
     * @param $to
     * @return string
     *
     */
    protected function _convertDateTime($dateTime,$from,$to){

        $fromDate = new DateTimeZone($from);
        $toDate = new DateTimeZone($to);
        $date = new DateTime($dateTime, $fromDate);
        $date->setTimezone($toDate);

        return $date->format('Y-m-d H:i:s');
    }

    /**
     * @return mixed
     */
    public function getAttributeCollection()
    {
        $attributeCodes = array('store_id','date_range');
        return Mage::getResourceModel('eav/entity_attribute_collection')->setCodeFilter($attributeCodes);

    }

    /**
     * EAV entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'order';
    }
    
     public function exportFile() {
        $this->export();

        $writer = $this->getWriter();

        return array(
            'rows' => $writer->getRowsCount(),
            'value' => $writer->getDestination()
        );
    }

}
