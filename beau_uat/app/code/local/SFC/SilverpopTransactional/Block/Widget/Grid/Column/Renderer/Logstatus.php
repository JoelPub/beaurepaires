<?php
/**
 * StoreFront Consulting Silverpop Module
 *
 * @category	SFC
 * @package    	SFC_Silverpop
 * @author     	StoreFront Consulting, Inc.
 * @website	 	http://www.storefrontconsulting.com
 * @copyright   Copyright © 2009-2012 StoreFront Consulting, Inc. All Rights Reserved.
 */


class SFC_SilverpopTransactional_Block_Widget_Grid_Column_Renderer_Logstatus extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
	}

	/**
	 * Render
	 * @param Varien_Object $row
	 * @return string
	 */
	public function render(Varien_Object $oRow)
	{
		return $this->_getValue($oRow);
	}

	/**
	 * Get value
	 * @param Varien_Object $row
	 * @return string
	 */
	protected function _getValue(Varien_Object $oRow)
	{
		// Get value
        $iValue = intval($oRow->getData($this->getColumn()->getIndex()));

		// Image
		if ($iValue) {
            $sName = SFC_SilverpopTransactional_Model_Logs::getStatusName($iValue);
            $sImage = SFC_SilverpopTransactional_Helper_Images::getLogStatusImage($iValue);
    		return '<img src="' . Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN, Mage::app()->getStore()->isCurrentlySecure()) . 'adminhtml/default/default/silverpoptransactional/img/' . $sImage .'" align="absmiddle" alt="' . $sName . '" title="' . $sName . '"/>&nbsp;&nbsp;' . $sName;
		}

		return '';
	}
}
