<?php
namespace Efaturacim\Util\Utils;
class StrCase{
    public static function upper($str){
        return self::toUpperTurkish($str);
    }
    public static function lower($str){
        return self::toLowerTurkish($str);
    }
    public static function camelCase($text){
        $camelCaseString = "";
        $wordStart = true; 
        $text      = self::toLowerTurkish($text);
        for ($i = 0; $i < strlen($text); $i++) {
            $char = $text[$i];                
            if ($char=="_" || $char==" ") {
                $wordStart = true;  
                $camelCaseString .= $char;
            }else{
                $camelCaseString .= $wordStart ? self::toUpperTurkish($char) : $char;
                $wordStart = false;
            }                
        }            
        return $camelCaseString;
    }
    public static function eng($text){
        return self::toEng($text);
    }
    public static function removeTurkishChars($str){ if(is_array($str)){ $res = array(); foreach ($str as $k=>$v){ $res[$k] = self::removeTurkishChars($v); } return $res; }else{ return str_replace(array("Ğ","Ü","Ş","İ","Ö","Ç","ğ","ü","ş","ı","ö","ç","Ä","ä","Ё","ё"), array("G","U","S","I","O","C","g","u","s","i","o","c","A","a","E","e"), "".$str); } }
    public static function toLowerTurkish($input){ if (is_array($input)){ $arr = array(); foreach ($input as $k=>$v){ $arr[$k] = self::toLowerTurkish($v); } return $arr;}else{ return mb_strtolower(str_replace( array("Ğ","Ü","Ş","I","İ","Ö","Ç"),array("ğ","ü","ş","ı","i","ö","ç"), "".$input),"UTF-8"); } }
    public static function toUpperTurkish($input){ if (is_array($input)){ $arr = array();foreach ($input as $k=>$v){ $arr[$k] = self::toUpperTurkish($v); } return $arr; }else{ return mb_strtoupper( str_replace(array("ğ","ü","ş","ı","i","ö","ç"), array("Ğ","Ü","Ş","I","İ","Ö","Ç"), "".$input),"UTF-8");} }
    public static function toEng($input){ return self::removeTurkishChars($input); }
    
}

?>