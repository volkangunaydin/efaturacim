<?php
namespace Efaturacim\Util\Utils\Array;

use Efaturacim\Util\Utils\CastUtil;

class AssocArray{
    public static function newArray($options=null,$defVals=null,$initVals=null){
        $r = [];
        if(!is_null($initVals) && is_array($initVals)){
            foreach($initVals as $key=>$val){
                $r[$key] = $val;
            }
        }
        if(!is_null($defVals) && is_array($defVals)){
            foreach($defVals as $key=>$val){
                $r[$key] = $val;
            }
        }
        if(!is_null($options) && is_array($options)){
            foreach($options as $key=>$val){
                $r[$key] = $val;
            }
        }
        return $r;
    }
    public static function &getValByRef(&$arr,$keyOrArray,$defVal=null){
        $r = $defVal;
        if(is_array($arr)){
            if(is_array($keyOrArray)){
                foreach($keyOrArray as $key){
                    if(key_exists($key,$arr)){
                        return self::getValByRef($arr,$key,$defVal,$typeForCast);
                    }
                }
            }else if(is_scalar($keyOrArray) && key_exists($keyOrArray,$arr)){
                return  $arr[$keyOrArray];
            }            
        }
        return $r;
    }
    public static function getVal($arr,$keyOrArray,$defVal=null,$typeForCast=null){
        $r = $defVal;
        if(is_array($arr)){
            if(is_array($keyOrArray)){
                foreach($keyOrArray as $key){
                    if(key_exists($key,$arr)){
                        return self::getVal($arr,$key,$defVal,$typeForCast);
                    }
                }
            }else if(is_scalar($keyOrArray) && key_exists($keyOrArray,$arr)){
                $r = @$arr[$keyOrArray];
                if(!is_null($typeForCast) && !is_null($r)){
                    return CastUtil::getAs($r,$defVal,$typeForCast);
                }
            }
        }
        return $r;
    }
}
?>  