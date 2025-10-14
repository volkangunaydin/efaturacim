<?php
namespace Efaturacim\Util\Utils\Url;

use Efaturacim\Util\Utils\SimpleResult;

class UrlUtil{
    protected static $__isSSL = null;
    protected static $__baseHost = null;
    public static function getUrl($url=null,$newParams=null,$excludeParams=null){
        return new UrlObject($url,$newParams,$excludeParams);
    }
    public static function isSsl(){    
        if(is_null(self::$__isSSL)){
            
            self::$__isSSL = ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||  
                              isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1) ||
                              isset($_SERVER['HTTPS_PROXY']) && $_SERVER['HTTPS_PROXY'] === 'on');
        }
        return self::$__isSSL;
    }
    public static function getBaseHost(){
        if(is_null(self::$__baseHost)){
            $isSsl = self::isSsl();
            if (!isset($_SERVER)) {
                self::$__baseHost = "";
            }else if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
                // The proxy has provided the original host requested by the client.
                self::$__baseHost =  $_SERVER['HTTP_X_FORWARDED_HOST'];
            } elseif (isset($_SERVER['HTTP_HOST'])) {
                // Standard host check (for non-proxied setups).
                self::$__baseHost =   @$_SERVER['HTTP_HOST'];
            } else {
                // Fallback if HTTP_HOST isn't set.
                self::$__baseHost =  @$_SERVER['SERVER_NAME'];                
                $port     = @$_SERVER['SERVER_PORT'];
                $isStandardPort = (!$isSsl && $port == 80) || ($isSsl && $port == 443);
                if (!$isStandardPort) {
                    self::$__baseHost .= ':' . $port;
                }                            
            }    
        }            
        return self::$__baseHost;
    }
    public static function getBaseUrl(){        
        $isSsl = self::isSsl();
        $host  = self::getBaseHost();        
        return ($isSsl ? 'https://' : 'http://') . $host."/";
    }
    public static function isValid($s){
        $res = self::getUrlAsResult($s);
        return $res;
    }
    public static function getUrlAsResult($org){
        $r = new SimpleResult();
        $s = "".$org."";
        $r->setAttribute("org_str", "".$org);
        $r->setAttribute("protocol", "");
        $r->setAttribute("domain", "");
        $p = "";
        if(is_string($s) & strlen($s)>0){
            $a = strtolower("".substr($s, 0,8));
            $b = strtolower("".substr($s, 0,7));
            if($a=="https://"){
                $p = "https";                    
                $s = substr($s, 8);
            }else if($b=="https://"){
                $p = "http";
                $s = substr($s, 7);
            }   
            if($p && strlen("".$p)>0){
                $r->setAttribute("protocol", "https");
                $pathStrPos = strpos($s, "/",0);                    
                if($pathStrPos!==false && $pathStrPos>0){                        
                    $r->setAttribute("domain", substr($s, 0,$pathStrPos));                        
                    $s = substr($s, $pathStrPos);                        
                    $r->setAttribute("path", $s);
                }else{
                    $pathStrPos = strpos($s, ".",0);
                    if($pathStrPos!==false && $pathStrPos>0){
                        $r->setAttribute("domain", $s);
                        $s = "";
                    }
                }
            }                
            $r->setAttribute("remain", "".$s);
        }
        if(strlen("".$r->attributes["domain"])>0 && strlen("".$r->attributes["protocol"])>0){
            $r->setIsOk(true);
        }            
        return $r;
    }    
}
?>