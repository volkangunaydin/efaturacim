<?php
namespace Efaturacim\Util\Utils\IO;

use Efaturacim\Util\Utils\String\StrUtil;

    class FileExtensions{
        public static function getExtensionFromName($name,$toLower=true,$canBeExtension=true){
            if(StrUtil::isEmpty($name) || !is_string($name)){ return ""; }
            if($canBeExtension && strlen($name)<=4 && strpos($name, ".")===false){                
                return $toLower ? strtolower($name) : $name;
            }
            $ext =  pathinfo($name, PATHINFO_EXTENSION);
            if(!is_null($ext) && $toLower){ $ext = strtolower($ext); }
            return $ext;
        }
        public static function getBaseNameOfFile($name){
            $p1 = strrpos("".$name, ".");
            if($p1!==false && $p1>0){
                return substr($name, 0, $p1);
            }
            return "";
        }
        public static function getImageExtensions(){
            return array("png","jpg","jpeg","bmp","gif");
        }   
        public static function getVideoExtensions(){
            return array("mp4","avi","mpeg","mov","m4v");
        }   
        public static function getSoundExtensions(){
            return array("mp3","m4a");
        }  
        public static function getThumbExtensions(){
            return array("db");
        }  
        public static function getPdfExtensions(){
            return array("pdf");
        }  
        public static function hasExtension($filepath,$extensionArray){
            if(is_null($extensionArray) || !is_array($extensionArray)){ return false; }
            $ext = self::getExtensionFromName($filepath,true);
            return in_array("".$ext, $extensionArray);
        }
        public static function isPdf($filepath){
            return self::hasExtension($filepath, self::getPdfExtensions());            
        }
        public static function isThumbFile($filepath){
            return self::hasExtension($filepath, self::getThumbExtensions());            
        }        
        public static function isImage($filepath){            
            return self::hasExtension($filepath, self::getImageExtensions());
        }
        public static function isVideoFile($filepath){            
            return self::hasExtension($filepath, self::getVideoExtensions());
        }
            public static function isImageFile($filepath){            
            return self::hasExtension($filepath, self::getImageExtensions());
        }
        public static function isSoundFile($filepath){            
            return self::hasExtension($filepath, self::getSoundExtensions());
        }
        public static function isImageExtension($ext){            
                return in_array("".$ext, self::getImageExtensions());
        }
        public static function isSoundExtension($ext){            
                return in_array("".$ext, self::getSoundExtensions());
        }
        public static function isWord($filepath){
            return self::hasExtension($filepath, array("doc","docx"));
        }
        public static function isWordOrPdf($filepath){
            return self::hasExtension($filepath, array("doc","docx","pdf"));
        }
        public static function getSafeFileName($filePath){
            if(StrUtil::notEmpty($filePath)){                
                $s = StrUtil::toVarName($filePath,"",array("template"=>"file"));
                while(StrUtil::startsWith($s, ".")){
                    $s = substr($s, 1);
                }
                return $s;
            }
            return "";
        }
    }
?>