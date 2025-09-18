<?php
namespace Efaturacim\Util\Utils\String;

class StrSegment{
    public static function getLastSegmentOf($string,$delimiters=array("\\","/"),$getIfThereIsNoSegments=true){
        $string = str_replace($delimiters,"|",$string);
        $string = trim($string);
        $arr = explode("|",$string);
        if($getIfThereIsNoSegments==false && count($arr)==0){
            return null;
        }
        $string = array_pop($arr);
        return $string;
    }
}

?>