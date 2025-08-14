<?php
namespace Efaturacim\Util\Utils\Array;

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
}
?>  