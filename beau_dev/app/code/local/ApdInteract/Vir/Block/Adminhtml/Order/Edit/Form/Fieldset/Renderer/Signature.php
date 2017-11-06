<?php
/**
 * File path:
 * magento_root/app/code/local/Youramespace/Yourextensionname/Block/Adminhtml/Entityname/Edit/Form/Renderer/Fieldset/Customtype.php
 */
class ApdInteract_Vir_Block_Adminhtml_Order_Edit_Form_Renderer_Fieldset_Signature extends Varien_Data_Form_Element_Abstract
{
 protected $_element;
 
 public function getElementHtml()
 {
     $elementHtml = parent::getElementHtml();
     $itemvalue = $this->getValue();
     // Do something with the value
     //$this->getData('value')
     //$elementHtml = '';
  
    return $elementHtml;
 }
 
}
