<?php
namespace Efaturacim\Util;
class SecurityUtil{
    public static function getClientKey(){        
        return CookieUtil::getCookie("SECURE_CLIENT_KEY",function(){
            return SecurityUtil::getGUID();
        });   
    }
    public static function getGUID(){        
            if (function_exists('com_create_guid') === true){
                return  trim(com_create_guid(), '{}'); 
            }else{
                 return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
            }
    }
    public static function getIp(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $ip_address = explode(',', $ip_address)[0];
        }elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip_address = $_SERVER['HTTP_X_REAL_IP'];
        }else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        $ip_address = filter_var($ip_address, FILTER_VALIDATE_IP);
        
        if ($ip_address === false) {
            return "";
        }
        return $ip_address;
    }    
    public static function getUserAgent(){
        return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
    }
}
?>