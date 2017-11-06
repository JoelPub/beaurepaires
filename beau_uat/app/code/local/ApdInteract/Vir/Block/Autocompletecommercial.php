<?php
class ApdInteract_Vir_Block_Autocompletecommercial extends Mage_Core_Block_Template {
    
    public function getAutocompleteCustomers() {

        // Data format
        // properties should equal column names, then everything will work.
        
        $search = Mage::app()->getRequest()->getPost('orderData');        
        $orders = $this->getOrderCollection($search['customername']);        
        return $orders;
        
        // Data comes back in below format:
        // Property names should match form fields / vir "order" object properties
        
//        echo "<pre>";
//        foreach ($orders as $order) {
//            print_r($order->getData());
//        }
        
//        $cust[0]->name = "Ed Smith";
//        $cust[0]->custaddress = "10 Ed Street";
//        $cust[0]->custsuburb = "Smithtown";
//        $cust[0]->custpostcode = "2000";
//        $cust[1]->name = "Kim Jones";
//        $cust[1]->custaddress = "10 Kim Street";
//        $cust[1]->custsuburb = "Jonestown";
//        $cust[1]->custpostcode = "3011";        
//        return $cust;
    }
    
    /*

    [increment_id] => 145000028
    [customer_email] => eds@yahoo.com
    [customer_firstname] => Eduardo
    [customer_lastname] => Vicente IIIII
     [gomage_deliverydate] => 
    [gomage_deliverydate_formated] => 
    [vmake] => 
    [vmodel] => 
    [last_wheel_balance] => 
    [last_wheel_alignment] => 
    [registration_number] => 
    [odometer] => 
    [storelocation] => 
    [delivery_date] => 
     */
    
    public function getOrderCollection($search = 'yahoo') {
        $orders = Mage::getModel('sales/order')
        ->getCollection()        
        ->addAttributeToSelect('increment_id') 
        ->addAttributeToSelect('created_at') 
     // Only show customers from correct store:           
     // ->addAttributeToFilter('storelocation', array('in' => array('name_of_store_here')))                         
        ->addAttributeToFilter('status', array('nin' => array('canceled')))
        ->addFieldToFilter(
                array('customer_firstname','customer_lastname','customer_email'),
                array(
                    array('like'=>"%{$search}%"),
                    array('like'=>"%{$search}%"),
                    array('like'=>"%{$search}%")
                )
        )
        ->join(array('soa' => 'sales/order_address'),
            'soa.parent_id=main_table.entity_id and soa.address_type = "billing"', 
            array(
                 'name'=>'CONCAT(soa.firstname, " " , soa.lastname)', 
                // 'custemail'=>'soa.email',
                 'phonenumber'=>'soa.telephone',
//                 'addressline1'=>'soa.street', 
//                 'suburb'=>'soa.city',
               //  'custregion'=>'soa.region',
//                 'postcode'=>'soa.postcode', 
                ),
            null,
            'left'
            );
        
        $orders->getSelect()
            // ->reset(Zend_Db_Select::COLUMNS)
            // Can use any mysql expression in below
            // eg MAX(field) AS maxyfield
            ->columns(array(
//                'last_wheel_balance AS lastbalance', 
//                'last_wheel_alignment AS lastwheelalignment',
//                'vmake AS vehiclemake',
//                'vmodel AS vehiclemodel',
                'UPPER(registration_number) AS regonumber',
                'odometer AS speedohubreading'
                ))
//            ->group('name')
            ->order(array('name ASC'))    
            ->order(array('created_at DESC'));
                //

        return $orders;
    }
}

