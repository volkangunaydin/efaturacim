<?php

namespace Efaturacim\Util;

class StrUtil{
    public static function test(): int
    {
        die("TEST IS OK ");
    }
    public static function notEmpty($str){
        return !is_null($str) && is_scalar($str) && strlen("".$str)>0;
    }
    public static function isJson($string,$softCheck=false) {
        $string = trim("".$string);
        if($softCheck){
            if(substr($string,0,1)=="[" && substr($string,-1,1)=="]"){
                return true;                
            }else if(substr($string,0,1)=="{" && substr($string,-1,1)=="}"){
                return true;
            }
        }        
        if(is_null($string) || is_numeric($string)){ return false; }
        $arr = @json_decode($string,true);
        $err = json_last_error();
        if($err === JSON_ERROR_NONE){
            return true;
        }
        return false;
    }    
        public static  function removeTurkishChars($str){ if(is_array($str)){ $res = array(); foreach ($str as $k=>$v){ $res[$k] = self::removeTurkishChars($v); } return $res; }else{ return str_replace(array("Ğ","Ü","Ş","İ","Ö","Ç","ğ","ü","ş","ı","ö","ç","Ä","ä","Ё","ё"), array("G","U","S","I","O","C","g","u","s","i","o","c","A","a","E","e"), "".$str); } }
        public static function toLowerTurkish($input){ if (is_array($input)){ $arr = array(); foreach ($input as $k=>$v){ $arr[$k] = self::toLowerTurkish($v); } return $arr;}else{ return mb_strtolower(str_replace( array("Ğ","Ü","Ş","I","İ","Ö","Ç"),array("ğ","ü","ş","ı","i","ö","ç"), "".$input),"UTF-8"); } }
        public static function toUpperTurkish($input){ if (is_array($input)){ $arr = array();foreach ($input as $k=>$v){ $arr[$k] = self::toUpperTurkish($v); } return $arr; }else{ return mb_strtoupper( str_replace(array("ğ","ü","ş","ı","i","ö","ç"), array("Ğ","Ü","Ş","I","İ","Ö","Ç"), "".$input),"UTF-8");} }
        public static function toEng($input){ return self::removeTurkishChars($input); }        
        public static function startsWith($str,$startText) { 
            if(!is_null($startText) & is_array($startText)){                   
                foreach($startText as $startTextStr){
                    if(self::startsWith($str,$startTextStr)){ 
                        return true; 
                    }
                }
                return false;
            }else{
                return (!is_null($str) && !is_null($startText) && strlen($str)>=strlen($startText) && (substr($str, 0,strlen($startText))."")==($startText."")); 
            }            
        }
        public static function endsWith($str,$endText) { return (!is_null($str) && !is_null($endText) && strlen($str)>=strlen($endText) && (substr($str, -1*strlen($endText))."")==($endText."")); }    
        public static function startsWithNumber($str) { return preg_match('/^\d/', $str) === 1; }
        public static function upper($str){ return self::toUpperTurkish($str); }
        public static function lower($str){ return self::toLowerTurkish($str); }
        public static function trimNewLines($strIn,$newLineReplacement=""){ return trim(str_replace(array("\r\n","\r","\n"),array($newLineReplacement,$newLineReplacement,$newLineReplacement),"".$strIn)); }
        public static function trimNonPrintable($str){
            return  preg_replace('/[[:^print:]]/', '', $str);
        }
        public static function trimAllSpaces($str,$replaceNonAscii=false){
            if($replaceNonAscii){ self::trimNonPrintable($str); }
            return trim(str_replace(array(" ","\t"), array("",""), trim(self::trimNewLines($str,"") ))); 
        }        
        public static function trimAllNonAlhaNumeric($str,$replaceNonAscii=false){
            $str2 = preg_replace('/[^a-zA-Z0-9çÇğĞıİöÖşŞüÜ]/u', '', "".$str);
            return self::trimAllSpaces($str2,$replaceNonAscii);
        }
        public static function onlyNumeric($str,$canBeNegative=false){ $unwantedChars = null; return self::onlyChars($str, ($canBeNegative?"0123456789-":"0123456789"), $unwantedChars); }
        protected static function getCharsForName(){
            return "ABCDEFGHIJKLMNOPRSTUVYZQWXabcdefghijklmnoprstuvyzqwxüğçöışÜĞÇÖİŞ .";
        }
        public static function onlyChars($str,$onlyChars,&$unwantedChars){
            if($str && is_array($str)){
                $ret = array();
                foreach ($str as $k=>$v){ $ret[$k] = self::onlyChars($v, $onlyChars, $unwantedChars); }
                return $ret;
            }
            $return = "";
            if($onlyChars=="::name"){
                $onlyChars = self::getCharsForName();
            }else if ($onlyChars=="::hash"){
                $onlyChars = "0123456789-L";
            }else if ($onlyChars=="::guid"){
                $onlyChars = "0123456789ABCDEFabcdef-";
            }else if ($onlyChars=="::en"){
                $onlyChars = "ABCDEFGHIJKLMNOPRSTUVYZQWXabcdefghijklmnoprstuvyzqwx";
            }else if ($onlyChars=="::safe"){
                $onlyChars = "ABCDEFGHIJKLMNOPRSTUVYZQWXabcdefghijklmnoprstuvyzqwx0123456789";
            }
            $unwantedChars = array();
            for($i=0;$i<strlen("".$str);$i++){
                $c = substr("".$str, $i,1);
                if(strpos($onlyChars,$c ) !== false){ $return .= $c; }else{ $unwantedChars[$c] = $c; }
            }
            return $return;
        }

        public static function coalesce($arg1=null){
            $args = func_get_args();
            foreach ($args as $v){
                if(\Vulcan\V::notEmpty($v)){ return $v;
                }else if($v && is_int($v)){ return $v; }
            }
            return null;
        }
        public static function getNotEmpty($arg1=null){
            $args = func_get_args();
            foreach ($args as $v){                
                if(\Vulcan\V::notEmpty($v)){ return $v; 
                }else if($v && is_int($v)){ return $v; }
            }
            return null;
        }

}