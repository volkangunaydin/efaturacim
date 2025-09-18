<?php

namespace Efaturacim\Util\Utils\String;

class RandomUtil{
    public static function randomString($length=20){
        // Ensure valid length
        $length = (int)$length;
        if($length <= 0){ $length = 20; }

        // Default alphabet: URL-safe alphanumeric
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $alphabetLength = strlen($alphabet);

        $result = '';
        for($i = 0; $i < $length; $i++){
            $index = random_int(0, $alphabetLength - 1);
            $result .= $alphabet[$index];
        }

        return $result;
    }
}

?>