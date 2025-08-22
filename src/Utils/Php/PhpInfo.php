<?php
namespace Efaturacim\Util\Utils\Php;

class PhpInfo{
    protected static $infoHtml = null;  
    protected static $infoArray = null;  
    protected static $phpVersion = null; 
    public static function initIFNot(){
        if(is_null(self::$infoHtml)){
            self::$infoHtml   = self::getInfoHtml();
            self::$infoArray  = self::getInfoArray();
            self::$phpVersion = self::getPhpVersion();
        }
    } 
    public static function getInfoHtml(){
        if(is_null(self::$infoHtml)){
            ob_start();
            phpinfo();
            $s  = ob_get_clean();
            self::$infoHtml = $s;
        }
        return self::$infoHtml;
    }
    public static function getInfoArray(){        
        
    }
    public static function getPhpVersionAsString(){                      
        return "".phpversion();
    }
    
}

?>