<?php

class BuyMerchantRequestResponseParameter {

    public static $MERCHANT_ID = "merchantID";
    public static $REFERENCE_CODE = "merchantReferenceCode";
    public static $GRAND_TOTAL = "purchaseTotals_grandTotalAmount";
    public static $CURRENCY = "purchaseTotals_currency";
    public static $ITEM_COUNT = "itemCount";
    public static $PROMO_CODE = "promoCode";
    public static $PRODUCT_CODE = "item_{}_productCode";
    public static $TOTAL_AMOUNT = "item_{}_totalAmount";
    public static $PAGE_TIMESTAMP = "orderPage_timestamp";
    public static $PAGE_PUBLIC_SIGNATURE = "orderPage_signaturePublic";
    public static $PAGE_SIGNED_FIELDS = "orderPage_signedFields";
    public static $PAGE_VERSION = "orderPage_version";
    public static $PAGE_SERIAL_NUMBER = "orderPage_serialNumber";
    public static $HOP_DATA = "eapps_hopData";
    public static $AUTH_TYPE = "authorizationType";
    public static $AUTH_SERVICE_RUN = "ccAuthService_run";
    public static $CARD_ACCOUNT_NUMBER = "card_accountNumber";
    public static $CARD_EXP_YEAR = "card_expirationYear";
    public static $CARD_CV_NUMBER = "card_cvNumber";
    public static $CARD_EXP_MONTH = "card_expirationMonth";
    public static $AUTH_REPLY_CVCODE = "ccAuthReply_cvCode";
    public static $SIGNED_PUBLIC_SIGNATURE = "signedDataPublicSignature";
    public static $REASON_CODE = "reasonCode";
    public static $DECISION = "decision";
    public static $AUTH_REPLY_REASONCODE = "ccAuthReply_reasonCode";
    public static $AUTH_REPLY_CAVV_RESPONSE_CODE = "ccAuthReply_cavvResponseCode";
    public static $AUTH_REPLY_PROCESSOR_RESPONSE = "ccAuthReply_processorResponse";
    public static $AUTH_REPLY_AUTHORIZATION_TIME = "ccAuthReply_authorizedDateTime";
    public static $SIGNED_FIELDS = "signedFields";
    public static $AUTH_REPLY_CPS_TOKEN = "ccAuthReply_cpsToken";
    public static $REASON_MESSAGE = "reasonMessage";
    public static $AUTH_REPLY_AMOUNT = "ccAuthReply_amount";
    public static $AUTH_REPLY_PHONE_NUMBER = "ccAuthReply_phoneNumber";
    public static $SIGNED_FIELDS_PUBLIC_SIGNATURE = "signedFieldsPublicSignature";

}
