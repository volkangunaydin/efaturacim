<?php
namespace Efaturacim\Util\Utils\String;

class StrTo{
    public static function str($strOrObject){
        if(is_null($strOrObject)){
            return "";
        }
        if(is_bool($strOrObject)){
            return $strOrObject?"true":"false";
        }
        if(is_numeric($strOrObject)){
        return "".$strOrObject;
        }
        if(is_array($strOrObject)){
            return json_encode($strOrObject);
        }
        if(is_object($strOrObject)){
            return json_encode($strOrObject);
        }
        
        return "".$strOrObject;
    }
}
?>