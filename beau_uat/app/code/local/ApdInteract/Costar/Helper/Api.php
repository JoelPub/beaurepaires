<?php

/**
 * Costar API Helper
 *
 * Class ApdInteract_Costar_Helper_APi
 * @category	ApdInteract
 * @package		ApdInteract_Costar
 * @author		Jagdeep
 *
 */
class ApdInteract_Costar_Helper_APi extends Mage_Core_Helper_Abstract {

    /**
     * @return boolean
     */
    public function isEnabled() {
        return Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_api/costar_api_enabled');
    }

    /**
     * @return boolean
     */
    public function isDebugModeEnabled() {
        return Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_api/costar_api_debug_mode');
    }

    /**
     * @param string $log
     */
    public function log($log = '') {
        if ($this->isDebugModeEnabled())
            Mage::log($log, null, 'costarapi.log');
    }

    /**
     * BCC-495
     * @return string
     */
    public function getDiscountMaterialCode(){
        return Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_discount_code/material_code');
    }


    /**
     * Get email Id from admin config
     * @return bool|mixed
     */
    public function getEmailAddress() {
        $sendEmail = Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_emails/costar_api_email');
        /*if (!filter_var($sendEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        }*/
        return $sendEmail;
    }

    /**
     * Send Costar API error email
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function sendEmail($subject = "", $message = "", $email = FALSE) {


        if ($email==FALSE):
            $emails = $this->getEmailAddress();
            $this->log('ApdInteract_Costar_Helper_APi::sendEmail Invalid address: ' . $emails.'x'.$email);
        else:
            $emails = $email;
        endif;

        $email_array = explode(",", $emails);
        $count = count($email_array);
        for ($i = 0; $i <= $count; $i++):
            if (isset($email_array[$i])):

                $subject = "Costar api : " . $subject;
                //get Email Id
                $sendToEmailId = trim($email_array[$i]);

                $store_id = 0;
                $mail = Mage::getModel('core/email');
                $mail->setSubject($subject);
                $data['subject_text'] = $subject;
                $mail->setBody($message);

                $mail->setFromName(Mage::getStoreConfig('trans_email/ident_general/name', $store_id));
                $mail->setFromEmail(Mage::getStoreConfig('trans_email/ident_general/email', $store_id));
                $mail->setType('html');

                try {
                    $mail->setToEmail($sendToEmailId);
                    $mail->send();
                    $this->log('ApdInteract_Costar_Helper_APi::send Email to Support Team: ' . $sendToEmailId);
                } catch (Exception $e) {
                    $this->log('ApdInteract_Costar_Helper_APi::send Email to Support Team: - ERROR:');
                    $this->log($e->getMessage());
                }
            endif;
        endfor;
    }

    /**
     *
     * Get Prepared Order fields to send via Costar SubmitOrder API call
     *
     * @param $order
     * @return array
     */
    public function prepareCostarOrderInfo($order) {

        if (is_int($order)) {
            $order = Mage::getModel('sales/order')->load($order);
        }

        //Get Order Billing Address
        $billingAddress = $order->getBillingAddress();
        //Get Order Payment Info
        $payment = $order->getPayment();
        $paymentReference = $payment->getData('last_trans_id');
        if (is_null($paymentReference)) {
            $paymentReference = "";
        }
        $paymentMethod = $payment->getData('method');

        // get Vehicle Id, Customer have account, from customer_vehicle table
        /*
          $vehicleId = "";
          $customerId = $order->getData('customer_id');
          $registration = $order->getData('registration_number');
          if(!empty($customerId)){
          $customerVehiclInfo = Mage::helper('apdinteract_vehicle')->checkIfVehicleIsExisting($customerId,$registration);
          if ($customerVehiclInfo->getSize() > 0){
          $data = $customerVehiclInfo->getFirstItem();
          $vehicleId = $data['vehicle_id'];
          }
          } */

        //Get Costar Customer Id If customer have account
        $costarCustomerId = "";
        $customerId = $order->getData('customer_id');
        if (!empty($customerId)) {
            $_customer = Mage::getModel('customer/customer')->load($customerId);
            $costarCustomerId = $_customer->getData('costar_customer_id');
            if (is_null($costarCustomerId)) {
                $costarCustomerId = "";
            }
        }


        //Get Business and Mobile phone from address fields using billing address Id
        $billingAddressId = $billingAddress->getData('customer_address_id');
        $businessPhone = $mobilePhone = "";
        if (!empty($billingAddressId)) {
            $customerAddress = Mage::getModel('customer/address')->load($billingAddressId);
            $businessPhone = $customerAddress->getData('business_phone');
            $mobilePhone = $customerAddress->getData('mobile');
        }

        //Get State full name via region Id
        //$regionId = $billingAddress->getData('region_id');
        //$state = Mage::helper('costar/api')->getStateByRegionId($regionId);
        //get state code
        $state = $billingAddress->getData('region');

        //Convert date time to ISO8601 format
        $orderDate = $order->getData('created_at');
        $orderDate = str_replace("+", ".", date(DATE_ISO8601, strtotime($orderDate)));
        $dateExplode = explode("T", $orderDate);
        $modifiedData = $dateExplode[0] . "T00:00:00.0000";

        //Get Store Costar Live ID and Branch Password/code
        $storeId = $order->getData('storelocation');
        $storeInfo = $this->getStoreInfoById($storeId);
        if ($state == '' || $state == 'n/a' || $state == 'N/A') // check if state not provided
            $state = strtoupper($this->getStateByRegionId($storeInfo->getData('region_id')));

        $post_code = $billingAddress->getData('postcode');
        if ($post_code == '' || $post_code == 'n/a' || $post_code == 'N/A') //check if post code not provided
            $post_code = $storeInfo->getData('postal_code');

        //Check if GiftCard applied
        $appliedGiftCard = $this->getAppliedGiftCardAmount($order);

        //Get Order Items
        $allItems = $order->getAllVisibleItems();
        $SequenceNumber = 1;

        $specialItems = array('AS_6540008', 'AS_6639996', 'AS_6666997', 'AS_6666971', 'AS_6969991'); // Wheel Alignment and Premium Wheel Alignment (4WD / SUV)

        //$itemCount = $order->getTotalItemCount();
        $itemCount = 0;
        foreach ($allItems as $item) {
            $sku = $item->getData('sku');
            if (!in_array($sku, $specialItems)) {
                $itemCount++;
            }
        }

        $itemArray = $skuArray = array();
        foreach ($allItems as $item) {
            $sku = $item->getData('sku');

            //Check If Shipping Cart Rule Applied, IF yes then get rule type and discount amount
            $shoppingCartRuleInfo = $this->getAppliedShoppingCartRule($item);

            $customOptions = $this->_getCustomOptions($item, $item->getData('qty_ordered'),$shoppingCartRuleInfo);
            if (in_array($sku, $skuArray)) {
                continue;
            }

            if (in_array($sku, $specialItems)) {
                $itemType = 'S';
                $ignoreSpecialItemDiscount = TRUE;
            } else {
                $itemType = 'I';
                $ignoreSpecialItemDiscount = FALSE;
            }

            $itemPrice = $item->getData('price_incl_tax');
            $qty = $item->getData('qty_ordered');

            //Apply Shopping Cart Discount Rule
            if(!empty($shoppingCartRuleInfo) && !$ignoreSpecialItemDiscount){

                foreach ($shoppingCartRuleInfo as $shoppingCartRule) {
                    if($shoppingCartRule['is_percentage']) {
                        $discount = ($itemPrice / 100) * $shoppingCartRule['discount'];
                        $itemPrice = $itemPrice - $discount;
                        $itemPrice = (isset($customOptions['custom_options']) && count($customOptions['custom_options']) > 0) ? $itemPrice - ($customOptions['custom_options_total'] / $qty) : $itemPrice;
                    }else{
                        $itemPrice = $itemPrice - (($shoppingCartRule['discount']/$itemCount)/$qty);
                        $itemPrice = (isset($customOptions['custom_options']) && count($customOptions['custom_options']) > 0) ? $itemPrice - ($customOptions['custom_options_total'] / $qty) : $itemPrice;
                    }
                }
            }

            //When Shopping Cart Discount not applied then use default rule.
            if($ignoreSpecialItemDiscount || empty($shoppingCartRuleInfo)) {
                //compute for unitprice minus 1 qty of custom option
                $itemPrice = (isset($customOptions['custom_options']) && count($customOptions['custom_options']) > 0) ? $itemPrice - ($customOptions['custom_options_total'] / $qty) : $itemPrice;
            }

            $itemArray[] = array(
                "Item" => ($itemType == 'I') ? $customOptions['sap_code'] : $sku,
                "ItemType" => $itemType,
                "SequenceNumber" => $SequenceNumber,
                "OrderQuantity" => (int) $qty,
                "UnitNetTaxIn" => number_format((float)$itemPrice, 2, '.', ''),
            );

            //product order custom Options
            if (isset($customOptions['custom_options']) && count($customOptions['custom_options']) > 0) {
                foreach ($customOptions['custom_options'] as $option) {
                    $SequenceNumber++;
                    $itemArray[] = array(
                        "Item" => $option['sku'],
                        "ItemType" => 'S',
                        "SequenceNumber" => $SequenceNumber,
                        "OrderQuantity" => (int) $qty,
                        "UnitNetTaxIn" => $option['price'],
                    );
                }
            }

            $skuArray[] = $sku;
            $SequenceNumber++;
        }


        // If gift card applied then send DISC line item : BCC-495
        if($appliedGiftCard['isGiftCardApplied']){
            $itemArray[] = array(
                "Item" => $this->getDiscountMaterialCode(),
                "ItemType" => "S",
                "SequenceNumber" => $SequenceNumber,
                "OrderQuantity" => -1,
                "UnitNetTaxIn" => abs($appliedGiftCard['giftCardAmount'])
            );
        }


        $street = $billingAddress->getData('street');
        $street = preg_replace('~[\r\n]+~', ' ', $street); //address cleanup

        //Prepare Costar Order Array
        $costarOrderInfo = array(
            "Address1" => $street,
            "Address2" => "",
            "BusinessPhone" => $businessPhone,
            "City" => $billingAddress->getData('city'),
            "Contact" => $billingAddress->getData('firstname') . ' ' . $billingAddress->getData('lastname'),
            "CustomerNumber" => $costarCustomerId,
            "Email" => $order->getData('customer_email'),
            "GivenName" => $billingAddress->getData('firstname'),
            "HomePhone" => $billingAddress->getData('telephone'),
            "MobilePhone" => $mobilePhone,
            "Name" => $billingAddress->getData('lastname'),
            "OrderDate" => $modifiedData, //$orderDate,
            "PaymentAmount" => $order->getData('grand_total'),
            "PaymentDescription" => $paymentMethod, //"Card **** **** **** 2384",
            "PaymentReference" => $paymentReference,
            "PoNumber" => $order->getData('increment_id'),
            "PostalCode" => $post_code,
            "State" => $state,
            "VehicleId" => "", // Empty, Because costar Vehicle Id not found in Magento
            "VehicleLicense" => $order->getData('registration_number'),
            "VehicleMake" => $order->getData('vmake'),
            "VehicleModel" => $order->getData('vmodel'),
            "VehicleYear" => $order->getData('year'),
            "OrderDetailList" => $itemArray);

        //Prepeare Costar Live Id request
        $costarLiveIdInfo = array("costarLiveId" => $storeInfo->getPCostarLiveId(),
            "branchPassword" => $storeInfo->getPBranchPassword(),
            "branchCode" => $storeInfo->getCostarStoreCode()
        );

        $return = array($costarLiveIdInfo, $costarOrderInfo);
        return $return;
    }

    /**
     * @param $product
     * @param int $qtyOrdered
     * @return array
     */
    protected function _getCustomOptions($item, $qtyOrdered = 1, $shoppingCartRuleInfo = array()) {
        $totalPrice = 0;
        $data = array();
        $product = Mage::getModel('catalog/product')->load($item->getProduct()->getId());

        if($simpleSku = $item->getProductOptionByCode('simple_sku')) {
            $simpleProductId = Mage::getModel('catalog/product')->getIdBySku($simpleSku);
            $data['sap_code'] = Mage::getModel('catalog/product')->load($simpleProductId)->getSapCode();
        } else {
            $data['sap_code'] = $product->getSapCode();
        }

        $catalogPriceRulePercentage = $this->getAppliedCatalogRule($item);

        foreach($product->getOptions() as $option) {
            if($option->getType() == 'field'){
                $price = $option->getPrice();

                //If Catalog Price Percentage Rule applied on Order item then applied it on Options fields
                if($catalogPriceRulePercentage > 0){
                    $catalogPriceRuleDiscount  = ($price/100)* $catalogPriceRulePercentage;
                    $price = $price - $catalogPriceRuleDiscount;
                }

                //If applied shopping cart rule is based on percentage then applied it on Options fields
                if(!empty($shoppingCartRuleInfo)){
                    foreach ($shoppingCartRuleInfo as $shoppingCartRule) {
                        if($shoppingCartRule['is_percentage']){
                            $discount  = ($price/100)* $shoppingCartRule['discount'];
                            $price = $price - $discount;
                        }
                    }
                }

                $data['custom_options'][] = array(
                    "sku" => $option->getSku(),
                    "price" => number_format((float)$price, 2, '.', ''),
                    "qtyOrdered" => $qtyOrdered,
                );

                $totalPrice += ($price * $qtyOrdered);
            }
        }
        $data['custom_options_total'] = $totalPrice;

        return $data;
    }

    /**
     * When Costar Rejected Order
     * Add Response in Order History and update Order status to Costar Rejected
     */
    public function costarRejectedHistory($_order, $message, $multiple = FALSE) {


        if (empty($_order)) {
            return false;
        }

        if (is_int($_order)) {
            $_order = Mage::getModel('sales/order')->load($_order);
        }

        $failed_email = Mage::getStoreConfig('apdinteract_costar/apdinteract_costar_emails/costar_rejected_email');


        $comment = "Costar Result: Rejected.<br>";
        $comment .= "Order# :" . $_order->getIncrementId() . "<br>";
        if (!empty($message)) {
            if (is_array($message)) {
                $message = implode(", ", $message);
            }
            $comment .= "ERROR: " . $message. "<br><br>";
        }

        if (!$multiple)
            $this->sendEmail("Rejected Order", $comment, $failed_email);

        $_order->setStatus('costar_rejected');
        $_order->addStatusHistoryComment($comment);
        $_order->save();

        return $comment;
    }

    /**
     * When Costar Accepted Order
     * Add Response in Order History and update Order status to Costar Accepted
     */
    public function costarAcceptedHistory($_order, $message) {

        if (empty($_order)) {
            return false;
        }

        if (is_int($_order)) {
            $_order = Mage::getModel('sales/order')->load($_order);
        }

        $comment = 'Costar Result: Accepted.';
        /* if(!empty($message)) {
          if (is_array($message)) {
          $message = implode(", ", $message);
          }
          $comment = $comment." ".$message;
          } */

        $_order->setStatus('costar_accepted');
        $_order->addStatusHistoryComment($comment);
        $_order->save();
    }

    /**
     * Get State full name via region Id
     * @param $regionId
     * @return mixed
     */
    public function getStateByRegionId($regionId) {
        $region = Mage::getModel('directory/region')->load($regionId);
        return $region->getData('code');
    }

    /**
     * Get Store Information Using Store ID
     *
     * @param $id
     * @return mixed
     */
    public function getStoreInfoById($id) {
        $store = Mage::getModel('storelocator/stores')->load($id);
        return $store;
    }

    /**
     * Check if Gift Card applied in Order : BCC-495
     * @param $_order
     * @return bool
     */
    public function isGiftCardApplied($_order){

        if(!is_null($_order->getData('discount_amount')) && strpos($_order->getDiscountDescription(), 'Giftcard') !== false){
            return true;
        }

        return false;
    }

    /**
     * Get Applied Gift Card Amount
     * @param $_order
     */
    public function getAppliedGiftCardAmount($_order){
        $giftcard = array('isGiftCardApplied' => false);
        $giftCardAmount = 0;
        $orderIncrementId = $_order->getIncrementId();
        $orderIncrementIdArray = explode("-",$orderIncrementId);
        $giftCardInfo = Mage::getModel('kartparadigm_giftcard/giftcardtrans')->getCollection()->addFieldToFilter('order_id',$orderIncrementIdArray[0]);
        $count = $giftCardInfo->getSize();
        if($count>0){
            $giftCardData = $giftCardInfo->getData();
            foreach($giftCardData as $giftCard){
                $giftCardAmount += $giftCard['giftcard_balused'];
            }

            $giftcard['isGiftCardApplied'] = true;
            $giftcard['giftCardAmount'] = $giftCardAmount;
            return $giftcard;
        }else{
            return $giftcard;
        }


    }

    /**
     * Get Applied Shopping Cart Discount type and amount.
     * @param $item object
     * @return array
     */
    public function getAppliedShoppingCartRule($item){

        $return = array();
        if(!is_null($ruleIdStr = $item->getData('applied_rule_ids'))){
            $ruleIds = explode(',',$ruleIdStr);
            foreach($ruleIds as $ruleId){
                $_rule = Mage::getModel('salesrule/rule')->load($ruleId);
                if($_rule->getDiscountAmount() <= 0 || $_rule->getSimpleAction() == 'bundled_price'){
                    continue;
                }
                if($_rule->getSimpleAction() == 'by_percent') {
                    $return[$ruleId]['is_percentage'] = true;
                    $return[$ruleId]['discount'] = $_rule->getDiscountAmount();
                }else{
                    $return[$ruleId]['is_percentage'] = false;
                    $return[$ruleId]['discount'] = (int)$_rule->getDiscountAmount();
                }
            }

            return $return;

        }

        return $return;
    }

    /**
     *
     * Tricky method to find Applied Catalog price percentage rule on Order Item
     *
     * @param $item
     * @return mixed
     */
    public function getAppliedCatalogRule($item) {
        $resource = Mage::getSingleton('core/resource');
        $connection = $resource->getConnection('core_read');
        $tableName = $resource->getTableName('catalogrule_product');
        $catalogRuleAmount = $connection->fetchRow('SELECT action_amount FROM '.$tableName.' WHERE action_operator = "by_percent" and product_id = '. $item->getProductId(). ' limit 1');
        if (!empty($catalogRuleAmount)) {
            return $catalogRuleAmount['action_amount'];
        }
        return false;
    }


    /**
     * Check if API error email trigger based on following condition
     * Send 1 email per hour.
     */
    public function canSendApiErrorEmail() {

        $time = $this->getEmailTriggerTime();
        if(!empty($time)){
            $currentTIme = time();
            $emailIntervalTime = $this->getEmailIntervalTime();
            $diff = abs( $currentTIme - $time);
            if($diff <= $emailIntervalTime){
                return false;
            }
        }

        return true;
    }


    /**
     * Get Email Interval Time in Seconds
     * @return int
     */
    public function getEmailIntervalTime() {

        $timeInSeconds = 3600;
        $emailIntervalTime = Mage::getStoreConfig('apimonitoring/costarapi/email_interval_time');
        if(!empty($emailIntervalTime) || is_numeric($emailIntervalTime)){
            $timeInSeconds = $emailIntervalTime * 60;
        }

        return $timeInSeconds;

    }


    /**
     * Get Email Last trigger time
     * Used direct query in DB because if use getConfig fucntion then its return value from cache.
     *
     * @return mixed
     *
     */
    public function getEmailTriggerTime() {

        $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
        $query = "SELECT value FROM core_config_data WHERE path = 'apimonitoring/costarapi/email_trigger_time'";
        $time  = $connection->fetchOne($query);
        return $time;

    }

    /**
     * Set Email trigger time
     *
     * @return mixed
     *
     */
    public function setEmailTriggerTime() {

        Mage::getConfig()->saveConfig('apimonitoring/costarapi/email_trigger_time', time());
    }

}
