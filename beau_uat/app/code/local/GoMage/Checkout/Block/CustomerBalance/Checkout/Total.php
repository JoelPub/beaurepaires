<?php
/**
 * GoMage LightCheckout Extension
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2014 GoMage (http://www.gomage.com)
 * @author       GoMage
 * @license      http://www.gomage.com/license-agreement/  Single domain license
 * @terms of use http://www.gomage.com/terms-of-use
 * @version      Release: 5.7
 * @since        Class available since Release 4.0
 */

class GoMage_Checkout_Block_CustomerBalance_Checkout_Total extends Enterprise_CustomerBalance_Block_Checkout_Total {
	
	protected function _construct() {		
		if ($this->getRequest()->getRouteName() == 'gomage_checkout' && $this->getRequest()->getControllerName() == 'onepage') {
			$h = Mage::helper('gomage_checkout');
			if (( bool ) $h->getConfigData('general/enabled')) {
				$this->_template = 'gomage/checkout/customerbalance/checkout/total.phtml';
			}
		}
		parent::_construct();
	}
}
