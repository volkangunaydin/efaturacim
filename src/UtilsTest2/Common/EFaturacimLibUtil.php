<?php
namespace Efaturacim\Util\Utils\Common;
class EFaturacimLibUtil{
     private static $LIB_PATH  = null;
     private static $TEST_PATH = null;   

     public static function getLibPath($suffix=""){
        self::ensurePaths();
         return self::$LIB_PATH . $suffix;
     }
     public static function getTestPath($suffix=""){
        self::ensurePaths();
         return self::$TEST_PATH . $suffix;
     }
     public static function ensurePaths($suffix=""){
        if(is_null(self::$LIB_PATH)){
            $curr = str_replace(array(":\\","\\\\","\\"),array(":/","/","/"),realpath(__DIR__));       
            self::$LIB_PATH = str_replace(array(":\\","\\\\","\\"),array(":/","/","/"),realpath($curr."/../../"))."/";       
            self::$TEST_PATH = str_replace(array(":\\","\\\\","\\"),array(":/","/","/"),realpath($curr."/../../../tests/"))."/";       
        }
     }
}
?>