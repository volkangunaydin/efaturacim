<?php
namespace Efaturacim\Util\Utils\Array;

class ArrayIn{
    public static function isArrayInArray($hugeArray,$tinyArray){
        if($hugeArray && is_array($hugeArray) && $tinyArray && is_array($tinyArray)){
            $isok = true;
            foreach($tinyArray as $k=>$v){
                if($isok && !in_array($v,$hugeArray)){
                    $isok = false;
                }
            }
            if($isok){ return $isok; }
        }
        return false;
    }
}

?>