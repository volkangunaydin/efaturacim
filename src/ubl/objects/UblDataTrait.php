<?php
namespace Efaturacim\Util\Ubl\Objects;

use Efaturacim\Util\StrUtil;
use Efaturacim\Util\Ubl\UblDocument;

trait UblDataTrait{
    public function loadSmart($loadObject,$type=null){
        if($type && $type==="json"){            
            return $this->loadFromJson($loadObject);
        }else if($type && $type==="array"){            
            $this->loadFromArray($loadObject);
        }else{
            if(is_array($loadObject)){
                $this->loadFromArray($loadObject);
            }else if(StrUtil::isJson($loadObject)){
                $this->loadFromJson($loadObject);            
            }            
        }
    }
    public function loadFromJson($jsonString){
        //try {            
            $arr = json_decode($jsonString,true);            
            if(is_array($arr) && count($arr)>0){                
                return $this->loadFromArray($arr);
            }
        //} catch (\Throwable $th) {       }
    }
    public function loadFromArray($arr,$depth=0){
        if($depth>10){  return; }
        if(!is_null($arr) && is_array($arr)){
            foreach($arr as $k=>$v){
                if(!is_null($v)){
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
                            if(is_scalar($v) && (is_null($this->$paramName) || !is_object($this->$paramName) )){
                                $this->$paramName = $v;                                
                            }else if (is_array($v) && !is_null($this->$paramName) && ($this->$paramName instanceof UblDataType)){                                                                
                                $this->$paramName->loadFromOptions($v);                                                            
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
    }
}
?>