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
}
?>