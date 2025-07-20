<?php
namespace Efaturacim\Util\Ubl\Objects;
trait UblDataTrait{
    public function loadSmart($loadObject,$type=null){
        if($type && $type==="json"){            
            return $this->loadFromJson($loadObject);
        }
    }
    public function loadFromJson($jsonString){
        try {
            
            $arr = json_decode($jsonString,true);            
            if(is_array($arr) && count($arr)>0){                
                return $this->loadFromArray($arr);
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function loadFromArray($arr){
        if(!is_null($arr) && is_array($arr)){
            foreach($arr as $k=>$v){
                if(!is_null($v)){
                    $k_safe = strtolower(substr($k,0,1)).substr($k,1);
                    $k_up   = strtoupper(substr($k,0,1)).substr($k,1);
                    $k_lower = strtolower($k);
                    $k_upper = strtoupper($k);
                    $paramArray = array($k);
                    if(!in_array($k_safe,$paramArray)){$paramArray[] = $k_safe;}
                    if(!in_array($k_up,$paramArray)){$paramArray[] = $k_up;}
                    if(!in_array($k_lower,$paramArray)){$paramArray[] = $k_lower;}
                    if(!in_array($k_upper,$paramArray)){$paramArray[] = $k_upper;}
                    
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