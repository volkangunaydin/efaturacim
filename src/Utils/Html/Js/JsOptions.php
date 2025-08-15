<?php
namespace Efaturacim\Util\Utils\Html\Js;

use Efaturacim\Util\Utils\Array\AssocArray;
use Efaturacim\Util\Utils\Json\JsonUtil;

class JsOptions{
    public $printPretty = true;
    protected $options = [];
    public function __construct($options=null){
        if(!is_null($options) && is_array($options)){
            $this->options = $options;
        }
    }
    public function getOptions(){
        return $this->options;
    }
    public function setOption($key,$value){
        $this->options[$key] = $value;
        return $this;
    }
    public function getOption($key,$defVal=null,$type=null){
        return AssocArray::getVal($this->options,$key,$defVal,$type);
    }
    public function toJson(){
        return JsonUtil::toJsonStringWithOptions((object)$this->options,array('pretty'=>$this->printPretty,'js_function'=>true,'jquery_selector'=>true));
    }
}