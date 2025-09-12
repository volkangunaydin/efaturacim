<?php
namespace Efaturacim\Util\Utils\Json;

use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StrUtil;
use Exception;

class JsonUtil{
    public static function toJsonStringWithOptions($arrOrObject,$context=null){
        $jsonOptions = 0;$searchArray=array(); $replaceArray = array();
        $searchArray2=array(); $replaceArray2 = array();
        if(Options::ensureParam($context) && $context instanceof Options){
            $isDebug = $context->getAsBool("debug");
            if($context->getAsBool(array("indent","pretty_print","prettify","pretty"))){ $jsonOptions = $jsonOptions | JSON_PRETTY_PRINT; }
            if($context->getAsBool(array("unicode","utf8"),true)){ $jsonOptions = $jsonOptions | JSON_UNESCAPED_UNICODE; }
                            
            if($context->getAsBool(array("jsfunction","js_function"))){                   
                self::__searchFunction($arrOrObject,$searchArray,$replaceArray,0,"function(",$isDebug);
            }
            if($context->getAsBool("jquery_selector")){
                self::__searchFunction($arrOrObject,$searchArray2,$replaceArray2,0,"\$(");
            }
            if($context->getAsBool("raw_selector")){
                self::__searchFunction($arrOrObject,$searchArray2,$replaceArray2,0,"RAWSTRING:");
            }
        }        
        $s = "".json_encode($arrOrObject,$jsonOptions);          
        if(count($searchArray)>0){$s = str_replace($searchArray, $replaceArray, $s);}        
        if(count($searchArray2)>0){$s = str_replace($searchArray2, $replaceArray2, $s);}
        return $s;            
    }
    
    protected  static function __searchFunction(&$arrOrObject,&$searchArray,&$replaceArray,$depth=0,$startString=null,$isDebug=false){            
        if($depth>100){ return; }            
        if(is_object($arrOrObject) || is_array($arrOrObject)){
            if(is_null($startString) || $startString==""){ $startString = "function("; }                
            foreach ($arrOrObject as $k=>$v){                                        
                if(is_scalar($v)){                        
                    if(StrUtil::startsWith($v, $startString)){                            
                        $ss = "__SEARCH__AND__REPLACE__".(count($searchArray)+1);                                
                        $searchArray[]  = '"'.$ss.'"';
                        if($startString=="RAWSTRING:"){
                            $replaceArray[] = substr("".$v,10);                            
                        }else{
                            $replaceArray[] = $v;                            
                        }                            
                        if(is_object($arrOrObject)){
                            $arrOrObject->$k = $ss;
                        }else if(is_array($arrOrObject)){
                            $arrOrObject[$k] = $ss;
                        }
                    }
                }else if (is_object($v) || is_array($v)){
                    if(is_object($arrOrObject)){
                        self::__searchFunction($arrOrObject->$k, $searchArray, $replaceArray,$depth+1);
                    }else if(is_array($arrOrObject)){
                        self::__searchFunction($arrOrObject[$k], $searchArray, $replaceArray,$depth+1);
                    }
                    //self::__searchFunction($arrOrObject[], $searchArray, $replaceArray)
                }
            }
        }
    }           
    public static function toJsonOutput($arrOrObject,$context=null){
        if(!(php_sapi_name() === 'cli')){
            @header("HTTP/1.1 200 OK");
            header('Content-Type: application/json');                
        }
        echo self::toJsonStringWithOptions($arrOrObject,$context);            
        die("");        
    } 
    public static function readAsArray($path,$default=array()){
        if(file_exists($path) && is_readable($path)){
            return @json_decode(file_get_contents($path),true);
        }
        return $default;
    }
        /**
         * 
         * @param string $jsonString
         * @param VParams $options
         * @param array $defArr
         * @param number $depth
         * @return mixed|string|array|mixed|string|array
         */
        public static function getAsArray($jsonString=null,$options=null,$defArr=null,$depth=0){
            $arr     = array();
            Options::ensureParam($options);
            try {
                $a = @json_decode("".$jsonString,true);                
                if($a && is_array($a) && count($a)>0){
                    return $a;
                }else if($depth<=0 && $options->getAsBool(array("try"),false) && self::isJson($jsonString,true) ){
                    $jsonString2 = self::checkJsonStringAndReturn($jsonString);
                    return self::getAsArray($jsonString2,$options,$defArr,$depth+1);
                }
            } catch (Exception $e) {
                //throw $th;
                if($depth<=0 && $options instanceof Options && $options->getAsBool(array("try"),false) && self::isJson($jsonString)){
                    $jsonString2 = self::checkJsonStringAndReturn($jsonString);
                    return self::getAsArray($jsonString2,$options,$defArr,$depth+1);                    
                }
            }
            if(!is_null($defArr)){ return $defArr; }
            return $arr;
        }    
        public static function checkJsonStringAndReturn($jsonString=null){
            if(!is_null($jsonString) && strlen("".$jsonString)>0){
                try {
                    $a = self::convertLooseJsonToArray($jsonString);
                    //\Vulcan\V::dump($a);
                }catch (Exception $e) {
                    //throw $th;
                }
                //\Vulcan\V::dump($jsonString);                
            }
            return $jsonString;
        }
        public static function  convertLooseJsonToArray($json_string) {
            // Remove leading/trailing whitespace and potential control characters
            $json_string = trim("".$json_string);
            
            // Check if it's already valid JSON
            if (json_decode($json_string, true) !== null) {
                return json_decode($json_string, true);
            }
            $result = array();            
            return $result;
        }                
        public static function isJson($string,$softCheck=false) {
            $string = trim("".$string);
            if(substr($string,0,1)=="[" && substr($string,-1,1)=="]"){
                return true;                
            }else if(substr($string,0,1)=="{" && substr($string,-1,1)=="}"){
                return true;
            }
            $arr = @json_decode($string,true);
            $err = json_last_error();
            if($err === JSON_ERROR_NONE){
                return true;
            }
            return false;
        }        
        public static function writeAsJsonFile($path,$arr,$options=null){
            $r = new SimpleResult();
            try {
                $a = file_put_contents($path,self::toJsonStringWithOptions($arr,$options));
                $r->setIsOk(true);
                $r->value = $a;
            } catch (\Throwable $th) {
                //throw $th;
                $r->setIsOk(false);
                $r->addError($th->getMessage());
            }
            return $r;
        }
}
?>