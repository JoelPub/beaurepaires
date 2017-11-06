<?php

class RequestGenerator {

    public static function generateRequestString($parameters) {
        $generatedRequest = "";
        $iCounter = 0;
        $merchantReqParam = new MerchantRequestParameter ();
        $arrayParam = $merchantReqParam->getParametermap();
        if (($parameters != null && is_array($parameters)) && count($parameters) > 0) {
            while (list ( $key, $value ) = each($parameters)) {
                if ($key != null && !($key == MerchantRequestParameter::$MERCHANT_ID || $key == MerchantRequestParameter::$SOURCE_FLAG)) {
                    if (in_array($key, $arrayParam)) {
                        if ($iCounter == 0) {
                            $generatedRequest = $key . MerchantConstants::$PARAMETER_SEPERATOR . $value;
                        } else {
                            $generatedRequest = $generatedRequest . MerchantConstants::$FIELD_SEPERATOR . $key . MerchantConstants::$PARAMETER_SEPERATOR . $value;
                        }
                        $iCounter ++;
                    }
                }
            }
        } else {
            throw new ParameterValidationException("No Parameters specified");
        }
        return $generatedRequest;
    }

    public static function validateRequestParameter($parameters) {
        $merchantReqParam = new MerchantRequestParameter ();
        $arrayParam = $merchantReqParam->getParameterfieldtypemap();
        while (list ( $key, $value ) = each($arrayParam)) {
            if ($arrayParam [$key] == MerchantConstants::$OPTIONAL) {
                continue;
            } else if ($arrayParam [$key] == MerchantConstants::$MANDATORY && $parameters [$key] != null && $parameters [$key] != "") {
                continue;
            } else {
                throw new ParameterValidationException("Mandatory parameter " . $key . " not present");
            }
        }
    }

    public static function generateRequest($parameters, $keyFile) {
        self::validateRequestParameter($parameters);
        $generatedRequest = self::generateRequestString($parameters);
        $strb64Key = base64_encode(fnReadFile($keyFile));
        $generatedEncryptedRequest = fnEncrypt($generatedRequest, $strb64Key);
        $merchantRequest = array(
            MerchantRequestParameter::$MERCHANT_ID => $parameters [MerchantRequestParameter::$MERCHANT_ID],
            MerchantRequestParameter::$SOURCE_FLAG => $parameters [MerchantRequestParameter::$SOURCE_FLAG],
            MerchantRequestParameter::$IN_DATA_BLOCK => $generatedEncryptedRequest
        );
        return $merchantRequest;
    }

}