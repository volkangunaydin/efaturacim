<?php
namespace Efaturacim\Util;

use Efaturacim\Util\Utils\Json\JsonUtil;

class EfaturacimUtil{
    protected static $version = null;
    public static function getVersion(){
        if(is_null(self::$version)){
            $arr = JsonUtil::readfile(dirname(__FILE__)."/info.json");
            dd($arr);
            self::$version = $version;
        }
        return self::$version;
    }
}
?>