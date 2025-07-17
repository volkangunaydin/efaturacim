<?php
namespace Efaturacim\Util;
class ArrayUtil{
    public static function arrayGetKey($array,&$keyToReturn,$keyOrKeys,$defVal=null){                                    
        if(!is_null($keyOrKeys) && is_scalar($keyOrKeys)){
            if(key_exists($keyOrKeys,$array)){
                $keyToReturn = $keyOrKeys;
                return @$array[$keyOrKeys];
            }
        }else if (is_array($keyOrKeys)){
            foreach($keyOrKeys as $key){
                if(key_exists($key,$array)){
                    $keyToReturn = $key;
                    return  @$array[$key];
                }
            }
        }
        $keyToReturn = null;
        return $defVal;
    }    
}
?>