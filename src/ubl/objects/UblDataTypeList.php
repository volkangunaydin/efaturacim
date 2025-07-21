<?php

namespace Efaturacim\Util\Ubl\Objects;
class UblDataTypeList{
    public $className = null;
    public $list = array();
    public function __construct($class){
        $this->className = $class;
    }
    public function isEmpty(){
        if(count($this->list)==0){
            return true;
        }
        return false;
    }
    public function add($obj,$key=null,$callback=null){
        if(!is_null($callback) && is_callable($callback)){
            call_user_func_array($callback,array(&$obj));
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
}
?>