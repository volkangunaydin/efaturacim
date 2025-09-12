<?php
namespace Efaturacim\Util;

use Efaturacim\Util\Utils\IO\LibPath;
use Efaturacim\Util\Utils\Json\JsonUtil;

class EfaturacimUtil{
    protected static $version = null;    
    protected static $libPath = null;    
    public static function getVersion(){
        if(is_null(self::$version)){
            $arr = JsonUtil::readAsArray(dirname(__FILE__)."/info.json");            
            self::$version = @$arr["version"];
        }
        return self::$version;        
    }
    public static function getLibPath(){
        if(is_null(self::$libPath)){
            self::$libPath = LibPath::getLibPath();
        }
        return self::$libPath;
    }
}
?>