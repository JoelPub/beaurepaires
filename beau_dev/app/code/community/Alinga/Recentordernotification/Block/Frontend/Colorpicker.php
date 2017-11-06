<?php
/**
 * Developed by Alinga ECommerce.
 * For more Information, please go to http://www.magentowebdesign.net.au/
 */
 
class Alinga_Recentordernotification_Block_Frontend_Colorpicker
    extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = '<script type="text/javascript" src="' . Mage::getBaseUrl('js') . 'alinga/procolor.compressed.js' .'"></script>';

        $input = new Varien_Data_Form_Element_Text();
		
        $input->setForm($element->getForm())
            ->setElement($element)
            ->setValue($element->getValue())
            ->setHtmlId($element->getHtmlId())
            ->setName($element->getName())
            ->setStyle('width: 60px') // Update style in order to shrink width
            ->addClass('validate-hex'); // Add some Prototype validation to make sure color code is correct

        $html .= $input->getHtml();
        $html .= $this->_getProcolorJs($element->getHtmlId());
        $html .= $this->_addHexValidator();

        return $html;
    }

    protected function _getProcolorJs($htmlId)
    {
        return '<script type="text/javascript">ProColor.prototype.attachButton(\'' . $htmlId . '\', { imgPath:\'' . Mage::getBaseUrl('js') . 'alinga/' . 'img/procolor_win_\', showInField: true });</script>';
    }

    protected function _addHexValidator()
    {
        return '<script type="text/javascript">Validation.add(\'validate-hex\', \'' . Mage::helper('recentordernotification')->__('Please enter a valid hex color code') . '\', function(v) {
				return /^#(?:[0-9a-fA-F]{3}){1,2}$/.test(v);
			});</script>';
    }
}
