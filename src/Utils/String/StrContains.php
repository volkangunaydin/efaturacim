<?php
namespace Efaturacim\Util\Utils\String;

use Efaturacim\Util\CastUtil;

    class StrContains{
        public static function contains($haystack,$needle,$minIndex=0,$caseInsensitive=false){
            if(is_array($haystack)){
                foreach ($haystack as $item){
                    $p = self::contains($item, $needle,$minIndex,$caseInsensitive);                    
                    if($p){ return $p; }
                }
            }else if(is_array($needle)){
                foreach ($needle as $needleItem){
                    $p = self::contains($haystack, $needleItem,$minIndex,$caseInsensitive);
                    
                    if($p){ return $p; }
                }
                return false;
            }else if(StrUtil::notEmpty($haystack) && StrUtil::notEmpty($needle)){
                if($minIndex>0){
                    if($caseInsensitive){
                        $p1 = stripos($haystack, $needle);
                    }else{
                        $p1 = strpos($haystack, $needle);
                    }
                    
                    if($p1 && $p1>=$minIndex){
                        return true;
                    }else{
                        return false;
                    }
                }else if (!function_exists('str_contains')) {
                    if(function_exists("mb_stripos")){
                        return $needle !== '' && ( $caseInsensitive ? mb_stripos($haystack, $needle) : mb_strpos($haystack, $needle) ) !== false;
                    }else{
                        return $needle !== '' && ( $caseInsensitive ? mb_stripos($haystack, $needle) : strpos($haystack, $needle) ) !== false;
                    }                    
                }else{
                    
                    if($caseInsensitive){
                        $p1 = stripos($haystack, $needle);
                        //\Vulcan\V::dump(array($haystack,$needle,"ci"=>$caseInsensitive,$p1));
                        return $p1!==false;
                    }else{
                        return str_contains($haystack,$needle);
                    }
                    
                }                
            }
            return false;
        }
        public static function containsAnyOf($haystack,$needles,$caseInsensitive=false){
            if($haystack && strlen("".$haystack)>0 && is_array($needles) && count($needles)>0){
                foreach ($needles as $needle){                    
                    if(self::contains($haystack, $needle,0,$caseInsensitive)){
                        return true;
                    }
                }
            }
            return false;
        }
        public static function containsAll($haystack,$needles){
            $r = true;
            if($haystack && strlen("".$haystack)>0 && is_array($needles) && count($needles)>0){
                foreach ($needles as $needle){
                    if(!self::contains($haystack, $needle)){
                        $r = false;
                    }
                }
            }else{
                $r = false;
            }
            return $r;
        }
        public static function startsWith($str,$startText) {
            if(!is_null($startText) & is_array($startText)){
                foreach($startText as $startTextStr){ if(self::startsWith($str,$startTextStr)){ return true; } }
                return false;
            }else{
                return (!is_null($str) && !is_null($startText) && strlen($str)>=strlen($startText) && (substr($str, 0,strlen($startText))."")==($startText.""));
            }
        }
        public static function endsWith($str,$endText,$trim=false) {
            if(!is_null($endText) & is_array($endText)){
                foreach($endText as $endTextStr){ if(self::endsWith($str,$endTextStr)){ return true; } }
                return false;
            }else{
                if($trim){
                    $s = str_replace(array("\r","\n"), array("",""), trim("".$str));
                    return (!is_null($s) && !is_null($endText) && strlen($s)>=strlen($endText) && (substr($s, -1*strlen($endText))."")==($endText.""));
                }
                return (!is_null($str) && !is_null($endText) && strlen($str)>=strlen($endText) && (substr($str, -1*strlen($endText))."")==($endText.""));
            }                         
        }
        
        public static function lhsRhs($str,$endText) {
            $lhs = null;
            $rhs = null;
            if(is_array($endText)){
                foreach ($endText as $v){
                    list($lhs,$rhs) = self::lhsRhs($str, $v,null);
                    if(strlen("".$lhs)>0 && strlen("".$rhs)>0){
                        return array($lhs,$rhs);
                    }
                }
                return array("","");
            }
            if(function_exists("mb_stripos")){
                $p = mb_stripos($str, $endText);
                if($p!==false && $p>0){
                    return array(
                        trim("".mb_substr($str, 0,$p))
                        ,trim("".mb_substr($str, $p+mb_strlen($endText)))
                    );                                        
                }
                return array("","");
            }else{
                $p = stripos($str, $endText);
                if($p!==false && $p>0){
                    return array(
                        trim("".substr($str, 0,$p))
                            ,trim("".substr($str, $p+strlen($endText)))
                    ); 
                    
                }
                return array("","");
            }
            return array($lhs,$rhs);
        }
        public static function lhs($str,$endText,$defVal="") {
            if(is_array($endText)){
                foreach ($endText as $v){
                    $lhs = self::lhs($str, $v,null);
                    if(strlen("".$lhs)>0){
                        return $lhs;
                    }
                }
                return "";
            }
            if(function_exists("mb_stripos")){
                $p = mb_stripos($str, $endText);
                if($p!==false && $p>0){
                    return mb_substr($str, 0,$p);
                }
                return $defVal;                
            }else{
                $p = stripos($str, $endText);
                if($p!==false && $p>0){
                    return substr($str, 0,$p);
                }
                return $defVal;                
            }
        }
        public static function rhs($str,$startText,$defVal="") {
            if(function_exists("mb_stripos")){
                $p = mb_stripos($str, $startText);
                if($p!==false && $p>=0){
                    return mb_substr($str, $p+mb_strlen($startText));
                }
                return $defVal;                
            }else{
                $p = stripos($str, $startText);
                if($p!==false && $p>=0){
                    return substr($str, $p+strlen($startText));
                }
                return $defVal;                
            }
        }
        public static function rhsAs($str,$startText,$type=null,$defVal="") {
            return CastUtil::getAs(self::rhs($str, $startText, ""),$defVal,$type);
        }
        public static function ensureEndChar($str,$endText){
            if(!self::endsWith($str, $endText)){
                return $str.$endText;
            }
            return $str;
        }
        public static function removeChar($str,$index){            
            if($index<0 || $index>=strlen($str)){
                return $str;
            }
            if(strlen($str)==($index+1)){
                return substr($str, 0,-1);
            }else if($index>0){
                //\Vulcan\V::dump(array("index"=>$index,"len"=>strlen($str),"str"=>$str));
                return substr($str, 0,$index)."".substr($str, $index+1);
            }else{
                return substr($str, 1);
            }
            return $str;
        }
        public static function unQuote($str){
            $p1 = strpos($str, '"');
            $p2 = strrpos($str, '"');
            $p1Alt = strpos($str, "'");
            $p2Alt = strrpos($str, "'");  
            
            //\Vulcan\V::dump(array($p1,$p2,$p1Alt,$p2Alt));
            if($p1!==false && $p2!==false && $p2>$p1){
                if($p1Alt!==false && $p2Alt!==false && $p1Alt>$p1 && $p2Alt>$p2 && $p2Alt>$p1Alt){
                    return self::removeChar(self::removeChar($str,$p2Alt), $p1Alt);
                }
                return self::removeChar(self::removeChar($str,$p2), $p1);
            }
            if($p1Alt!==false && $p2Alt!==false && $p2Alt>$p1Alt){                
                return self::removeChar(self::removeChar($str,$p2Alt), $p1Alt);
            }            
            return $str;
        }
        
    }

?>