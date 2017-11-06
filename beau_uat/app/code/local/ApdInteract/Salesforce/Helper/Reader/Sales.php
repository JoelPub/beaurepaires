<?php

class ApdInteract_Salesforce_Helper_Reader_Sales 
extends ApdInteract_Salesforce_Helper_Core_Reader_Abstract {
	
	public function __construct() {
		$this->model_name = "sales/order";
		parent::__construct();		
	}
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see ApdInteract_Salesforce_Helper_Core_Reader_Abstract::__init()
	 */
	public function __init() {
        parent::__init();
                
        $start = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        ;

        $before = mktime(0, 0, 0, date('m', $start), date("d") - 1, date('Y', $start));

        // calculate the first day of last month
        $first = date('Y-m-d H:i:s', $before);

        // calculate the last day of last month
        $last = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') + 1, date('Y', $start)));
        
        $this->collection->addAttributeToSelect('*');
        if(!Mage::registry('salesforce-update')):
            $this->collection->addFieldToFilter('updated_at', array(
                'from' => $first,
                'to' => $last,
                'date' => true
            ));
        endif;
               
    }

}