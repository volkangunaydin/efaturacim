<?php
namespace Efaturacim\Util\Utils\IO;

use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StrBase64;


class IO_Util{
    public static function readFileAsString($path,$options=null){
        return self::readFile($path, $options)->value;
    }
    public static function readFile($path,$options){
        $r = new SimpleResult();
        Options::ensureParam($options);
        if($path && file_exists($path) && is_readable($path)){
            $r->setIsOk(true);
            $r->value = file_get_contents($path);
        }
        if($options instanceof Options){
            if($options->getAsBool(array("return_as_string","as_string"))){
                return $r->value;
            }
        }
        return $r;
    }        
    public static function readFileAsBase64EncodedString($path,$options=null){
        return StrBase64::encode(self::readFileAsString($path,$options));
    }
}
?>