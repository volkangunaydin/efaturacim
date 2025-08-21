<?php
namespace Efaturacim\Util\Utils\Url;
class UrlUtil{
    public static function getUrl($url=null,$newParams=null,$excludeParams=null){
        return new UrlObject($url,$newParams,$excludeParams);
    }
    public static function isSsl(){        
        return ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||  isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1));                
    }
    public static function getBaseHost(){
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            // The proxy has provided the original host requested by the client.
            return $_SERVER['HTTP_X_FORWARDED_HOST'];
        } elseif (isset($_SERVER['HTTP_HOST'])) {
            // Standard host check (for non-proxied setups).
            return  $_SERVER['HTTP_HOST'];
        } else {
            // Fallback if HTTP_HOST isn't set.
            return $_SERVER['SERVER_NAME'];
        }    
    }
    public static function getBaseUrl(){        
        $isSsl = self::isSsl();
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            // The proxy has provided the original host requested by the client.
            $host =  $_SERVER['HTTP_X_FORWARDED_HOST'];
        } elseif (isset($_SERVER['HTTP_HOST'])) {
            // Standard host check (for non-proxied setups).
            $host = $_SERVER['HTTP_HOST'];
        } else {
            // Fallback if HTTP_HOST isn't set.
            $host = $_SERVER['SERVER_NAME'];
            $port     = $_SERVER['SERVER_PORT'];
            $isStandardPort = (!$isSsl && $port == 80) || ($isSsl && $port == 443);
            if (!$isStandardPort) {
                $host .= ':' . $port;
            }            
        }           
        return $isSsl ? 'https://' : 'http://' . $host."/";
    }
}
?>