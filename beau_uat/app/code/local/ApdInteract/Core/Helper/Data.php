<?php

class ApdInteract_Core_Helper_Data extends Mage_Core_Helper_Data
{

    public function getRandomString($len, $chars = null)
    {
        /*if (is_null($chars)) {
            $chars = self::CHARS_LOWERS . self::CHARS_UPPERS . self::CHARS_DIGITS;
        }
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len-2; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }*/

        $key = '';
        $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
        $num = range(0,9);
        for($i=0; $i < $len-1; $i++) {
            $key .= $pool[mt_rand(0, count($pool) - 1)];
        }
        
        $key .= $num[mt_rand(0,1)];
        

        /* Disabled & need update - as this is causing parse error on bft-develop & bft-uat*/
        //$str .= self::CHARS_LOWERS[mt_rand(0, strlen(self::CHARS_LOWERS))];
        //$str .= self::CHARS_DIGITS[mt_rand(0, strlen(self::CHARS_DIGITS))];

        return $key;
    }
    
}
