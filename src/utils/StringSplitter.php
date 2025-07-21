<?php
namespace Efaturacim\Util\Utils;

use Efaturacim\Util\CastUtil;
use Efaturacim\Util\Options;

    class StringSplitter{
        public static function splitWithWidth($string,$charLimit=0,$splitFromWords=false,$trim=false){
            if($charLimit<=0){ return array($string); }
            $arr = array();
            if($string && strlen($string)>0){
                if(strlen($string)>$charLimit){
                    $kalan = $string;
                    $i = 0;
                    while (strlen($kalan)>$charLimit && $i<10000) {
                        $i++;
                        $s1    = mb_substr($kalan, 0,$charLimit);                                              
                        $kalan = mb_substr($kalan, $charLimit);
                        if($splitFromWords && strlen($kalan)>0){
                            $chr1 = substr($s1,-1);
                            $chr2 = trim("".substr($kalan,1,1));
                            if($chr1==" " || $chr1=="." || $chr2=="" || $chr2=="."){
                                
                            }else{
                                $p = self::getLastPos($s1, array(" ",".",","));
                                if($p>0){
                                    $s1_kalan = substr($s1,$p);
                                    $s1 = substr($s1,0,$p);
                                    $kalan = $s1_kalan.$kalan;
                                    //\Vulcan\V::dump(array($s1_kalan,$s1,$kalan));
                                }
                                
                            }
                        }
                        if($trim){
                            $s1     = trim("".$s1);                            
                            $kalan  = trim("".$kalan);
                        }
                        $arr[] = $s1;                        
                    }
                    if(strlen($kalan)>0){
                        $arr[] = $kalan;
                    }                                    
                    
//                     $s2  = mb_substr($string, $charLimit);
//                     \Vulcan\V::dump($charLimit);
//                     $tmp = self::splitWithWidth($s2);
//                     foreach ($tmp as $v){ $arr[] = $v; }
                }else{
                    $arr[] = $string;
                }                                
            }
            return $arr;
        }
        public static function getLastPos($s,$arrNeedles){
            $r = -1;
            if($arrNeedles && is_array($arrNeedles) && strlen("".$s)>0){
                foreach ($arrNeedles as $needle){
                    $p = strrpos($s, $needle);
                    if($p && $p>0 && $p>$r){
                        $r = $p;
                    }
                }
            }
            return $r;
        }
        public static function withNewLines($string){
            return self::newLines($string,false,false,null,null,0);
        }
        public static function newLines($string,$trim=true,$removeEmpty=false,$options=null,$regEx=null,$charLimit=0){
            $res   = array();            
            if(is_null($string) || $string==""){ return $res; }
            if(is_null($regEx)){  $regEx = "/\r\n|\n|\r/"; }
            if(Options::ensureParam($options) && $options instanceof Options){
                $limit = $options->getAsInt("limit",-1);
                $array = preg_split($regEx, "".$string,$limit);
                if($array && is_array($array)){
                    foreach($array as $k=>$v){
                        $key = null;
                        if(is_scalar($v)){
                            if($trim){$v = trim($v);}
                        }
                        if($removeEmpty){
                            if(is_null($v) || $v==""){
                                continue;
                            }
                        }
                        if($charLimit && strlen($v)>$charLimit){
                            $aa = self::splitWithWidth($v,$charLimit);
                            foreach ($aa as $vv){
                                if(is_null($key)){
                                    $res[]  = $vv;
                                }else{
                                    $res[$key] = $vv;
                                }
                            }
                            continue;
                        }
                        if(is_null($key)){
                            $res[] = $v;
                        }else{
                            $res[$key] = $v;
                        }
                    }
                    $delimiter = $options->getAsString("key_delimiter");
                    if(\Vulcan\V::notEmptyString($delimiter)){
                        $reverseKey = $options->getAsBool("reverse_key");
                        $arr = array();
                        foreach ($res as $val){
                            $tmp = preg_split($delimiter, "".$val,2);
                            if($tmp && count($tmp)==2){
                                $key = "".trim("".$tmp[0]);
                                $val = "".trim("".$tmp[1]);
                                if($reverseKey){
                                    $arr[$val] = $key;
                                }else{
                                    $arr[$key] = $val;
                                }
                            }
                        }
                        return $arr;
                    }
                }
            }
            return $res;
        }
        public static function splitWithSpash($str,$options=null,$removeEmpty=false){
            $regEx = "/[\\/]/is";
            return self::newLines($str,true,$removeEmpty,$options,$regEx);
        }
        public static function splitWithSlash($str,$options=null,$removeEmpty=false){
            $regEx = "/[\\/]/is";
            return self::newLines($str,true,$removeEmpty,$options,$regEx);
        }
        public static function splitWithDirSeperator($str,$options=null,$removeEmpty=false){
            $regEx = "/[\\/|\\\\]/is";
            return self::newLines($str,true,$removeEmpty,$options,$regEx);
        }

        public static function splitWithDash($str,$options=null,$removeEmpty=false){
            $regEx = "/[-]/is";
            return self::newLines($str,true,$removeEmpty,$options,$regEx);
        }
        public static function splitWithDashOrSubDash($str,$options=null,$removeEmpty=false){
            $regEx = "/[-_]/is";
            return self::newLines($str,true,$removeEmpty,$options,$regEx);
        }
        public static function splitWithPipe($str,$options=null,$removeEmpty=false){
            $regEx = "/[\\|]/is";
            return self::newLines($str,true,$removeEmpty,$options,$regEx);            
        }
        
        public static function withDotsOrSpaces($str,$options=null){
            $regEx = "/[.| |\\t]/is";
            return self::newLines($str,true,true,$options,$regEx);            
        }
        public static function withDots($str,$options=null){
            $regEx = "/[.]/is";
            return self::newLines($str,true,true,$options,$regEx);
        }
        public static function withPipe($str,$options=null){
            $regEx = "/[\\|]/is";
            return self::newLines($str,true,true,$options,$regEx);
        }
        public static function withComaOrSemiColon($str,$options=null){
            $regEx = "/[,|;]/is";
            return self::newLines($str,true,true,$options,$regEx);
        }
        public static function withComa($str,$options=null){
            $regEx = "/[,]/is";
            return self::newLines($str,true,true,$options,$regEx);
        }
        public static function withSpaces($str,$options=null){
            $regEx = "/[ |\\t]/is";
            return self::newLines($str,true,true,$options,$regEx);            
        }
        public static function splitWithNewLineOrComa($str,$options=null){
            $regEx = "/[,|\\|||\r\n|\n|\r]/is";
            return self::newLines($str,true,true,$options,$regEx);     
        }
        
        public static function withSmart($str,$options=null){
            $regEx = "/[ |\\t|-]/is";
            return self::newLines($str,true,true,$options,$regEx);
        }
        public static function csvStringToArray($string,$seperator=";",$enclosure = "\"",$escape = "\\"){
            if(\Vulcan\V::notEmptyString($string)){
                try {
                    $arr = str_getcsv($string,$seperator,$enclosure,$escape);
                    if($arr && is_array($arr) && count($arr)>0){
                        return $arr;
                    }
                } catch (\Exception $e) {
                }
            }
            return array();
        }
        protected static function __getSide($sideStr,$str,$prefix,$type=null,$trim=false,$defVal=null){
            $s   = $str;
            $pos = strpos($str, $prefix);
            if($sideStr=='r' && $pos===false){
                return ''.$defVal;
            }
            if($pos !== false){
                if($sideStr=='r'){                                     
                    $s = substr($str,$pos+strlen($prefix));                    
                }else{
                    $s = substr($str, 0,$pos);
                }                
            }else if(!is_null($defVal)){ 
                return $defVal; 
            }
            if($trim){ $s = trim($s); }
            if(!is_null($type)){                
                return CastUtil::getAs($s,$type,$defVal);
            }
            return "".$s;
        }
        public static function hasRhs($str,$prefix,$type=null,$trim=false,$defVal=null){
            $a = self::rhs($str, $prefix,$type,$trim,$defVal);
            return strlen("".$a)>0;
        }        
        public static function rhs($str,$prefix,$type=null,$trim=false,$defVal=null){
            return self::__getSide("r", $str, $prefix,$type,$trim,$defVal);
        }
        public static function lhs($str,$prefix,$type=null,$trim=false,$defVal=null){
            return self::__getSide("l", $str, $prefix,$type,$trim,$defVal);
        }
        public static function rhsLhs($str,$prefix,$defValForRhs,$type=null,$trim=false,$defVal=null){
            $res = array($str,$defValForRhs);
            $rhs = self::rhs($str, $prefix,$type,$trim,"");
            if(strlen("".$rhs)>0){
                $lhs = self::lhs($str, $prefix,$type,$trim,"");
                if(strlen("".$lhs)>0){
                    return array($lhs,$rhs);
                }
            }
            return $res;
        }        
        public static function partsFrom($org,$seperatorOrSeperators){
            $arr = array();
            if($seperatorOrSeperators && is_array($seperatorOrSeperators) && count($seperatorOrSeperators)>0){
                
            }else if (is_string($seperatorOrSeperators) && strlen("".$seperatorOrSeperators)>0){                                
                return explode($seperatorOrSeperators, $org);                                
            }
            return $arr;
        }
        public static function withChars($str,$chars,$defVal=null,$dataType=null){
            $r = array();
            $currPos = -1;
            $doContinue = true;
            if(is_array($chars) && count($chars)>0){
                $i=0;
                do{
                    $i++;
                    $p = StrPos::getNextPos($str, $chars,$currPos+1);                    
                    if($p>0){                        
                        $v   = trim("".substr($str, $currPos+1,$p-($currPos+1)));                        
                        $r[] = $v;                       
                        $currPos = $p;                        
                    }else{
                        $doContinue = false;
                    }
                }while($i<1000 && $doContinue);                                                
            }
            if($currPos<strlen($str)){
                $sonParca = trim("".substr($str, $currPos+1,strlen($str)-($currPos+1)));
                if(strlen("".$sonParca)>0){
                    $r[] = $sonParca;
                }
            }
            if(!is_null($dataType)){
                $r2 = array();
                foreach ($r as $v){
                    $r2[] = CastUtil::getAs($v,$defVal,$dataType);
                }
                return $r2;
            }        
            return $r;
        }
        public static function explode($str,$seperator=","){
            $ret = array();            
            if($seperator=="smart"){                
                $tmp = preg_split("/[,;]/is",$str);
            }else{
                $tmp = explode("".$seperator,$str);
            }            
            foreach ($tmp as $v){
                $s = trim("".$v);
                if(strlen("".$s)>0 && !in_array($s, $ret)){
                    $ret[] = $s;
                }
            }            
            return $ret;
        }
        public static function merge($arr1,$arr2){
            $ret = array();
            if($arr1 && is_array($arr1) && count($arr1)>0){
                foreach ($arr1 as $v){
                    $s = trim("".$v);
                    if(strlen("".$s)>0 && !in_array($s, $ret)){
                        $ret[] = $s;
                    }
                }
            }
            if($arr2 && is_array($arr2) && count($arr2)>0){
                foreach ($arr2 as $v){
                    $s = trim("".$v);
                    if(strlen("".$s)>0 && !in_array($s, $ret)){
                        $ret[] = $s;
                    }
                }
            }
            return $ret;
        }
        public static function getPartAs($str,$posIndex,$type=null,$defVal=null,$options=null){
           $tmp = self::withSmart($str,$options);
           ///print_r(array("org"=>$str,"res"=>$tmp));die("");
           if($tmp && is_array($tmp) && count($tmp)>=$posIndex){
               return CastUtil::getAs(@$tmp[$posIndex],$defVal,$type);
           }
           return $defVal;     
        }
    }

?>