<?php
namespace Efaturacim\Util\Ubl\Objects;

use Efaturacim\Util\Utils\String\StrUtil;
use Efaturacim\Util\Ubl\UblDocument;
use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\Options;
use Vulcan\Base\Util\StringUtil\StrUtil as StringUtilStrUtil;

trait UblDataTrait{
    public function loadSmart($loadObject,$type,$debug,&$debugArray){
        if($type && $type==="json"){            
            return $this->loadFromJson($loadObject);
        }else if($type && $type==="array"){            
            $this->loadFromArray($loadObject,0,$debug,false,$debugArray);
        }else if(!is_null($loadObject) && is_string($loadObject)){            
            if(is_array($loadObject)){
                $this->loadFromArray($loadObject,0,$debug,false,$debugArray);
            }else if(StrUtil::isJson($loadObject)){
                $this->loadFromJson($loadObject,false,$debugArray);          
            }else if(StrUtil::isXml($loadObject)){              
                $this->loadFromXml($loadObject,$debug,false,$debugArray);          
            }                        
        }
    }
    public function loadFromJson($jsonString){
        //try {            
            $debugArray = array();
            $arr = json_decode($jsonString,true);            
            if(is_array($arr) && count($arr)>0){    
                //\Vulcan\V::dump($arr);            
                return $this->loadFromArray($arr,0,false,false,$debugArray);
            }
        //} catch (\Throwable $th) {       }
    }
    public function loadFromArray($arr,$depth,$isDebug,$dieOnDebug,&$debugArray){
        if($depth>50){  return; }        
        if(is_null($debugArray)){
            $debugArray = array("class"=>get_class($this),"counter"=>0,"errors"=>array(),"log"=>array());        
        }
        if($isDebug){
            if(!key_exists("class",$debugArray)){
                $debugArray["class"]    = get_class($this);
            }            
            if(!key_exists("counter",$debugArray)){
                $debugArray["counter"] = 0;      
            }
            if(!key_exists("errors",$debugArray)){
                $debugArray["errors"] = array();
            }
            if(!key_exists("log",$debugArray)){
                $debugArray["log"] = array();
            }
            $debugArray["counter"]++;
        }
        if(!is_null($arr) && is_array($arr)){   
            if($isDebug){
                $debugArray["log"][] = "Array found for loadFromArray => Search ERROR for errors";
            }       
            foreach($arr as $k=>$v){           
                $paramArray = array();     
                if(!is_null($v)){                      
                    if($k=="@attributes" && $this instanceof UblDataType && is_array($v)){                                                
                        if(!is_array($this->attributes)){
                            $this->attributes = array();
                        }                
                        foreach($v as $kk=>$vv){                                                        
                            if($isDebug){                                
                                $debugArray["log"][] = "Setting attribute named => ".$kk." => ".print_r($vv,true);
                            }
                            if($kk=="@value"){
                                if($isDebug){
                                    $debugArray["log"][] = "Setting textContent => ".$kk." => ".print_r($vv,true);
                                }
                                 $this->setTextContent($vv);
                            }else{
                                $this->attributes[$kk] = $vv; 
                            }
                        }                        
                        continue;
                    }else if($k=="@value"){                                                
                        if($this instanceof UblDataType && is_scalar($v)){
                            if($isDebug){
                                $debugArray["log"][] = "Setting textContent from @value => ".$k." => ".print_r($v,true);
                            }
                            $this->setTextContent($v);
                            continue;                    
                        }
                    }                    
                    $k_safe     = StrUtil::toLowerEng(substr($k,0,1)).substr($k,1);
                    $k_up       = StrUtil::toUpperEng(substr($k,0,1)).substr($k,1);
                    $k_lower    = StrUtil::toLowerEng($k);
                    $k_upper    = StrUtil::toUpperEng($k);
                    
                    if(!in_array($k_safe,$paramArray)){$paramArray[] = $k_safe;}                    
                    if(!in_array($k_up,$paramArray)){$paramArray[] = $k_up;}
                    if(!in_array($k,$paramArray)){$paramArray[] = $k;}                    
                    if(!in_array($k_lower,$paramArray)){$paramArray[] = $k_lower;}
                    if(!in_array($k_upper,$paramArray)){$paramArray[] = $k_upper;}                    
                    $isFound = false;                    
                    foreach($paramArray as $paramName){
                        $key = null;
                        if($isFound===false && method_exists($this,"getPropertyAlias")){                            
                            $key = $this->getPropertyAlias($k,$v);
                            if(!is_null($key) && strlen("".$key)>0){
                                if($isDebug){
                                     $debugArray["log"][] = "getPropertyAlias => ".$k." => ".$key;
                                }
                                if(is_array($v)){
                                    $this->loadFromArray( array("".$key => $v),$depth+1,$isDebug,$dieOnDebug,$debugArray);
                                }
                            }
                        }
                        if($isFound===false && method_exists($this,"setPropertyFromOptions")){
                            $isPropertyOptionIsOK = false;
                            if(!is_null($key) && strlen("".$key)>0){
                                $isPropertyOptionIsOK = $this->setPropertyFromOptions($key,$v,null);
                            }else{
                                $isPropertyOptionIsOK = $this->setPropertyFromOptions($k,$v,null);
                            }                        
                            if($isPropertyOptionIsOK===true){
                                $isFound = true;
                            }
                        }
                        // OTOMATIK ASSIGN ISLENMLERI
                        if($isFound===false && property_exists($this,$paramName)){
                            if($paramName=="lineExtensionAmount"){
                                //\Vulcan\V::dump($arr);
                                //$isDebug = true;
                            }
                            if($isDebug){
                                $valStr = print_r($v,true);
                                if(strlen($valStr)>200){ $valStr = mb_substr($valStr,0,200)."..."; }                                
                                $debugArray["log"][] = "Field exists : ".$paramName." ==> ".$valStr;
                            }
                            $isScalar = is_scalar($v) && (is_null($this->$paramName) || !is_object($this->$paramName) );                                                        
                            if($isScalar){
                                if (is_string($v)) {
                                    $lowerVal = strtolower(trim($v));
                                    if ($lowerVal === 'true') { $v = true; }
                                    else if ($lowerVal === 'false') { $v = false; }
                                }
                                if($isDebug){
                                    $debugArray["log"][] = "Setting scalar value for ".$paramName." := ".$v;
                                }                                                              
                                $this->$paramName = $v;                                                                
                            }else if (is_scalar($v) && is_object($this->$paramName) && $this->$paramName instanceof UblDataType && strlen("".$v)>0){                                
                                $isScalar = true;
                                if($isDebug){
                                    $debugArray["log"][] = "Setting textContent value for ".$paramName." := ".$v;
                                }              
                                if(method_exists($this,"setPropertyFromOptions") && $this->setPropertyFromOptions($paramName,$v,null)){
                                    if($isDebug){
                                        $debugArray["log"][] = "setPropertyFromOptions value for ".$paramName." := ".$v;
                                    }              
                                }else{
                                    $this->$paramName->setTextContent($v);
                                }                                                      
                            }
                            if (!$isScalar && is_array($v) && !is_null($this->$paramName) && ( is_object($this->$paramName) && method_exists($this->$paramName,"loadFromArray")  )){                                                                                                                                                            
                                 if($isDebug){  
                                    $valStr = print_r($v,true);
                                    if(strlen($valStr)>200){ $valStr = mb_substr($valStr,0,200)."..."; }
                                    $debugArray["log"][] = "loadFromArray for ".$paramName." @".get_class($this->$paramName)."  := ".$valStr."";
                                }               
                                $rr = $this->$paramName->loadFromArray($v,$depth+1,$isDebug,false,$debugArray);                                                            
                                
                                if($isDebug && is_array($rr) && key_exists("log",$rr)){
                                    $sep = "=============  ".$paramName."  ===============";
                                    $debugArray["log"][] = $sep;
                                    foreach($rr["log"] as $logStr){
                                        $debugArray["log"][] = $logStr;    
                                    }
                                    $debugArray["log"][] = $sep;
                                }
                            }
                            $isFound = true;
                            break;
                        }                        
                    }
                    if($isFound===false && $isDebug){
                        if(in_array("".$paramName,array("@ATTRIBUTES","UBLVERSIONID"))){
                                                        
                        }else{
                            $debugArray["errors"][] = "Field not found : ".$paramName;
                        }
                        
                    }
                }
            }            
        }else if (is_object($arr) && method_exists($arr,"toArrayOrObject")){
            $arr2 = $arr->toArrayOrObject();
            if(is_array($arr2)){
                return $this->loadFromArray($arr2,$depth+1,$isDebug,$dieOnDebug,$debugArray);
            }            
        }
        if($isDebug){
            if(method_exists($this,"showAsXml")){
                $debugArray["xml"] = $this->getAsXmlString(null);            
            }            
            if($dieOnDebug){
                \Vulcan\V::dump($debugArray);
            }else{
                //return $debugArray;
            }      
        }
        $this->onAfterLoadComplete($arr,$debugArray);
    }
    public function onAfterLoadComplete($arr,$debugArray){
    }
}
?>