<?php

class MerchantRequestParameter {

    // Merchant Input Parameters
    public static $IN_DATA_BLOCK = "merchant_eappsData";
    public static $MERCHANT_ID = "merchantID";
    public static $SOURCE_FLAG = "source";
    public static $CARD_TYPE = "cardType";
    public static $IP_ADDRESS = "ipAddress";
    public static $RETURN_URL = "returnURL";
    public static $STREAM = "stream";
    public static $CHANNEL = "channel";
    public static $GEMID1 = "gemid1";
    public static $FIRST_NAME = "firstName";
    public static $LAST_NAME = "lastName";
    public static $UNIT_NO = "unitNo";
    public static $STREET_NO = "streetNo";
    public static $PROPERTY_STREET_NAME = "streetName";
    public static $STREET_TYPE = "streetType";
    public static $CITY_TOWN_SUBURB = "city";
    public static $STATE = "state";
    public static $POST_CODE = "postCode";
    public static $MERCHANT_FIELD1 = "mField1";
    public static $MERCHANT_FIELD2 = "mField2";
    public static $MERCHANT_FIELD3 = "mField3";
    public static $MERCHANT_FIELD4 = "mField4";
    // Merchant Output Parameters
    public static $MERCHANT_OUT_DATA_BLOCK = "eapps_merchantData";
    public static $HOP_OUT_DATA_BLOCK = "eapps_hopData";
    public static $PARAM_VALID = "validationFlag";
    public static $APPLICATION_STATUS = "eapps_appStatus";
    public static $ACCOUNT_NUMBER = "eapps_accountNumber";
    public static $AVAILABLE_CREDIT_LIMIT = "eapps_creditLimit";
    public static $SECURE_TOKEN = "eapps_geToken";
    public static $TITLE = "title";
    public static $CONTACT_NO_HOME = "contactNo";
    public static $MOBILE_NO = "mobileNo";
    public static $EMAIL = "email";
    public static $PROCESS = "process";
    private $MANDATORY = "M";
    private $OPTIONAL = "O";

    public function getParametermap() {
        $parameterMap = array(
            self::$CARD_TYPE,
            self::$IP_ADDRESS,
            self::$RETURN_URL,
            self::$STREAM,
            self::$CHANNEL,
            self::$GEMID1,
            self::$FIRST_NAME,
            self::$LAST_NAME,
            self::$UNIT_NO,
            self::$STREET_NO,
            self::$PROPERTY_STREET_NAME,
            self::$STREET_TYPE,
            self::$CITY_TOWN_SUBURB,
            self::$STATE,
            self::$POST_CODE,
            self::$MERCHANT_FIELD1,
            self::$MERCHANT_FIELD2,
            self::$MERCHANT_FIELD3,
            self::$MERCHANT_FIELD4
        );
        return $parameterMap;
    }

    public function getParameterfieldtypemap() {
        $parameterFieldTypeMap = array(
            self::$MERCHANT_ID => $this->MANDATORY,
            self::$SOURCE_FLAG => $this->MANDATORY,
            self::$CARD_TYPE => $this->MANDATORY,
            self::$IP_ADDRESS => $this->MANDATORY,
            self::$RETURN_URL => $this->MANDATORY,
            self::$STREAM => $this->MANDATORY,
            self::$CHANNEL => $this->MANDATORY,
            self::$GEMID1 => $this->MANDATORY,
            self::$FIRST_NAME => $this->OPTIONAL,
            self::$LAST_NAME => $this->OPTIONAL,
            self::$UNIT_NO => $this->OPTIONAL,
            self::$STREET_NO => $this->OPTIONAL,
            self::$PROPERTY_STREET_NAME => $this->OPTIONAL,
            self::$STREET_TYPE => $this->OPTIONAL,
            self::$CITY_TOWN_SUBURB => $this->OPTIONAL,
            self::$STATE => $this->OPTIONAL,
            self::$POST_CODE => $this->OPTIONAL,
            self::$MERCHANT_FIELD1 => $this->OPTIONAL,
            self::$MERCHANT_FIELD2 => $this->OPTIONAL,
            self::$MERCHANT_FIELD3 => $this->OPTIONAL,
            self::$MERCHANT_FIELD4 => $this->OPTIONAL
        );
        return $parameterFieldTypeMap;
    }

}