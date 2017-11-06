<?php

class ResponseProcessor {

    private static function generateResponseMap($responseText) {
        $outMap = array();
        echo $responseText . "\n";
        if ($responseText != null && strpos($responseText, MerchantConstants::$FIELD_SEPERATOR) != false) {
            $tokens = explode(MerchantConstants::$FIELD_SEPERATOR, $responseText);
            $params = null;
            $merchantRespParam = new MerchantReponseParameter ();
            $arrayParam = $merchantRespParam->getParametermap();
            foreach ($tokens as $token) {
                if ($token != null && strpos($token, MerchantConstants::$PARAMETER_SEPERATOR) != false) {
                    $params = explode(MerchantConstants::$PARAMETER_SEPERATOR, $token);
                    print_r($params);
                    echo $params [0];
                    print_r($arrayParam);
                    if ($params [0] != null && in_array($params [0], $arrayParam)) {
                        if (count($params) == 1) {
                            $outMap [$params [0]] = "";
                        } else {
                            $outMap [$params [0]] = $params [1];
                        }
                    } else {
                        throw new ParameterValidationException("Invalid Response - invalid parameter received");
                    }
                } else {
                    throw new ParameterValidationException("Invalid Response - names and values are not paired");
                }
            }
        } else {
            throw new ParameterValidationException("Invalid Response - field seperator does not exists");
        }
        return $outMap;
    }

    public static function generateResponse($responseString, $keyFile) {
        $strb64Key = base64_encode(fnReadFile($keyFile));
        $generatedDecryptedResponse = fnDecrypt($responseString, $strb64Key);
        $generatedRequest = self::generateResponseMap($generatedDecryptedResponse);
        return $generatedRequest;
    }

}