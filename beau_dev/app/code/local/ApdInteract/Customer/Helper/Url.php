<?php
class ApdInteract_Customer_Helper_Url extends Mage_Customer_Helper_Data
{
    public function getAccountUrl()
    {
        return $this->_getUrl('account/dashboard');
    }
    
     public function getDashboardUrl()
    {
        return $this->_getUrl('account/dashboard');
    }
}
?>