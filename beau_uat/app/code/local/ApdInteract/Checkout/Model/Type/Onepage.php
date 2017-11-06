<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition End User License Agreement
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magento.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Checkout
 * @copyright Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license http://www.magento.com/license/enterprise-edition
 */

/**
 * One page checkout processing model
 */
class ApdInteract_Checkout_Model_Type_Onepage extends Mage_Checkout_Model_Type_Onepage
{
	public function saveOrder()
	{
		$this->validate();
		$isNewCustomer = false;
		$sendConfirmationEmailtoGuest = Mage::getSingleton("core/session")->getCreateAccount();
		switch ($this->getCheckoutMethod()) {
			case self::METHOD_GUEST:
				// added checking to allow sending of 'welcome email' when (guest) customer ticked 'Create Account for me' in checkout page'
				if ($sendConfirmationEmailtoGuest){
					$this->_prepareNewCustomerQuote();
				}else{
					$this->_prepareGuestQuote();
				}
				
				break;
			case self::METHOD_REGISTER:
				$this->_prepareNewCustomerQuote();
				$isNewCustomer = true;
				break;
			default:
				$this->_prepareCustomerQuote();
				break;
		}
	
		$service = Mage::getModel('sales/service_quote', $this->getQuote());
		$service->submitAll();
		
		if ($isNewCustomer || $sendConfirmationEmailtoGuest) {
			try {
				$this->_involveNewCustomer();
			} catch (Exception $e) {
				Mage::logException($e);
			}
		}
	
		$this->_checkoutSession->setLastQuoteId($this->getQuote()->getId())
		->setLastSuccessQuoteId($this->getQuote()->getId())
		->clearHelperData();
	
		$order = $service->getOrder();
		if ($order) {
			Mage::dispatchEvent('checkout_type_onepage_save_order_after',
					array('order'=>$order, 'quote'=>$this->getQuote()));
	
			/**
			 * a flag to set that there will be redirect to third party after confirmation
			 * eg: paypal standard ipn
			*/
			$redirectUrl = $this->getQuote()->getPayment()->getOrderPlaceRedirectUrl();
			/**
			 * we only want to send to customer about new order when there is no redirect to third party
			*/
			if (!$redirectUrl && $order->getCanSendNewEmailFlag()) {
				try {
					$order->queueNewOrderEmail();
				} catch (Exception $e) {
					Mage::logException($e);
				}
			}
	
			// add order information to the session
			$this->_checkoutSession->setLastOrderId($order->getId())
			->setRedirectUrl($redirectUrl)
			->setLastRealOrderId($order->getIncrementId());
	
			// as well a billing agreement can be created
			$agreement = $order->getPayment()->getBillingAgreement();
			if ($agreement) {
				$this->_checkoutSession->setLastBillingAgreementId($agreement->getId());
			}
		}
	
		// add recurring profiles information to the session
		$profiles = $service->getRecurringPaymentProfiles();
		if ($profiles) {
			$ids = array();
			foreach ($profiles as $profile) {
				$ids[] = $profile->getId();
			}
			$this->_checkoutSession->setLastRecurringProfileIds($ids);
			// TODO: send recurring profile emails
		}
	
		Mage::dispatchEvent(
				'checkout_submit_all_after',
				array('order' => $order, 'quote' => $this->getQuote(), 'recurring_profiles' => $profiles)
		);
	
		return $this;
	}
	
}
