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
        $arr = @json_decode($string,true);
        $err = json_last_error();
        if($err === JSON_ERROR_NONE){
            return true;
        }
        return false;
    }    
}