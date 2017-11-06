<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'app/Mage.php';

Mage::app();

echo "<pre>";


$order = Mage::getModel('sales/order')->loadByIncrementId(301773);

//Prepare Costar Order Info
$costarFieldsArray = Mage::helper('costar/api')->prepareCostarOrderInfo($order);

// Make a Costar SubmitOrder Api call
$result = Mage::getModel('apdinteract_costar/api')->submitOrder($costarFieldsArray[0],$costarFieldsArray[1]);

//Update Order status and History on API response
if($result['error']){
    Mage::helper('costar/api')->costarRejectedHistory($order,$result['message']);
}else{
    Mage::helper('costar/api')->costarAcceptedHistory($order,$result['message']);
}


die();
//Test Signature And Encryption
//$result = Mage::getModel('apdinteract_costar/api')->testSignatureAndEncryption();
//print_r($result);
//die();


//$result = Mage::getModel('apdinteract_costar/api')->submitOrder();
//print_r($result);

//die();



//get Order info from order Id:
//$order = Mage::getModel('sales/order')->load(301773);
//Increment Id
$order = Mage::getModel('sales/order')->loadByIncrementId(301773);

//print_r($order->getBillingAddress()->getData());
//print_r($order->getData());
//die();

$payment = $order->getPayment();
$billingAddress = $order->getBillingAddress();

$paymentReference = $payment->getData('last_trans_id');
$paymentMethod = $payment->getData('method');

// get Vehicle Id, Customer have account, from customer_vehicle table
$vehicleId = "";
$customerId = $order->getData('customer_id');
$registration = $order->getData('registration_number');
if(!empty($customerId)){
    $customerVehiclInfo = Mage::helper('apdinteract_vehicle')->checkIfVehicleIsExisting($customerId,$registration);
    if ($customerVehiclInfo->getSize() > 0){
        $data = $customerVehiclInfo->getFirstItem();
        $vehicleId = $data['vehicle_id'];
    }
}

//Get Business and Mobile phone from address fields using billing address Id
$billingAddressId = $billingAddress->getData('customer_address_id');
$businessPhone = $mobilePhone = "";
if(!empty($billingAddressId)){
    $customerAddress = Mage::getModel('customer/address')->load($billingAddressId);
    $businessPhone = $customerAddress->getData('business_phone');
    $mobilePhone = $customerAddress->getData('mobile');
}

//Get State full name via region Id
$regionId = $billingAddress->getData('region_id');
$state = Mage::helper('costar/api')->getStateByRegionId($regionId);

//Convert date time to ISO8601 format
$orderDate = $order->getData('created_at');
$orderDate = str_replace("+",".",date(DATE_ISO8601, strtotime($orderDate)));
$dateExplode = explode("T",$orderDate);
$modifiedData = $dateExplode[0]."T00:00:00.0000";
//print_r($dateExplode[0]);
//die();

//Get Order Items
$allItems = $order->getAllItems();

$SequenceNumber = 1;
$itemArray = $skuArray = array();
foreach ($allItems as $item) {

    $sku = $item->getData('sku');
    if (in_array($sku, $skuArray)) {
        continue;
    }
    $itemArray[] = array(
        "Item"=> $sku,
        "ItemType"=> "I",
        "SequenceNumber"=> $SequenceNumber,
        "OrderQuantity"=> (int)$item->getData('qty_ordered'),
        "UnitNetTaxIn"=> $item->getData('price_incl_tax'),
    );

    $skuArray[] = $sku;
    $SequenceNumber++;
}

    $orderArray =array (
    "Address1"=> $billingAddress->getData('street'),
    "Address2"=> "",
    "BusinessPhone"=> $businessPhone,
    "City"=> $billingAddress->getData('city'),
    "Contact"=> $billingAddress->getData('firstname').' '.$billingAddress->getData('lastname'),
    "CustomerNumber"=> "",            // currenty empty will be added when issue fixed from coster end
    "Email"=> $order->getData('customer_email'),
    "GivenName"=> $billingAddress->getData('firstname'),
    "HomePhone"=> $billingAddress->getData('telephone'),
    "MobilePhone"=> $mobilePhone,
    "Name"=> $billingAddress->getData('lastname'),
    "OrderDate"=> $modifiedData, //$orderDate,
    "PaymentAmount"=> $order->getData('grand_total'),
    "PaymentDescription"=> $paymentMethod,    //"Card **** **** **** 2384",
    "PaymentReference"=> $paymentReference,
    "PoNumber"=> $order->getData('increment_id'),
    "PostalCode"=> $billingAddress->getData('postcode'),
    "State"=> $state,
    "VehicleId"=> "",  //$vehicleId,  currently empty, will be added when issue fixed from costar end
    "VehicleLicense"=> $order->getData('registration_number'),
    "VehicleMake"=> "Abarth", //$order->getData('vmake'), entry not found in order table
    "VehicleModel"=>"124 Spider", //$order->getData('vmodel'), entry not found in order table
    "VehicleYear"=> "2017", //$order->getData('year'),
    "OrderDetailList"=> $itemArray );

    $fieldsArray = array("costarLiveId" => "8200",
    "branchPassword" => "U163",
    "branchCode" => "U163"
    );

print_r($orderArray);
//die();

$result = Mage::getModel('apdinteract_costar/api')->submitOrder($fieldsArray,$orderArray);
print_r($result);
die();
//Test StockQuery
$stockQueryFieldsArray = array("costarLiveId" => "8200",
    "branchPassword" => "U163",
    "branchCode" => "U163",
    "itemSku" => "528469");
$result = Mage::getModel('apdinteract_costar/api')->stockQuery($stockQueryFieldsArray);

print_r($result);

echo "\n";

echo "Done";
