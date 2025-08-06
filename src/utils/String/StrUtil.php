<?php

namespace Efaturacim\Util\Utils\String;

class StrUtil{
    public static function notEmpty($str){
        return !is_null($str) && is_scalar($str) && strlen("".$str)>0;
    }
    public static function isEmpty($str){
        if(is_null($str) || $str==="" || (is_string($str) && trim($str)==="") || !is_scalar($str) ){
            return true;
        }
        return false;
    }
    public static function isXML($string,$softCheck=true) {
        $string = trim((string) $string);
        if (self::isEmpty($string)) {
            return false;
        }

        // A "soft" check is unreliable and can lead to false positives.
        // For robust validation, it's best to always use a proper parser.
        // This implementation performs a full validation to ensure correctness.
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        // Suppress warnings from loadXML, we'll check them with libxml_get_errors
        $loaded = @$doc->loadXML($string);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        return $loaded !== false && empty($errors);
    }
    public static function isJson($string,$softCheck=false) {
        $string = trim("".$string);
        if (self::isEmpty($string)) {
            return false;
        }        
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
        public static function getGUID($lower=true){
            $guid = self::_getGUID();
            if($lower){
                $guid = strtolower($guid);
            }
            return $guid;
        }
        protected static function _getGUID(){
            if (function_exists('com_create_guid') === true){
                return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
                // return trim(com_create_guid(), '{}');
            }else{
                return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
            }
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
        public static function parse($str,$arr,$smart=true,$esc=false,$recursive=false,$depth=0){
            if(is_null($str) || $str===""){ return ""; }
            if($depth>9){ return $str; }
            if (is_null($arr) || !is_array($arr) || count($arr)==0){ return $str; }
            $strOut = $str;
            if (is_array($str)){
                $t = array();
                foreach ($str as $kk=>$vv){ $t[$kk] = self::parse($vv, $arr); }
                return $t;
            }
            if(is_scalar($str) && $smart && key_exists($str, $arr)){ return @$arr[$str]; }
            if ($esc){
                $escSearch = array("{ ");
                $escReplace = array("__CBO__");
                $escReplace2 = array("{");
                $str = str_replace($escSearch, $escReplace, $str);
            }
            $matches = null;
            preg_match_all("/\\{([^\\}]*)\\}/i",@$str."",$matches);
            $counter=0;
            if (count($matches)>1 && count($matches[1])>0){
                foreach($matches[1] as $kk=>$vv){
                    $counter++;
                    if (is_string($vv)){
                        $strOut = str_replace("".@$matches[0][$kk],"".@$arr[$vv],"".$strOut);
                    }else if (is_array($vv) && isset($vv["func"])){
                        $params = array(@$vv["val"]);
                        $strOut = call_user_func_array($vv["func"], $params);
                    }else if (is_array($vv) && isset($vv["userfunc"])){
                        $params = array($kk,$vv,$matches[0][$kk]);
                        $strOut = call_user_func_array($vv["userfunc"], $params);
                    }
                }
            }
            if ($esc){
                $str = str_replace($escReplace, $escReplace2, $str);
            }
            if($counter>0 && $recursive){
                return self::parse($strOut, $arr,$smart,$esc,true,$depth+1);
            }
            return $strOut;
        }	
        public static function arrayToString($listOfArray,$func=null){
            $s = '';
            if($listOfArray && is_array($listOfArray) && count($listOfArray)>0 && $func && is_callable($func)){
                $indexFrom1 = 0;
                foreach ($listOfArray as $k=>$v){
                    if(is_array($v)){
                        $indexFrom1++;
                        $s .= call_user_func_array($func, array($v,$indexFrom1));                        
                    }
                }              
            }
            return $s;
        }
        public static function parseSmart($arr,$format,$defVal=""){
            if($arr && is_array($arr) && !is_null($format)){
                if(is_callable($format)){
                    return call_user_func($format, array($arr));                
                }else if(is_string($format) && key_exists($format, $arr)){                    
                    return @$arr[$format];
                }else if(is_string($format) && strlen("".$format)>0){                    
                    return self::parse($format,$arr,true,false);
                }
            }
            return $defVal;
        }        
        public static function encodeBase64($str){
            if($str && is_object($str) && !is_string($str)){
                return @base64_encode(serialize($str));
            }else if($str && is_array($str)){
                return @base64_encode(serialize($str));
            }else if($str && strlen($str)>0){
                try {
                    $a = @base64_encode($str);
                    if($a && strlen("".$a)>0){
                        return $a;
                    }
                } catch (\Exception $e) {
                }
            }
            return "";
        }
        public static function decodeBase64($str){
            if($str && strlen($str)>0){
                try {
                    $a = base64_decode($str);
                    if($a && strlen("".$a)>0){
                        return $a;
                    }
                } catch (\Exception $e) {
                }
            }
            return null;
        }                
        public static function trimAllExceptNumbers($s){
            if (is_null($s) || !is_scalar($s)) {
                return '';
            }
            return preg_replace('/[^0-9]/', '', (string) $s);
        }
        public static function len($str) {
            if (is_null($str) || !is_scalar($str)) { return 0;}
            return strlen((string)"".$str);
        }        
        public static function toLowerEng($str){ 
            if(!function_exists("mb_strtolower")){
                return str_replace(array("Ğ","Ü","Ş","İ","Ö","Ç","ğ","ü","ş","ı","ö","ç","Ë"), array("G","U","S","I","O","C","g","u","s","i","o","c","E"), "".$str);
            }            
            return mb_strtolower(self::removeTurkishChars($str)); 
        }        
}