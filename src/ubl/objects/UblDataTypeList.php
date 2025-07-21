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
    public function add($obj,$key=null){
        if(is_null($key)){
            $this->list[] = $obj;
        }else{
            $this->list[$key] = $obj;
        }
    }
}
?>