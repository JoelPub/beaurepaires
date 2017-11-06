<?php
class ApdInteract_Gefinance_Block_Form_Gefinance extends Mage_Payment_Block_Form
{
  protected function _construct()
  {
    parent::_construct();
    $this->setTemplate('gefinance/form/gefinance.phtml');
  }
}