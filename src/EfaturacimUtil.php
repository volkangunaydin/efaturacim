<?php
namespace Efaturacim\Util;

use Efaturacim\Util\Utils\Json\JsonUtil;

class EfaturacimUtil{
    protected static $version = null;    
    public static function getVersion(){
        if(is_null(self::$version)){
            $arr = JsonUtil::readAsArray(dirname(__FILE__)."/info.json");            
            self::$version = @$arr["version"];
        }
        return self::$version;        
    }
}
?>