<?php
include_once 'web/util/merchantConstants.php';
include_once 'web/buy/cybs/param/sopMerchantRequestResponseParameter.php';
include_once 'web/buy/request/generator/sopRequestGenerator.php';
include_once 'web/buy/request/generator/generatorUtility.php';
class TestSOPRequestResponse {
	private $url;
	private $serialNumber;
	private $publicKey;
	private $merchantId;
	private $promoCode;
	private $grandTotal;
	private $itemCount;
	private $merchantReferenceCode;
	private $hopData;
	private $cardNumber;
	private $expiryMonth;
	private $expiryYear;
	private $cvvNumber;
	private $accountNumber;
	private $dateOfBirth;
	private $smsCode;
	private $authServiceRun;
	private $currency;
	private $pageVersion;
	private $propArray;
	private function applyProperty() {
		try {
			$this->propArray = parseIniFile ( "../../../ApplyBuyProperties.ini" );
			
			$this->url = $this->propArray ['SOPurl'];
			$this->serialNumber = $this->propArray ['serialNumber'];
			$this->publicKey = $this->propArray ['publicKey'];
			$this->merchantId = $this->propArray ['merchantId'];
			
			$this->currency = $this->propArray ['currency'];
			$this->authServiceRun = $this->propArray ['authServiceRun'];
			$this->promoCode = $this->propArray ['promoCode'];
			$this->grandTotal = $this->propArray ['grandTotal'];
			$this->itemCount = $this->propArray ['itemCount'];
			$this->merchantReferenceCode = $this->propArray ['merchantReferenceCode'];
			$this->hopData = $this->propArray ['hopData'];
			$this->cardNumber = $this->propArray ['cardNumber'];
			$this->expiryMonth = $this->propArray ['expiryMonth'];
			$this->expiryYear = $this->propArray ['expiryYear'];
			$this->cvvNumber = $this->propArray ['cvvNumber'];
			$this->accountNumber = $this->propArray ['accountNumber'];
			$this->dateOfBirth = $this->propArray ['dateOfBirth'];
			$this->smsCode = $this->propArray ['smsCode'];
			$this->pageVersion = $this->propArray ['pageVersion'];
		} catch ( Exception $ex ) {
			echo $ex->getMessage ();
		}
	}
	public function mainTestSOP() {
		self::applyProperty ();
		echo ("Enter Authorization Type : C for Credit Card, T for Secured Token, S for Retrieve SMS, A for SMS Code\n");
		$handle = fopen ( "php://stdin", "r" );
		$inputAuthType = null;
		try {
			$inputAuthType = fgets ( $handle );
		} catch ( IOException $ioe ) {
			echo ("IO error trying to read your Authorization Type!");
			exit ( 1 );
		}
		$inputAuthType = trim ( $inputAuthType );
		
		if ($inputAuthType != null && ($inputAuthType == MerchantConstants::$AUTH_TYPE_CREDIT_CARD || $inputAuthType == MerchantConstants::$AUTH_TYPE_SECURED_TOKEN || $inputAuthType == MerchantConstants::$AUTH_TYPE_RETRIEVE_SMS || $inputAuthType == MerchantConstants::$AUTH_TYPE_SMS_CODE)) {
			self::testRequestSOP ( $inputAuthType );
			echo ("Done !\n");
		//	self::testResponse ( $inputAuthType );
		} else {
			echo ("Invalid Authorization Type. Please try again later.\n");
			exit ( 1 );
		}
	}
	private function testRequestSOP($authType) {
		$inputParameters = array ();
		$output = null;
		try {
			/**
			 * Put all parameters into ordered map for generating SOP request parameters
			 */
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_SERVICE_RUN] = $this->authServiceRun;
			$inputParameters [SOPMerchantRequestResponseParameter::$MERCHANT_ID] = $this->merchantId;
			$inputParameters [SOPMerchantRequestResponseParameter::$REFERENCE_CODE] = $this->merchantReferenceCode;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_TYPE] = $authType;
			$inputParameters [SOPMerchantRequestResponseParameter::$PROMO_CODE] = $this->promoCode;
			$inputParameters [SOPMerchantRequestResponseParameter::$ITEM_COUNT] = $this->itemCount;
			$iCounter = 0;
			while ( $this->propArray [str_replace ( "{}", $iCounter, SOPMerchantRequestResponseParameter::$PRODUCT_CODE )] != null ) {
				$inputParameters [str_replace ( "{}", $iCounter, SOPMerchantRequestResponseParameter::$PRODUCT_CODE )] = $this->propArray [str_replace ( "{}", $iCounter, SOPMerchantRequestResponseParameter::$PRODUCT_CODE )];
				$inputParameters [str_replace ( "{}", $iCounter, SOPMerchantRequestResponseParameter::$TOTAL_AMOUNT )] = $this->propArray [str_replace ( "{}", $iCounter, SOPMerchantRequestResponseParameter::$TOTAL_AMOUNT )];
				$iCounter ++;
			}
			
			$inputParameters [SOPMerchantRequestResponseParameter::$CURRENCY] = $this->currency;
			$inputParameters [SOPMerchantRequestResponseParameter::$GRAND_TOTAL] = $this->grandTotal;
			if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_SECURED_TOKEN) {
				$inputParameters [SOPMerchantRequestResponseParameter::$CARD_ACCOUNT_NUMBER] = $this->accountNumber;
				$inputParameters [SOPMerchantRequestResponseParameter::$HOP_DATA] = $this->hopData;
			} else if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_RETRIEVE_SMS) {
				$inputParameters [SOPMerchantRequestResponseParameter::$CARD_ACCOUNT_NUMBER] = $this->cardNumber;
			} else if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_SMS_CODE) {
				$inputParameters [SOPMerchantRequestResponseParameter::$CARD_ACCOUNT_NUMBER] = $this->cardNumber;
			}
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_SERIAL_NUMBER] = $this->serialNumber;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_VERSION] = $this->pageVersion;
			
			/**
			 * Generate the request parameters for SOP
			 */
			$output = SOPRequestGenerator::generateRequest ( $inputParameters, $this->publicKey );
			
			/**
			 * Generate the request form
			 */
			self::generateSOPRequest ( $output, $authType );
		} catch ( Exception $ex ) {
			echo "\nI am here in exception\n";
			echo $ex->getMessage ();
		}
	}
	private function generateSOPRequest($output, $authType) {
		$fileName = "";
		if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_CREDIT_CARD) {
			$fileName = "C:\\SOP-CreditCard-Request.html";
		}
		if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_SECURED_TOKEN) {
			$fileName = "C:\\SOP-SecuredToken-Request.html";
		}
		if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_RETRIEVE_SMS) {
			$fileName = "C:\\SOP-RetrieveSMS-Request.html";
		}
		if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_SMS_CODE) {
			$fileName = "C:\\SOP-SMSCode-Request.html";
		}
		
		$file = fopen ( $fileName, "w" );
		
		fwrite ( $file, "<html>\n" );
		fwrite ( $file, "<form name=\"merchantForm\" action=\"" . $this->url . "\" method=\"POST\">\n" );
		fwrite ( $file, "<table border=\"0\"  align=\"center\" style=\"border:1px #0E4E96 solid\" bgcolor=\"#ADD8E6\" cellspacing=\"10\" cellpadding=\"10\" width=\"100%\">" );
		fwrite ( $file, "<tr><td align=\"left\" width=\"15%\"><strong>URL</strong></td>" );
		fwrite ( $file, "<td align=\"left\" width=\"85%\">" );
		fwrite ( $file, $this->url );
		fwrite ( $file, "</td>" );
		fwrite ( $file, "</tr>" );
		if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_CREDIT_CARD) {
			fwrite ( $file, "<tr><td align=\"left\" width=\"15%\"><strong>" . SOPMerchantRequestResponseParameter::$CARD_ACCOUNT_NUMBER . "</strong></td>" );
			fwrite ( $file, "<td align=\"left\" width=\"85%\">" );
			fwrite ( $file, $this->cardNumber );
			fwrite ( $file, "</td>" );
			fwrite ( $file, "</tr>" );
			fwrite ( $file, "<input type=\"hidden\" name=\"" . SOPMerchantRequestResponseParameter::$CARD_ACCOUNT_NUMBER . "\" value=\"" . $this->cardNumber . "\">\n" );
			fwrite ( $file, "<tr><td align=\"left\" width=\"15%\"><strong>" . SOPMerchantRequestResponseParameter::$CARD_EXP_MONTH . "</strong></td>" );
			fwrite ( $file, "<td align=\"left\" width=\"85%\">" );
			fwrite ( $file, $this->expiryMonth );
			fwrite ( $file, "</td>" );
			fwrite ( $file, "</tr>" );
			fwrite ( $file, "<input type=\"hidden\" name=\"" . SOPMerchantRequestResponseParameter::$CARD_EXP_MONTH . "\" value=\"" . $this->expiryMonth . "\">\n" );
			fwrite ( $file, "<tr><td align=\"left\" width=\"15%\"><strong>" . SOPMerchantRequestResponseParameter::$CARD_EXP_YEAR . "</strong></td>" );
			fwrite ( $file, "<td align=\"left\" width=\"85%\">" );
			fwrite ( $file, $this->expiryYear );
			fwrite ( $file, "</td>" );
			fwrite ( $file, "</tr>" );
			fwrite ( $file, "<input type=\"hidden\" name=\"" . SOPMerchantRequestResponseParameter::$CARD_EXP_YEAR . "\" value=\"" . $this->expiryYear . "\">\n" );
			fwrite ( $file, "<tr><td align=\"left\" width=\"15%\"><strong>" . SOPMerchantRequestResponseParameter::$CARD_CV_NUMBER . "</strong></td>" );
			fwrite ( $file, "<td align=\"left\" width=\"85%\">" );
			fwrite ( $file, $this->cvvNumber );
			fwrite ( $file, "</td>" );
			fwrite ( $file, "</tr>" );
			fwrite ( $file, "<input type=\"hidden\" name=\"" . SOPMerchantRequestResponseParameter::$CARD_CV_NUMBER . "\" value=\"" . $this->cvvNumber . "\">\n" );
		}
		if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_RETRIEVE_SMS) {
			fwrite ( $file, "<tr><td align=\"left\" width=\"15%\"><strong>" . SOPMerchantRequestResponseParameter::$DATE_OF_BIRTH . "</strong></td>" );
			fwrite ( $file, "<td align=\"left\" width=\"85%\">" );
			fwrite ( $file, $this->dateOfBirth );
			fwrite ( $file, "</td>" );
			fwrite ( $file, "</tr>" );
			fwrite ( $file, "<input type=\"hidden\" name=\"" . SOPMerchantRequestResponseParameter::$DATE_OF_BIRTH . "\" value=\"" . $this->dateOfBirth . "\">\n" );
		}
		if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_SMS_CODE) {
			fwrite ( $file, "<tr><td align=\"left\" width=\"15%\"><strong>" . SOPMerchantRequestResponseParameter::$SMS_CODE . "</strong></td>" );
			fwrite ( $file, "<td align=\"left\" width=\"85%\">" );
			fwrite ( $file, $this->smsCode );
			fwrite ( $file, "</td>" );
			fwrite ( $file, "</tr>" );
			fwrite ( $file, "<input type=\"hidden\" name=\"" . SOPMerchantRequestResponseParameter::$SMS_CODE . "\" value=\"" . $this->smsCode . "\">\n" );
		}
		while ( list ( $key, $value ) = each ( $output ) ) {
			fwrite ( $file, "<tr><td align=\"left\" width=\"15%\"><strong>" . $key . "</strong></td>" );
			fwrite ( $file, "<td align=\"left\" width=\"85%\">" );
			fwrite ( $file, $value );
			fwrite ( $file, "</td>" );
			fwrite ( $file, "</tr>" );
			fwrite ( $file, "<input type=\"hidden\" name=\"" . $key . "\" value=\"" . $value . "\">\n" );
		}
		fwrite ( $file, "</table>" );
		fwrite ( $file, "<BR>" );
		fwrite ( $file, "<input type=\"submit\" name=\"Submit\" value=\"Submit\">\n" );
		fwrite ( $file, "</form>" );
		fwrite ( $file, "</html>" );
		fclose ( $file );
	}
	private function testResponse($authType) {
		$inputParameters = array ();
		
		$REPLY_CVCODE = "M";
		$REASON_CODE_PUBLIC_SIGNATURE = "Gla1c/HO1aYDCS2mUmGQZIy99i8=";
		$ACCEPT_REASON_CODE = "00";
		$DECISION = "ACCEPT";
		$CAVV_RESPONSE_CODE = "2";
		$PROCESSOR_RESPONSE = "003000";
		$REPLY_PHONE_NUMBER = "04xxxx4900";
		$CPS_TOKEN = "9910340260580022";
		$DECISION_PUBLIC_SIGNATURE = "X6kgHjWqKsVmrvc14vcg/JPo9iY=";
		
		if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_CREDIT_CARD) {
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CVCODE] = $REPLY_CVCODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$SIGNED_PUBLIC_SIGNATURE] = "w1BkVPQrpBz/Mz24IDq8H+ah1t4=";
			$inputParameters [SOPMerchantRequestResponseParameter::$REASON_CODE_PUBLIC_SIGNATURE] = $REASON_CODE_PUBLIC_SIGNATURE;
			$inputParameters [SOPMerchantRequestResponseParameter::$REASON_CODE] = $ACCEPT_REASON_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$GRAND_TOTAL] = $this->grandTotal;
			$inputParameters [SOPMerchantRequestResponseParameter::$DECISION] = $DECISION;
			$inputParameters [SOPMerchantRequestResponseParameter::$CURRENCY] = $this->currency;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_REASONCODE] = $ACCEPT_REASON_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CAVV_RESPONSE_CODE] = $CAVV_RESPONSE_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_PROCESSOR_RESPONSE] = $PROCESSOR_RESPONSE;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_PUBLIC_SIGNATURE] = "9+AxdrT59LKIBdeaew5L442w/Yo=";
			$inputParameters [SOPMerchantRequestResponseParameter::$PROMO_CODE] = $this->promoCode;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_SERIAL_NUMBER] = $this->serialNumber;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_AMOUNT] = $this->grandTotal;
			$inputParameters [SOPMerchantRequestResponseParameter::$MERCHANT_ID] = $this->merchantId;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_PHONE_NUMBER] = $REPLY_PHONE_NUMBER;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_TIMESTAMP] = "1372732175138";
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CPS_TOKEN] = $CPS_TOKEN;
			$inputParameters [SOPMerchantRequestResponseParameter::$REFERENCE_CODE] = $this->merchantReferenceCode;
			$inputParameters [SOPMerchantRequestResponseParameter::$DECISION_PUBLIC_SIGNATURE] = $DECISION_PUBLIC_SIGNATURE;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_SIGNED_FIELDS] = "ccAuthService_run,merchantID,merchantReferenceCode,authorizationType,promoCode,itemCount,item_0_productCode,item_0_totalAmount,item_1_productCode,item_1_totalAmount,purchaseTotals_currency,purchaseTotals_grandTotalAmount,orderPage_serialNumber,orderPage_version,orderPage_timestamp";
			$inputParameters [SOPMerchantRequestResponseParameter::$SIGNED_FIELDS] = "reasonCode_publicSignature,purchaseTotals_currency,ccAuthReply_amount,ccAuthReply_authorizedDateTime,ccAuthReply_reasonCode,decision,ccAuthReply_cpsToken,reasonCode,ccAuthReply_cavvResponseCode,orderPage_signedFields,ccAuthReply_phoneNumber,ccAuthReply_processorResponse,purchaseTotals_grandTotalAmount,promoCode,merchantID,orderPage_serialNumber,orderPage_signaturePublic,decision_publicSignature,ccAuthReply_cvCode,orderPage_timestamp,merchantReferenceCode";
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_AUTHORIZATION_TIME] = "02-07-2013 12:29:52+1000";
		} else if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_SECURED_TOKEN) {
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CVCODE] = $REPLY_CVCODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$SIGNED_PUBLIC_SIGNATURE] = "DKl4pymkjVjSRAycEi76hU0XAg0=";
			$inputParameters [SOPMerchantRequestResponseParameter::$REASON_CODE_PUBLIC_SIGNATURE] = $REASON_CODE_PUBLIC_SIGNATURE;
			$inputParameters [SOPMerchantRequestResponseParameter::$REASON_CODE] = $ACCEPT_REASON_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$GRAND_TOTAL] = $this->grandTotal;
			$inputParameters [SOPMerchantRequestResponseParameter::$DECISION] = $DECISION;
			$inputParameters [SOPMerchantRequestResponseParameter::$HOP_DATA] = "gTpqqCN582lJhTIlfOy4DuHHaEQe3pb7MG4r/35C52j/8deutTsR6h3cAHQCArNS/mjOQK2Lv8VnFHWRKQlGjp38PqbZ7pusiyZ1UWDCeO/A9K/yLtwotiRuTfilBtmABlr0WdChtS00Z7aIXq2keWqUTmlYG2EzaD7DuZWz8u2UXyEcBCwXNHWuAEC6Q5NPRHHDHBCiNe0iGjldJtMIBExGXqzoHiFPhYpvM+mMlnU=";
			$inputParameters [SOPMerchantRequestResponseParameter::$CURRENCY] = $this->currency;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_REASONCODE] = $ACCEPT_REASON_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$REASON_MESSAGE] = "The request processed successfully";
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CAVV_RESPONSE_CODE] = $CAVV_RESPONSE_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_PROCESSOR_RESPONSE] = $PROCESSOR_RESPONSE;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_PUBLIC_SIGNATURE] = "qeammZijs/5t9fEha911K+gXV1s=";
			$inputParameters [SOPMerchantRequestResponseParameter::$PROMO_CODE] = $this->promoCode;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_SERIAL_NUMBER] = $this->serialNumber;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_AMOUNT] = $this->grandTotal;
			$inputParameters [SOPMerchantRequestResponseParameter::$MERCHANT_ID] = $this->merchantId;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_TIMESTAMP] = "1371532351822";
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CPS_TOKEN] = "9910338497440010";
			$inputParameters [SOPMerchantRequestResponseParameter::$REFERENCE_CODE] = $this->merchantReferenceCode;
			$inputParameters [SOPMerchantRequestResponseParameter::$DECISION_PUBLIC_SIGNATURE] = $DECISION_PUBLIC_SIGNATURE;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_SIGNED_FIELDS] = "ccAuthService_run,merchantID,merchantReferenceCode,authorizationType,promoCode,itemCount,item_0_productCode,item_0_totalAmount,item_1_productCode,item_1_totalAmount,purchaseTotals_currency,purchaseTotals_grandTotalAmount,card_accountNumber,eapps_hopData,orderPage_serialNumber,orderPage_version,orderPage_timestamp";
			$inputParameters [SOPMerchantRequestResponseParameter::$SIGNED_FIELDS] = "reasonCode_publicSignature,purchaseTotals_currency,ccAuthReply_amount,reasonMessage,ccAuthReply_authorizedDateTime,ccAuthReply_reasonCode,decision,ccAuthReply_cpsToken,reasonCode,ccAuthReply_cavvResponseCode,orderPage_signedFields,ccAuthReply_processorResponse,purchaseTotals_grandTotalAmount,promoCode,merchantID,orderPage_serialNumber,orderPage_signaturePublic,eapps_hopData,decision_publicSignature,ccAuthReply_cvCode,orderPage_timestamp,merchantReferenceCode";
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_AUTHORIZATION_TIME] = "18-06-2013 15:13:01+1000";
		} else if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_RETRIEVE_SMS) {
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CVCODE] = $REPLY_CVCODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$SIGNED_PUBLIC_SIGNATURE] = "IBlRUTkaFW+JVKDI4IijA0T1bWk=";
			$inputParameters [SOPMerchantRequestResponseParameter::$REASON_CODE_PUBLIC_SIGNATURE] = $REASON_CODE_PUBLIC_SIGNATURE;
			$inputParameters [SOPMerchantRequestResponseParameter::$REASON_CODE] = $ACCEPT_REASON_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$GRAND_TOTAL] = "0.00";
			$inputParameters [SOPMerchantRequestResponseParameter::$DECISION] = $DECISION;
			$inputParameters [SOPMerchantRequestResponseParameter::$CURRENCY] = $this->currency;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_REASONCODE] = $ACCEPT_REASON_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CAVV_RESPONSE_CODE] = $CAVV_RESPONSE_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_PROCESSOR_RESPONSE] = "000000";
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_PUBLIC_SIGNATURE] = "0IP14/509hTmvCrqnwoS9yV8tX4=";
			$inputParameters [SOPMerchantRequestResponseParameter::$PROMO_CODE] = $this->promoCode;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_SERIAL_NUMBER] = $this->serialNumber;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_AMOUNT] = "0.00";
			$inputParameters [SOPMerchantRequestResponseParameter::$MERCHANT_ID] = $this->merchantId;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_PHONE_NUMBER] = $REPLY_PHONE_NUMBER;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_TIMESTAMP] = "1371532423438";
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CPS_TOKEN] = $CPS_TOKEN;
			$inputParameters [SOPMerchantRequestResponseParameter::$REFERENCE_CODE] = $this->merchantReferenceCode;
			$inputParameters [SOPMerchantRequestResponseParameter::$DECISION_PUBLIC_SIGNATURE] = $DECISION_PUBLIC_SIGNATURE;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_SIGNED_FIELDS] = "ccAuthService_run,merchantID,merchantReferenceCode,authorizationType,promoCode,itemCount,item_0_productCode,item_0_totalAmount,item_1_productCode,item_1_totalAmount,purchaseTotals_currency,purchaseTotals_grandTotalAmount,card_accountNumber,dateOfBirth,orderPage_serialNumber,orderPage_version,orderPage_timestamp";
			$inputParameters [SOPMerchantRequestResponseParameter::$SIGNED_FIELDS] = "reasonCode_publicSignature,purchaseTotals_currency,ccAuthReply_amount,ccAuthReply_authorizedDateTime,ccAuthReply_reasonCode,decision,ccAuthReply_cpsToken,reasonCode,ccAuthReply_cavvResponseCode,orderPage_signedFields,ccAuthReply_phoneNumber,ccAuthReply_processorResponse,purchaseTotals_grandTotalAmount,promoCode,merchantID,orderPage_serialNumber,orderPage_signaturePublic,decision_publicSignature,ccAuthReply_cvCode,orderPage_timestamp,merchantReferenceCode";
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_AUTHORIZATION_TIME] = "18-06-2013 15:14:00+1000";
		} else if ($authType != null && $authType == MerchantConstants::$AUTH_TYPE_SMS_CODE) {
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CVCODE] = $REPLY_CVCODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$SIGNED_PUBLIC_SIGNATURE] = "X9ZioAnqczUNqf/K0IytAOq5Wwc=";
			$inputParameters [SOPMerchantRequestResponseParameter::$REASON_CODE_PUBLIC_SIGNATURE] = $REASON_CODE_PUBLIC_SIGNATURE;
			$inputParameters [SOPMerchantRequestResponseParameter::$REASON_CODE] = $ACCEPT_REASON_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$GRAND_TOTAL] = $this->grandTotal;
			$inputParameters [SOPMerchantRequestResponseParameter::$DECISION] = $DECISION;
			$inputParameters [SOPMerchantRequestResponseParameter::$CURRENCY] = $this->currency;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_REASONCODE] = $ACCEPT_REASON_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CAVV_RESPONSE_CODE] = $CAVV_RESPONSE_CODE;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_PROCESSOR_RESPONSE] = $PROCESSOR_RESPONSE;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_PUBLIC_SIGNATURE] = "Xe9WIfuXaINCssE7u9r5Te2YocA=";
			$inputParameters [SOPMerchantRequestResponseParameter::$PROMO_CODE] = $this->promoCode;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_SERIAL_NUMBER] = $this->serialNumber;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_AMOUNT] = $this->grandTotal;
			$inputParameters [SOPMerchantRequestResponseParameter::$MERCHANT_ID] = $this->merchantId;
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_PHONE_NUMBER] = $REPLY_PHONE_NUMBER;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_TIMESTAMP] = "1371532469880";
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_CPS_TOKEN] = $CPS_TOKEN;
			$inputParameters [SOPMerchantRequestResponseParameter::$REFERENCE_CODE] = $this->merchantReferenceCode;
			$inputParameters [SOPMerchantRequestResponseParameter::$DECISION_PUBLIC_SIGNATURE] = $DECISION_PUBLIC_SIGNATURE;
			$inputParameters [SOPMerchantRequestResponseParameter::$PAGE_SIGNED_FIELDS] = "ccAuthService_run,merchantID,merchantReferenceCode,authorizationType,promoCode,itemCount,item_0_productCode,item_0_totalAmount,item_1_productCode,item_1_totalAmount,purchaseTotals_currency,purchaseTotals_grandTotalAmount,card_accountNumber,smsCode,orderPage_serialNumber,orderPage_version,orderPage_timestamp";
			$inputParameters [SOPMerchantRequestResponseParameter::$SIGNED_FIELDS] = "reasonCode_publicSignature,purchaseTotals_currency,ccAuthReply_amount,ccAuthReply_authorizedDateTime,ccAuthReply_reasonCode,decision,ccAuthReply_cpsToken,reasonCode,ccAuthReply_cavvResponseCode,orderPage_signedFields,ccAuthReply_phoneNumber,ccAuthReply_processorResponse,purchaseTotals_grandTotalAmount,promoCode,merchantID,orderPage_serialNumber,orderPage_signaturePublic,decision_publicSignature,ccAuthReply_cvCode,orderPage_timestamp,merchantReferenceCode";
			$inputParameters [SOPMerchantRequestResponseParameter::$AUTH_REPLY_AUTHORIZATION_TIME] = "18-06-2013 15:14:47+1000";
		}
		
		$flag = VerifyTransactionSignature ( $inputParameters, $this->publicKey ) ? "Success" : "Failure";
		echo ("\nflag= " . $flag);
	}
}

if (defined ( 'STDIN' )) {
	echo ("Running from CLI\n");
} else {
	echo ("Not Running from CLI\n");
}
$testSOPReqResp = new TestSOPRequestResponse ();
$testSOPReqResp->mainTestSOP ();

?>