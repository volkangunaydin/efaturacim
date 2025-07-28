<?php

namespace Efaturacim\Util\Ubl\Objects;

use DOMDocument;
use DOMNodeList;
class UblDataTypeList{
    public $className = null;
    public $list = array();
    public function __construct($class){
        $this->className = $class;
        $this->initMe();
    }
    public function initMe(){
        
    }
    public function isEmpty(){
        if(count($this->list)==0){
            return true;
        }
        return false;
    }
    public function add($obj,$key=null,$callback=null,$context=null){
        if(!is_null($callback) && is_callable($callback)){
            call_user_func_array($callback,array(&$obj));
        }
        if($obj && method_exists($obj,"onBeforeAdd")){
            call_user_func_array(array($obj,"onBeforeAdd"),array($context));
        }
        if(is_null($key)){
            $this->list[] = $obj;
        }else{
            $this->list[$key] = $obj;
        }        
    }
    public function getCount(){
        return count($this->list);
    }
    public function toArrayOrObject(){
        if($this->getCount()>0){
            $arr = array();
            foreach($this->list as $item){
                if($item instanceof UblDataType && $item->isEmpty()===false){
                    $arr[] = $item->toArrayOrObject();
                }
            }
            return $arr;
        }
        return array();
    }
    public function loadFromArray($arr,$depth=0){
        if($depth>10){  return; }
        if(!is_null($arr) && is_array($arr)){   
            if(key_exists(0,$arr)){
                $i=0;
                foreach($arr as $k=>$v){
                    $i++;
                    if($i==1 && is_scalar($v)){                    
                        break;
                    }                
                    if(!is_null($v) && is_array($v) && count($v)>0 && strlen("".$this->className)>0 && class_exists($this->className,true)  ){                    
                        $obj = new $this->className();
                        $obj->loadFromArray($v,$depth+1);
                        $this->add($obj);
                    }
                }
            }else if(count($arr)>0){
                $obj = new $this->className();
                $obj->loadFromArray($arr,$depth+1);
                $this->add($obj);                
            }         

        }
    }
    public function toDOMElement(DOMDocument $doc){
        $fragment = $doc->createDocumentFragment();
        foreach($this->list as $item){
            if($item instanceof UblDataType){       
                    $childElement = $item->toDOMElement($doc);
                    if($childElement){
                        $fragment->appendChild($childElement);
                    }
            }
        }
        return $fragment;
    }
}
?>