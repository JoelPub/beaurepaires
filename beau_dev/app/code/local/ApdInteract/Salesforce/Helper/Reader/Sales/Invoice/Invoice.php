<?php

class ApdInteract_Salesforce_Helper_Reader_Sales_Invoice_Invoice 
extends ApdInteract_Salesforce_Helper_Core_Reader_Abstract {
	
	public function __construct() {
		$this->model_name = "sales/order_invoice";
		parent::__construct();
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ApdInteract_Salesforce_Helper_Core_Reader_Abstract::__init()
	 */
	public function __init() {
		parent::__init();
		$this->id_name = "entity_id";
		$this->collection->addAttributeToFilter("order_id", array("in"=>$this->getAllSyncedOrderIds()));
		return $this;
	}
	
	/**
	 * 
	 * @return NULL[]
	 */
	public function getAllSyncedOrderIds() {
            // current day to start with
            $start = mktime(0,0,0,date('m'), date('d'), date('Y'));;
            
            $before = mktime(0,0,0,date('m',$start),date("d")-1,date('Y',$start));
            
            // calculate the first day of last month
            $first = date('Y-m-d H:i:s',$before);
                       
            // calculate the last day of last month
            $last = date('Y-m-d H:i:s',mktime(0, 0, 0, date('m'), date('d')+1, date('Y',$start)));

            $class = get_class(Mage::getModel("sales/order"));
            $dc = Mage::getModel("sales/order")
                ->getCollection();
                
            
            if(!Mage::registry('salesforce-update')):
                $dc->addFieldToFilter('updated_at', array(
                    'from' => $first,
                    'to' => $last,
                    'date' => true,
                ));
            endif;
           
                //->load();
        $result = array();
        foreach ($dc as $d) {
            $result[] = $d->getData("entity_id");
        }
        
        return $result;
    }
	
}