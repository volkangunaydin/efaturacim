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
        if($path && file_exists($path) && is_file($path)){
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
    public static function getSafePath($path,$isFile=true){
        if($path && file_exists($path) && is_dir($path)){
            $path = "".$path;
        }else if($isFile){
            $path = dirname($path);
        }
        $path = str_replace("\\","/",$path);
        $path = str_replace("//","/",$path);
        if(substr("".$path,-1) !== "/"){
            $path .= "/";
        }
        return $path;
    }   
    public static function getExtensionFromName($name,$toLower=false,$canBeExtension=true){
        return FileExtensions::getExtensionFromName($name,$toLower,$canBeExtension);
    }   
    public static function file_exists($path){
        return file_exists($path);
    }
    public static function is_writable($path){
        return file_exists($path) && is_writable($path);
    }
    public static function writeFileAsString($path,$content){
        return file_put_contents($path,$content);
    }
}
?>