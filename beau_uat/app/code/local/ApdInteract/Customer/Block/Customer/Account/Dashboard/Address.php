<?php
class ApdInteract_Customer_Block_Customer_Account_Dashboard_Address extends Mage_Customer_Block_Account_Dashboard_Address
{
   
    public function getPrimaryShippingAddressEditUrl()
    {
        return Mage::getUrl('account/contacts/edit', array('id'=>$this->getCustomer()->getDefaultShipping()));
    }

    public function getPrimaryBillingAddressEditUrl()
    {
        return Mage::getUrl('account/contacts/edit', array('id'=>$this->getCustomer()->getDefaultBilling()));
    }

    public function getAddressBookUrl()
    {
        return $this->getUrl('account/contacts/');
    }
}