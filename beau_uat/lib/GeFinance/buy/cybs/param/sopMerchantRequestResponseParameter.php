<?php
include_once 'web/buy/cybs/param/buyMerchantRequestResponseParameter.php';
class SOPMerchantRequestResponseParameter extends BuyMerchantRequestResponseParameter {
	public static $test = "Helo";
	public static $DATE_OF_BIRTH = "dateOfBirth";
	public static $SMS_CODE = "smsCode";
	public static $REASON_CODE_PUBLIC_SIGNATURE = "reasonCode_publicSignature";
	public static $DECISION_PUBLIC_SIGNATURE = "decision_publicSignature";
	private $MANDATORY = "M";
	private $OPTIONAL = "O";
	public function getParametermap() {
		$parameterMap = array (
				self::$AUTH_SERVICE_RUN,
				self::$MERCHANT_ID,
				self::$REFERENCE_CODE,
				self::$AUTH_TYPE,
				self::$PROMO_CODE,
				self::$ITEM_COUNT,
				self::$PRODUCT_CODE,
				self::$TOTAL_AMOUNT,
				self::$CURRENCY,
				self::$GRAND_TOTAL,
				self::$CARD_ACCOUNT_NUMBER,
				self::$CARD_EXP_MONTH,
				self::$CARD_EXP_YEAR,
				self::$CARD_CV_NUMBER,
				self::$DATE_OF_BIRTH,
				self::$SMS_CODE,
				self::$HOP_DATA,
				self::$PAGE_PUBLIC_SIGNATURE,
				self::$PAGE_SIGNED_FIELDS,
				self::$PAGE_SERIAL_NUMBER,
				self::$PAGE_TIMESTAMP,
				self::$PAGE_VERSION 
		);
		return $parameterMap;
	}
	public function getParameterfieldtypemap() {
		$parameterFieldTypeMap = array (
				self::$AUTH_SERVICE_RUN => $this->MANDATORY,
				self::$MERCHANT_ID => $this->MANDATORY,
				self::$REFERENCE_CODE => $this->MANDATORY,
				self::$AUTH_TYPE => $this->MANDATORY,
				self::$PROMO_CODE => $this->MANDATORY,
				self::$ITEM_COUNT => $this->MANDATORY,
				self::$PRODUCT_CODE => $this->MANDATORY,
				self::$TOTAL_AMOUNT => $this->MANDATORY,
				self::$CURRENCY => $this->MANDATORY,
				self::$GRAND_TOTAL => $this->MANDATORY,
				self::$CARD_ACCOUNT_NUMBER => $this->OPTIONAL,
				self::$CARD_EXP_MONTH => $this->OPTIONAL,
				self::$CARD_EXP_YEAR => $this->OPTIONAL,
				self::$CARD_CV_NUMBER => $this->OPTIONAL,
				self::$DATE_OF_BIRTH => $this->OPTIONAL,
				self::$SMS_CODE => $this->OPTIONAL,
				self::$HOP_DATA => $this->OPTIONAL,
				self::$PAGE_SERIAL_NUMBER => $this->MANDATORY,
				self::$PAGE_VERSION => $this->MANDATORY 
		);
		return $parameterFieldTypeMap;
	}
}
?>