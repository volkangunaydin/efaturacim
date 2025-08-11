<?php
namespace Efaturacim\Util\Utils\String;

use Efaturacim\Util\Utils\CastUtil;

class StrSerialize{
        public static function unserializeBase64($str,$defVal=null){
            try {
                $a = @unserialize("".@base64_decode("".$str));
                if(!is_null($a) && $a!==false){
                   return $a; 
                }
            } catch (\Exception $e) {
            }
            return $defVal;
        }
        public static function serializeBase64($object=null){
            return base64_encode(serialize($object));
        }
        public static function serialize($object){
            return "".serialize($object);
        }
        public static function unserialize($str){
            if($str && strlen($str)>0){
                try {
                    $a = @unserialize($str);
                    if(!is_null($a) && $a!==false){
                        return $a;
                    }
                } catch (\Exception $e) {
                }                
                try {
                    $a = self::unserializeBase64($str,null);
                    if(!is_null($a)){
                        return $a;
                    }
                } catch (\Exception $e) {
                }     
            }
            return null;
        }
        public static function unserializeBase64AsArray($dataStr){
            return CastUtil::getAs(self::unserializeBase64($dataStr),array(),CastUtil::$DATA_ARRAY);
        }
    }
}
?>