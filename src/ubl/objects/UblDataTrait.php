<?php
namespace Efaturacim\Util\Ubl\Objects;

use Efaturacim\Util\StrUtil;
use Efaturacim\Util\Ubl\UblDocument;

trait UblDataTrait{
    public function loadSmart($loadObject,$type=null,$debug=false){
        if($type && $type==="json"){            
            return $this->loadFromJson($loadObject);
        }else if($type && $type==="array"){            
            $this->loadFromArray($loadObject,0,$debug);
        }else if(!is_null($loadObject) && is_string($loadObject)){            
            if(is_array($loadObject)){
                $this->loadFromArray($loadObject,0,$debug);
            }else if(StrUtil::isJson($loadObject)){
                $this->loadFromJson($loadObject);          
            }else if(StrUtil::isXml($loadObject)){              
                $this->loadFromXml($loadObject,$debug);          
            }                        
        }
    }
    public function loadFromJson($jsonString){
        //try {            
            $arr = json_decode($jsonString,true);            
            if(is_array($arr) && count($arr)>0){    
                //\Vulcan\V::dump($arr);            
                return $this->loadFromArray($arr);
            }
        //} catch (\Throwable $th) {       }
    }
    public function loadFromArray($arr,$depth=0,$isDebug=false,$dieOnDebug=true){
        if($depth>10){  return; }        
        $debugArray = array("log"=>array());        
        if(!is_null($arr) && is_array($arr)){            
            foreach($arr as $k=>$v){                
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
                    }else if($k=="@value" && $this instanceof UblDataType && is_scalar($v) ){
                        if($isDebug){
                            $debugArray["log"][] = "Setting textContent from @value => ".$k." => ".print_r($v,true);
                        }
                        $this->setTextContent($v);
                        continue;
                    }
                    
                    $k_safe     = strtolower(substr($k,0,1)).substr($k,1);
                    $k_up       = strtoupper(substr($k,0,1)).substr($k,1);
                    $k_lower    = strtolower($k);
                    $k_upper    = strtoupper($k);
                    $paramArray = array($k);
                    if(!in_array($k_safe,$paramArray)){$paramArray[] = $k_safe;}
                    if(!in_array($k_up,$paramArray)){$paramArray[] = $k_up;}
                    if(!in_array($k_lower,$paramArray)){$paramArray[] = $k_lower;}
                    if(!in_array($k_upper,$paramArray)){$paramArray[] = $k_upper;}                    
                    $isFound = false;                    
                    foreach($paramArray as $paramName){
                        if(property_exists($this,$paramName)){
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
                                    $debugArray["log"][] = "loadFromArray for ".$paramName." := ".$valStr;
                                }               
                                $rr = $this->$paramName->loadFromArray($v,$depth+1,$isDebug,false);                                                            
                                
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
                    $key = null;
                    if($isFound===false && method_exists($this,"getPropertyAlias")){
                        $key = $this->getPropertyAlias($k,$v);
                        if(!is_null($key) && strlen("".$key)>0){
                            if(is_array($v)){
                                $this->loadFromArray( array("".$key => $v),$depth+1);
                            }
                        }
                    }
                    if($isFound===false && method_exists($this,"setPropertyFromOptions")){
                        if(!is_null($key) && strlen("".$key)>0){
                            $this->setPropertyFromOptions($key,$v,null);
                        }else{
                            $this->setPropertyFromOptions($k,$v,null);
                        }                        
                    }
                }
            }            
        }else if (is_object($arr) && method_exists($arr,"toArrayOrObject")){
            $arr2 = $arr->toArrayOrObject();
            if(is_array($arr2)){
                return $this->loadFromArray($arr2);
            }            
        }
        if($isDebug){
            $debugArray["class"]    = get_class($this);
            $debugArray["org_data"] = $arr;      
            if(method_exists($this,"showAsXml")){
                $debugArray["xml"] = $this->getAsXmlString(null);            
            }            
            if($dieOnDebug){
                \Vulcan\V::dump($debugArray);
            }else{
                return $debugArray;
            }      
        }
        $this->onAfterLoadComplete($arr,$debugArray);
    }
    public function onAfterLoadComplete($arr,$debugArray){
    }
}
?>