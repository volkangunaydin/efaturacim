<?php

namespace Efaturacim\Util\Utils\Cache;

class MemoryCache{
    private static $cache = array();

    public static function get($key,$defValue=null){
        if(isset(self::$cache[$key])){
            return self::$cache[$key];
        }
        return $defValue;
    }
    public static function set($key,$value){
        self::$cache[$key] = $value;
    }
    public static function hasKey($key){
        return isset(self::$cache[$key]);
    }
    public static function getKey($key){
        if(is_array($key)){
            $key = implode("|",$key);
        }
        if(is_object($key)){
            $key = serialize($key);
        }
        if(is_callable($key)){
            $key = $key();
        }
        if(is_scalar($key)){
            return md5("".$key);
        }
        return null;
    }
}


?>