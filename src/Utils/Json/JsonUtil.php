<?php
namespace Efaturacim\Util\Utils\Json;

use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\String\StrUtil;

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
}
?>