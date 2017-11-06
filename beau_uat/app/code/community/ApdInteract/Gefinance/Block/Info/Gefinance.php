<?php

class ApdInteract_Gefinance_Block_Info_Gefinance extends Mage_Payment_Block_Info
{
  protected function _prepareSpecificInformation($transport = null)
  {
    if (null !== $this->_paymentSpecificInformation) 
    {
      return $this->_paymentSpecificInformation;
    }
     
    $data = array();
    if ($this->getInfo()->getCustomFieldOne()) 
    {
      $data[Mage::helper('payment')->__('Payment Method')] = $this->getInfo()->getGeMethod();
    }
     
    if ($this->getInfo()->getCustomFieldTwo()) 
    {
      $data[Mage::helper('payment')->__('Payment Term')] = $this->getInfo()->getGeTerm();
    }
 
    $transport = parent::_prepareSpecificInformation($transport);
     
    return $transport->setData(array_merge($data, $transport->getData()));
  }
}