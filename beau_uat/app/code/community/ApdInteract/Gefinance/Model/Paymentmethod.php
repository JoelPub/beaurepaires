<?php
class ApdInteract_Gefinance_Model_Paymentmethod extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'gefinance';
    protected $_formBlockType = 'gefinance/form_gefinance';
    protected $_infoBlockType = 'gefinance/info_gefinance';

    public function assignData($data)
    {
        $info = $this->getInfoInstance();



        if ($data->getGeTerm()) {
            $info->setGeTerm($data->getGeTerm());
        }

        return $this;
    }

    public function validate()
    {

        $info       = $this->getInfoInstance();
        $grandtotal = Mage::getModel('checkout/cart')->getQuote()->getGrandTotal();
        $errorMsg   = "";


        if (!$info->getGeTerm()) {
            $errorCode = 'invalid_data';
            $errorMsg .= $this->_getHelper()->__('Payment Term is a required field.');

        } else {
            $term = $info->getGeTerm();

            $minimumPurchaseAmt = Mage::helper('gefinance')->getConfig('ge_' . $term . 'mo_min');
            $minimumPurchase    = Mage::helper('core')->currency($minimumPurchaseAmt, TRUE, FALSE);
            if ($minimumPurchaseAmt > $grandtotal) {
                $errorCode = 'invalid_data';
                $errorMsg .= $this->_getHelper()->__('Minimum purchase for Payment Term ' . $term . ' months is atleast ' . $minimumPurchase . '.');
            }
        }

        if ($errorMsg != '') {
            Mage::throwException($errorMsg);
        }

        return $this;
    }

    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('gefinance/payment/redirect', array(
            '_secure' => false
        ));
    }

    public function getStandardCheckoutFormFields()
    {
        $orderIncrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
        $order            = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        $payment          = $order->getPayment();
        $term             = $payment->getGeTerm();

        
        $post_value                      = array();
        $merchantID                      = Mage::helper('gefinance')->getConfig('merchant_id');
        $merchantReferenceCode           = $orderIncrementId;
        $orderPage_serialNumber          = Mage::helper('gefinance')->getConfig('ge_serial');
        $orderPage_version               = ApdInteract_Gefinance_Model_Variables::ORDERPAGE_VERSION_DATA;
        $merchant_eappsData              = Mage::helper('gefinance')->getMerchantEappsData(); // Mage::helper('gefinance')->getConfig('ge_key'); // ge_key is public key, other is AES-encrypted
        $orderPage_timestamp             = Mage::helper('gefinance')->getmicrotime();
        $purchaseTotals_grandTotalAmount = $order->getGrandTotal();
        $purchaseTotals_currency         = ApdInteract_Gefinance_Model_Variables::PURCHASETOTALS_CURRENCY_DATA;
        $promoCode                       = Mage::helper('gefinance')->getConfig('ge_' . $term . 'mo');
        $promoDescription                = $term . ' Months Interest Free With Repayments';
        $ordered_items                   = $order->getItemsCollection();
        $discount                        = $order->getDiscountAmount();

        $i          = 0;
        $item_count = 0;

        $code = $order->getCouponCode();


        $oCoupon = Mage::getModel('salesrule/coupon')->load($code, 'code');
		$oRule = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
		$rule = $oRule->getData();
		$discount_amount = $rule['discount_amount'];
		$percent_or_fixed = $rule['simple_action'];
		$cart_total = $purchaseTotals_grandTotalAmount - $discount;
		$fixed_discount = @(abs($discount)/$cart_total);

        Mage::getSingleton('core/session')->setPOrderId($merchantReferenceCode);

        $items  = array();
        $fields = "";
        foreach ($ordered_items as $item) { //item detail

            $options = $item->getProductOptions();
            $unit    = $item->getPriceInclTax();
            $qty = abs($item->getQtyOrdered());
            $price   = $unit * $qty;
            $rowTotal = $item->getRowTotalInclTax();
            //$extra = $rowTotal - $price;

            if($discount<0) {
				if($percent_or_fixed=='by_percent') {
					$item_discount = round($rowTotal * ($discount_amount/100), 2, PHP_ROUND_HALF_UP);
            		$price = $rowTotal - $item_discount;
				}else {

            		$item_discount = round($rowTotal * $fixed_discount,2, PHP_ROUND_HALF_UP);
					$price = $rowTotal - $item_discount;
				}

			} else {
				$price   = $rowTotal;
			}


            if ($price > 0) {

                $items[] = array(
                    "item_" . $i . "_productCode" => '1300',
                    "item_" . $i . "_productDescription" => $qty.'x '.$item->getName() . ' ' . $options['attributes_info'][0]['value'],
                    "item_" . $i . "_totalAmount" => $price
                );
                $fields .= "item_" . $i . "_productCode," . "item_" . $i . "_productDescription," . "item_" . $i . "_totalAmount";

                $item_count += $qty;
                $i++;

            }
        }




        $itemCount = $item_count;


        $post_value = array(

            ApdInteract_Gefinance_Model_Variables::MERCHANTID => $merchantID,
            ApdInteract_Gefinance_Model_Variables::MERCHANTREFERENCECODE => $merchantReferenceCode,
            ApdInteract_Gefinance_Model_Variables::ORDERPAGE_SERIALNUMBER => $orderPage_serialNumber,
            ApdInteract_Gefinance_Model_Variables::ORDERPAGE_VERSION => $orderPage_version,
            ApdInteract_Gefinance_Model_Variables::ORDERPAGE_TIMESTAMP => $orderPage_timestamp,
            ApdInteract_Gefinance_Model_Variables::PURCHASETOTALS_GRANDTOTALAMOUNT => $purchaseTotals_grandTotalAmount,
            ApdInteract_Gefinance_Model_Variables::PURCHASETOTALS_CURRENCY => $purchaseTotals_currency,
            ApdInteract_Gefinance_Model_Variables::ITEMCOUNT => $itemCount,
            ApdInteract_Gefinance_Model_Variables::PROMOCODE => $promoCode,
            ApdInteract_Gefinance_Model_Variables::PROMODESCRIPTION => $promoDescription,
            ApdInteract_Gefinance_Model_Variables::MERCHANT_EAPPSDATA => $merchant_eappsData
        );

        $eapps_hopdata = Mage::helper('gefinance')->getHopDataFromSession();
        if (!empty($eapps_hopdata)) {
            $post_value[ApdInteract_Gefinance_Model_Variables::EAPPS_HOPDATA] = $eapps_hopdata;
        }

        $values = array(
            'variables' => $post_value,
            'items' => $items
        );
        return $values;

    }

    public function registerTransaction($paymentStatus, $reasonCode, $reasonMsg, $parentTransactionId, $ccAuthReply_amount)
    {
        $this->_order = null;
        $this->_getOrder($parentTransactionId);

        try {

            // Handle payment_status
            switch ($paymentStatus) {
                // paid
                case ApdInteract_Gefinance_Model_Variables::DECISION_ACCEPT:
                    $this->_registerPaymentCapture(true, $parentTransactionId, $ccAuthReply_amount);
                    break;

                case ApdInteract_Gefinance_Model_Variables::DECISION_REJECT:
                    $this->_registerPaymentDenial($parentTransactionId);
                    break;
                case ApdInteract_Gefinance_Model_Variables::DECISION_CANCELLED:
                    $this->_registerPaymentCancelled($parentTransactionId);
                    break;

                default:
                    $this->_registerPaymentCancelled($parentTransactionId);
            }
        }
        catch (Mage_Core_Exception $e) {
            $comment = $this->_createComment(Mage::helper('gefinance')->__('Note: %s', $e->getMessage()), true);
            $comment->save();
            throw $e;
        }
    }

    protected function _registerPaymentCapture($skipFraudDetection = false, $parentTransactionId, $ccAuthReply_amount)
    {

        $payment = $this->_order->getPayment();
        $term = $payment->getGeTerm();
        Mage::getSingleton('core/session')->setGeterm($term);
        $payment->setTransactionId($parentTransactionId)->setCurrencyCode('AUD')->setPreparedMessage($this->_createComment(''))->setParentTransactionId($parentTransactionId)->setShouldCloseParentTransaction('Completed')->setIsTransactionClosed(0)->registerCaptureNotification($ccAuthReply_amount, $skipFraudDetection && $parentTransactionId);
        $this->_order->save();

        // notify customer
        $invoice = $payment->getCreatedInvoice();
        if ($invoice && !$this->_order->getEmailSent()) {
            $this->_order->queueNewOrderEmail()->addStatusHistoryComment(Mage::helper('gefinance')->__('Notified customer about invoice #%s.', $invoice->getIncrementId()))->setIsCustomerNotified(true)->save();
        }
    }

    protected function _registerPaymentDenial($parentTransactionId)
    {
        $payment = $this->_order->getPayment();

        $payment->setTransactionId($parentTransactionId)->setNotificationResult(true)->setIsTransactionClosed(true);
        if (!$this->_order->isCanceled()) {
            $payment->registerPaymentReviewAction(Mage_Sales_Model_Order_Payment::REVIEW_ACTION_DENY, false);
        } else {

            $comment = Mage::helper('gefinance')->__('Transaction ID: "%s" payment rejected ', $parentTransactionId);
            $this->_order->addStatusHistoryComment($this->_createComment($comment), false);
        }

        $this->_order->save();
    }


    protected function _registerPaymentCancelled($parentTransactionId)
    {
        if ($parentTransactionId > 0) {

            $payment = $this->_order->getPayment();

            $payment->setTransactionId($parentTransactionId)->setNotificationResult(true)->setIsTransactionClosed(true);
            if (!$this->_order->isCanceled()) {
                $payment->registerPaymentReviewAction(Mage_Sales_Model_Order_Payment::REVIEW_ACTION_DENY, false);
            } else {

                $comment = Mage::helper('gefinance')->__('Transaction ID: "%s" has been cancelled ', $parentTransactionId);
                $this->_order->addStatusHistoryComment($this->_createComment($comment), false);
            }

            $this->_order->save();
        }
    }

    protected function _getOrder($parentTransactionId)
    {
        if (empty($this->_order)) {

            $this->_order = Mage::getModel('sales/order')->loadByIncrementId($parentTransactionId);
            if (!$this->_order->getId()) {
                Mage::log(sprintf('Wrong order ID: "%s".', $parentTransactionId));
            }
        }
        return $this->_order;
    }

    protected function _createComment($comment = '', $addToHistory = false)
    {
        $message = "";
        if ($comment) {
            $message .= ' ' . $comment;
        }
        if ($addToHistory) {
            $message = $this->_order->addStatusHistoryComment($message);
            $message->setIsCustomerNotified(null);
        }
        return $message;
    }

    public function reCreateQuoteFromOrder($order_id)
    {



        $this->_order = null;
        $this->_getOrder($order_id);
        $order = $this->_order;

        $quote = Mage::getModel('sales/quote')->load($order->getQuoteId());
        if ($quote->getId()) {

            $quote->setIsActive(1)->save();
            Mage::getSingleton('checkout/session')->replaceQuote($quote);
        }

        $currenQuoteId = Mage::getSingleton('checkout/session')->getQuoteId();
        $OrderQuote    = Mage::getModel('sales/quote')->load($currenQuoteId);

        $id = $currenQuoteId;

        if ($id) {
            $OrderQuote->setIsActive(1)->save();
        }

        if ($OrderQuote->getId() && $currenQuoteId != $OrderQuote->getId()) {
            if ($currenQuoteId) {
                $OrderQuote->merge(Mage::getSingleton('checkout/session')->getQuote())->collectTotals()->save();
            }

            Mage::getSingleton('checkout/session')->setQuoteId($OrderQuote->getId());

            if (Mage::getSingleton('checkout/session')->_quote) {
                Mage::getSingleton('checkout/session')->delete();
            }


        } else {

            Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress();
            Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress();
            Mage::getSingleton('checkout/session')->getQuote()->setCustomer(Mage::getSingleton('customer/session')->getCustomer())->setTotalsCollectedFlag(false)->collectTotals()->save();
        }



    }


}
