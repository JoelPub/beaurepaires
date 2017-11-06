<?php

class ApdInteract_Customer_Block_Customer_Account_Navigation extends Mage_Customer_Block_Account_Navigation
{
    private $exclude_links = array();
    
    /* Rewrite Mage_Customer_Block_Customer_Account_Navigation
     * Exclude mobility link on customer account navigation if not subscribe on mobility group

     * @return object
     */
    public function addLink($name, $path, $label, $urlParams=array())
    { 

        $helper = Mage::helper('apdinteract_customer');


        if(!$helper->mobilitySubscriber()){
            $this->exclude_links[] = 'mobility';
        }

        if (!in_array($name, $this->exclude_links)) {
            return parent::addLink($name, $path, $label, $urlParams);
        }
    }

}

?>