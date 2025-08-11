<?php
namespace Efaturacim\Util\Utils\String;

                                                                
class StrBase64{
        public static function decode($str){
            if($str && strlen($str)>0){
                try {
                    $a = base64_decode($str);
                    if($a && strlen("".$a)>0){
                        return $a;
                    }
                } catch (\Exception $e) {
                }
            }
            return null;
        }
        public static function encode($str){
            if($str && is_object($str) && !is_string($str)){
                return @base64_encode(serialize($str));
            }else if($str && is_array($str)){
                return @base64_encode(serialize($str));
            }else if($str && strlen($str)>0){
                try {
                    $a = @base64_encode($str);
                    if($a && strlen("".$a)>0){
                        return $a;
                    }
                } catch (\Exception $e) {
                }
            }
            return "";
        }
        public static function decodeAsJson($base64Encoded=""){
            $s =  self::decode($base64Encoded);
            if($s && is_string($s) &&strlen("".$s)>0){
                return json_decode($s,true);
            }
            return array();
        }
        public static function decodeAsArray($base64Encoded=""){
            $s = self::decode($base64Encoded);
            if($s && strlen("".$s)>0){
                try {
                    $a = @unserialize($s);
                    if(!is_null($a)){
                        if(!is_array($a) && is_object($a)){
                            return json_decode(json_encode ( $a ) , true);
                        }
                        return $a;
                    }
                } catch (\Exception $e) {
                }
            }
            return null;            
        }
        public static function decodeAsObject($base64Encoded=""){
            $s = self::decode($base64Encoded);
            if($s && strlen("".$s)>0){
                try {
                    $a = @unserialize($s);                    
                    if(!is_null($a)){
                        return $a;                        
                    }
                } catch (\Exception $e) {
                }
            }
            return null;   
        }        
        public static function hexToBase64($hex){
            $return = '';
            foreach(str_split($hex, 2) as $pair){
                $return .= chr(hexdec($pair));
            }
            return base64_encode($return);
        }
        public static function img($file){
            $s = '';
            if($file && strlen("".$file)>0 && file_exists($file)){
                return self::getBase64EncodedImage($file,file_get_contents($file),false);
            }
            return $s;
        }
        public static function getBase64EncodedImage($fileName,$content=null,$returnOnlyBase64String=true){
            if($fileName && strlen("".$fileName)>0){
                $ext = FileExtensions::getExtensionFromName($fileName,true);                
                if($ext=="jpg"){ $ext=  "jpeg"; }                                
                if($returnOnlyBase64String){
                    $img = 'data:image/'.$ext.';base64,'.self::encode("".$content).'';                
                }else{
                    $img = '<img src="data:image/'.$ext.';base64,'.self::encode("".$content).'" />';                
                }
                
                return $img;
            }
            return "";
        }
}
?>