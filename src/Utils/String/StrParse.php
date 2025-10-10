<?php
namespace Efaturacim\Util\Utils\String;
class StrParse{
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
    public static function smart($arr,$format,$defVal=""){
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
}
?>