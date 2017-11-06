<?php

class MerchantReponseParameter {

    public static $MERCHANT_OUT_DATA_BLOCK = "eapps_merchantData";
    public static $HOP_OUT_DATA_BLOCK = "eapps_hopData";
    public static $PARAM_VALID = "validationFlag";
    public static $APPLICATION_STATUS = "eapps_appStatus";
    public static $ACCOUNT_NUMBER = "eapps_accountNumber";
    public static $AVAILABLE_CREDIT_LIMIT = "eapps_creditLimit";
    public static $SECURE_TOKEN = "eapps_geToken";
    public static $PROCESS = "process";
    public static $MERCHANT_FIELD1 = "mField1";
    public static $MERCHANT_FIELD2 = "mField2";
    public static $MERCHANT_FIELD3 = "mField3";
    public static $MERCHANT_FIELD4 = "mField4";
    public static $TITLE = "title";
    public static $FIRST_NAME = "firstName";
    public static $LAST_NAME = "lastName";
    public static $UNIT_NO = "unitNo";
    public static $STREET_NO = "streetNo";
    public static $PROPERTY_STREET_NAME = "streetName";
    public static $STREET_TYPE = "streetType";
    public static $CITY_TOWN_SUBURB = "city";
    public static $STATE = "state";
    public static $POST_CODE = "postCode";
    public static $CONTACT_NO_HOME = "contactNo";
    public static $MOBILE_NO = "mobileNo";
    public static $EMAIL = "email";

    public function getParametermap() {
        $parameterMap = array(
            self::$APPLICATION_STATUS,
            self::$ACCOUNT_NUMBER,
            self::$AVAILABLE_CREDIT_LIMIT,
            self::$SECURE_TOKEN,
            self::$PROCESS,
            self::$MERCHANT_FIELD1,
            self::$MERCHANT_FIELD2,
            self::$MERCHANT_FIELD3,
            self::$MERCHANT_FIELD4,
            self::$TITLE,
            self::$FIRST_NAME,
            self::$LAST_NAME,
            self::$UNIT_NO,
            self::$STREET_NO,
            self::$PROPERTY_STREET_NAME,
            self::$STREET_TYPE,
            self::$CITY_TOWN_SUBURB,
            self::$STATE,
            self::$POST_CODE,
            self::$CONTACT_NO_HOME,
            self::$MOBILE_NO,
            self::$EMAIL
        );

        return $parameterMap;
    }

}