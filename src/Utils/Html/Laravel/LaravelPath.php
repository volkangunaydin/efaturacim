<?php
namespace Efaturacim\Util\Utils\Html\Laravel;

use Efaturacim\Util\Utils\Html\PrettyPrint\PrettyPrint;
use Efaturacim\Util\Utils\String\StrUtil;

class LaravelPath{
    protected static $basePath = null;
    
    public static function getBasePath($path=null){
        if(is_null(self::$basePath)){
            if(function_exists('base_path')){
                self::$basePath = base_path();
            }else{
                self::$basePath = \base_path();
            }
            self::$basePath = str_replace(array("\\"),array("/"),self::$basePath);
            if(substr(self::$basePath,-1)!="/"){
                self::$basePath .= "/";
            }
        }        
        if(!is_null($path) && $path!=""){
            $path = str_replace(array("\\"),array("/"),$path);
            if(substr($path,0,1)=="/"){
                $path = substr($path,1);
            }
            return self::$basePath.$path;
        }
        return self::$basePath;
    }
    public static function readFile($path){
        $path = self::getBasePath($path);
        if(file_exists($path) && is_readable($path)){
            return file_get_contents($path);
        }
        return "";
    }
    public static function prettyPrint(&$doc,$path,$type="auto"){
        $code = self::readFile($path);          
        if(StrUtil::notEmpty($code)){
            return PrettyPrint::smart($doc,$code,$type);
        }
        return $code;
    }
}   