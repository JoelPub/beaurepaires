<?php
class ApdInteract_Costar_Model_Log 
    extends Mage_Core_Model_Abstract {

    public function __construct() {
        $this->_init('apdinteract_costar/log');
    }
    
    /**    
    * Will provide dropdown value on select menu for manual importing stock inventory
    *   
    * @return  array    $data      
    */
    public function toOptionArray() {

        $data = array(
                    array("value" => "", "label" => "Please select"),
                    array("value" => "inventory", "label" => "apdinteract_stock_update"),
                    array("value" => "catalog_product", "label" => "Catalog Product"),
                    array("value" => "customer", "label" => "Customer"),
                    array("value" => "order", "label" => "Sales Order"),
                );
        return $data;
    }

}
