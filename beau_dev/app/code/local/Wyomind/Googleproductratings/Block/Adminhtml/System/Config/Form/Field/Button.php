<?php

class Wyomind_Googleproductratings_Block_Adminhtml_System_Config_Form_Field_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /*
     * Set template
     */

    protected function _construct() 
    {
        parent::_construct();
       
        $this->setTemplate('productratings/button.phtml');
    }

    /**
     * Return element html
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element) 
    {
        return $this->_toHtml();
    }

    /**
     * Return ajax url for button
     *
     * @return string
     */
    public function getActionUrl() 
    {
        $websiteCode = $this->getRequest()->getParam('website');
        $storeCode = $this->getRequest()->getParam('store');
        return Mage::helper('adminhtml')->getUrl('adminhtml/googleproductratings/generate', array("website" => $websiteCode, "store" => $storeCode));
    }

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml() 
    {
       
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                    'id' => 'googleratings_button',
                    'label' => $this->helper('adminhtml')->__('Generate now!'),
                    'onclick' => 'javascript:generate(); return false;'
                    )
                );
        
        return $button->toHtml();
    }

}
