<?php
include_once 'web/util/merchantConstants.php';
include_once 'web/exception/customException.php';
include_once 'web/buy/cybs/param/sopMerchantRequestResponseParameter.php';
class SOPRequestGenerator {
	private static function generateRequestMap($parameters, $publicKey) {
		$outMap = array ();
		while ( list ( $key, $value ) = each ( $parameters ) ) {
			$outMap [$key] = $value;
		}
		$outMap [SOPMerchantRequestResponseParameter::$PAGE_TIMESTAMP] = getmicrotime ();
		$signatureMap = InsertSignature ( $outMap, $publicKey );
		$outMap [SOPMerchantRequestResponseParameter::$PAGE_PUBLIC_SIGNATURE] = $signatureMap [SOPMerchantRequestResponseParameter::$PAGE_PUBLIC_SIGNATURE];
		$outMap [SOPMerchantRequestResponseParameter::$PAGE_SIGNED_FIELDS] = $signatureMap [SOPMerchantRequestResponseParameter::$PAGE_SIGNED_FIELDS];
		
		return $outMap;
	}
	private static function validateRequestParameter($parameters) {
		$sopMerReqParam = new SOPMerchantRequestResponseParameter ();
		$arrayParam = $sopMerReqParam->getParameterfieldtypemap ();
		while ( list ( $key, $value ) = each ( $arrayParam ) ) {
			if ($value == MerchantConstants::$OPTIONAL) {
				continue;
			} else if (strpos ( $key, "{}" ) != false && $value == MerchantConstants::$MANDATORY && $parameters [str_replace ( "{}", "0", $key )] != null && $parameters [str_replace ( "{}", "0", $key )] != "") {
				continue;
			} else if ($value == MerchantConstants::$MANDATORY && $parameters [$key] != null && $parameters [$key] != "") {
				continue;
			} else {
				throw new ParameterValidationException ( "Mandatory paremeter " + parameter + " not present" );
			}
		}
	}
	public static function generateRequest($parameters, $publicKey) {
		self::validateRequestParameter ( $parameters );
		return self::generateRequestMap ( $parameters, $publicKey );
	}
}
?>