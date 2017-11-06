<?php

function php_hmacsha1($data, $key) {
    $klen = strlen($key);
    $blen = 64;
    $ipad = str_pad("", $blen, chr(0x36));
    $opad = str_pad("", $blen, chr(0x5c));

    if ($klen <= $blen) {
        while (strlen($key) < $blen) {
            $key .= "\0";
        }
    } else {
        $key = cybs_sha1($key);
    }
    $key = str_pad($key, strlen($ipad) + strlen($data), "\0");
    return cybs_sha1(($key ^ $opad) . cybs_sha1($key ^ $ipad . $data));
}

function cybs_sha1($in) {
    if (function_exists('sha1')) {
        return pack("H*", sha1($in));
    }
    $indx = 0;
    $chunk = "";

    $A = array(
        1732584193,
        4023233417,
        2562383102,
        271733878,
        3285377520
    );
    $K = array(
        1518500249,
        1859775393,
        2400959708,
        3395469782
    );
    $a = $b = $c = $d = $e = 0;
    $l = $p = $r = $t = 0;

    do {
        $chunk = substr($in, $l, 64);
        $r = strlen($chunk);
        $l += $r;

        if ($r < 64 && !$p ++) {
            $r ++;
            $chunk .= "\x80";
        }
        $chunk .= "\0\0\0\0";
        while (strlen($chunk) % 4 > 0) {
            $chunk .= "\0";
        }
        $len = strlen($chunk) / 4;
        if ($len > 16)
            $len = 16;
        $fmt = "N" . $len;
        $W = array_values(unpack($fmt, $chunk));
        if ($r < 57) {
            while (count($W) < 15) {
                array_push($W, "\0");
            }
            $W [15] = $l * 8;
        }

        for ($i = 16; $i <= 79; $i ++) {
            $v1 = d($W, $i - 3);
            $v2 = d($W, $i - 8);
            $v3 = d($W, $i - 14);
            $v4 = d($W, $i - 16);
            array_push($W, L($v1 ^ $v2 ^ $v3 ^ $v4, 1));
        }

        list ( $a, $b, $c, $d, $e ) = $A;

        for ($i = 0; $i <= 79; $i ++) {
            $t0 = 0;
            switch (intval($i / 20)) {
                case 1 :
                case 3 :
                    $t0 = F1($b, $c, $d);
                    break;
                case 2 :
                    $t0 = F2($b, $c, $d);
                    break;
                default :
                    $t0 = F0($b, $c, $d);
                    break;
            }
            $t = M($t0 + $e + d($W, $i) + d($K, $i / 20) + L($a, 5));
            $e = $d;
            $d = $c;
            $c = L($b, 30);
            $b = $a;
            $a = $t;
        }

        $A [0] = M($A [0] + $a);
        $A [1] = M($A [1] + $b);
        $A [2] = M($A [2] + $c);
        $A [3] = M($A [3] + $d);
        $A [4] = M($A [4] + $e);
    } while ($r > 56);
    $v = pack("N*", $A [0], $A [1], $A [2], $A [3], $A [4]);
    return $v;
}

function dd($x) {
    if (defined($x))
        return $x;
    return 0;
}

function d($arr, $x) {
    if ($x < count($arr))
        return $arr [$x];
    return 0;
}

function F0($b, $c, $d) {
    return $b & ($c ^ $d) ^ $d;
}

function F1($b, $c, $d) {
    return $b ^ $c ^ $d;
}

function F2($b, $c, $d) {
    return ($b | $c) & $d | $b & $c;
}

function M($x) {
    $m = 1 + ~ 0;
    if ($m == 0)
        return $x;
    return ($x - $m * intval($x / $m));
}

function L($x, $n) {
    return (($x << $n) | ((pow(2, $n) - 1) & ($x >> (32 - $n))));
}

function getmicrotime() {
    list ( $usec, $sec ) = explode(" ", microtime());
    $usec = (int) ((float) $usec * 1000);
    while (strlen($usec) < 3) {
        $usec = "0" . $usec;
    }
    return $sec . $usec;
}

function hopHash($data, $key) {
    //return str_replace ( "[\r\n\t]", "", base64_encode ( php_hmacsha1 ( $data, $publickey ) ) );
    return base64_encode(php_hmacsha1($data, $key));
}

function InsertSignature($inputMap, $publicKey) {
    if ($inputMap == null) {
        return null;
    }

    $outMap = array();
    $customFields = "";
    $dataToSign = "";
    while (list ( $key, $value ) = each($inputMap)) {
        $customFields .= $key . ",";
        $dataToSign .= $key . "=" . $value . ",";
    }
    if (strlen($customFields) > 0) {
        $customFields = substr($customFields, 0, strlen($customFields) - 1);
    }
    $dataToSign .= BuyMerchantRequestResponseParameter::$SIGNED_FIELDS_PUBLIC_SIGNATURE . "=";
    $dataToSign .= hopHash($customFields, $publicKey);
    $outMap [BuyMerchantRequestResponseParameter::$PAGE_PUBLIC_SIGNATURE] = hopHash($dataToSign, $publicKey);
    $outMap [BuyMerchantRequestResponseParameter::$PAGE_SIGNED_FIELDS] = $customFields;

    return $outMap;
}

function VerifySignature($data, $signature, $publicKey) {
    $pub_digest = hopHash($data, $publicKey);
    return strcmp($pub_digest, $signature) == 0;
}

function VerifyTransactionSignature($inputMap, $publicKey) {
    if ($inputMap == null || ($inputMap != null && ($inputMap [BuyMerchantRequestResponseParameter::$SIGNED_PUBLIC_SIGNATURE] == null || $inputMap [BuyMerchantRequestResponseParameter::$SIGNED_FIELDS] == null))) {
        return false;
    }
    $transactionSignature = $inputMap [BuyMerchantRequestResponseParameter::$SIGNED_PUBLIC_SIGNATURE];
    $transactionSignatureFields = $inputMap [BuyMerchantRequestResponseParameter::$SIGNED_FIELDS];

    $tokenizer = explode(",", $transactionSignatureFields);
    $data = "";
    while (list ( $key, $value ) = each($tokenizer)) {
        $data .= $value . "=" . $inputMap [$value] . ",";
    }

    $data .= BuyMerchantRequestResponseParameter::$SIGNED_FIELDS_PUBLIC_SIGNATURE . "=";
    $data .= hopHash($transactionSignatureFields, $publicKey);
    return verifySignature($data, $transactionSignature, $publicKey);
}

function parseIniFile($file) {
    if (!is_file($file))
        return null;
    $iniFileContent = file_get_contents($file);
    return parseIniString($iniFileContent);
}

function parseIniString($iniFileContent = '') {
    $iniArray = array();
    $iniFileContentArray = explode("\n", $iniFileContent);
    foreach ($iniFileContentArray as $iniFileContentArrayRow) {
        $iniArrayKey = substr($iniFileContentArrayRow, 0, strpos($iniFileContentArrayRow, '='));
        $iniArrayValue = substr($iniFileContentArrayRow, (strpos($iniFileContentArrayRow, '=') + 1));
        $iniArray [$iniArrayKey] = trim($iniArrayValue);
    }
    return $iniArray;
}
