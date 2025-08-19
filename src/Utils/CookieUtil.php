<?php
namespace Efaturacim\Util\Utils;
class CookieUtil{
    public static function setCookie($name,$value,$expInDays=null){
        // Check if setcookie function is available and not in console
        if (!function_exists('setcookie') || php_sapi_name() === 'cli' || php_sapi_name() === 'phpdbg') {
            return false;
        }        
        if(is_null($expInDays) || $expInDays<=0){
            $t = time() + (86400 * 30*365);
        }else{
            $t = time() + round((86400 * $expInDays));
        }
        return setcookie($name,$value,expires_or_options: $t);
    }
    public static function getCookie($name,$defValOrCallback=null,$expInDays=null){
        if(!is_null($name) && strlen("".$name)>0 && isset($_COOKIE)){
            if(key_exists($name, @$_COOKIE)){
                return @$_COOKIE[$name];
            }else if (!is_null($defValOrCallback)){
                if(is_callable($defValOrCallback)){
                    $val = call_user_func_array($defValOrCallback,array());
                    if(!is_null($val)){
                        self::setCookie($name,$val,$expInDays);
                    }                                        
                    return $val;
                }else if (is_scalar($defValOrCallback)){
                    return $defValOrCallback;
                }else{
                    return null;
                }
            }else{
                return null;
            }
        }            
    }
}
?>