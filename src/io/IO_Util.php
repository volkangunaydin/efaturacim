<?php
namespace Efaturacim\Util\IO;

use Efaturacim\Util\Options;
use Efaturacim\Util\SimpleResult;
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
}
?>