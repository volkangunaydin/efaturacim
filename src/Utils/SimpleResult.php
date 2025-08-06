<?php
namespace Efaturacim\Util\Utils;

use Efaturacim\Util\Utils\Array\ArrayUtil;

class SimpleResult{
        public $__isok     = false;
        public $attributes = array();
        public $events     = array();
        public $messages   = array();
        public $value      = null;
        public $list       = array();
        public $resultCode = 0;
        public function isOK(){ return $this->__isok; }
        public function setIsOk($bool=true){ $this->__isok = $bool; return $this; }
        public function setValue($val=null){ $this->value = $val; return $this; }
        public function setAttribute($key,$val){ $this->attributes[$key] = $val; return $this; }
        public  function getAttribute($name,$defVal=null,$type=null){ $key=null; return ArrayUtil::arrayGetKey($this->attributes,$key,$name,$defVal); }
        public function getMessages($sep="<hr/>",$types=null){        
            $s = "";
            foreach ($this->messages as $v){ if(is_null($types) || in_array(@$v["type"], $types)){ $s .= (strlen($s)>0 ? $sep : "").@$v["text"];}}          
            return $s;
        }    
        public function addError($str){ return $this->addMessage($str,"error"); }
        public function addSuccess($str){ return $this->addMessage($str,"success"); }
        public function addWarn($str){ return $this->addMessage($str,"warning"); }
        public function addMessage($str,$type,$key=null){
            if(is_null($key)){ $this->messages[] = array("text"=>$str,"type"=>$type,"t"=>date("Y-m-d H:i:s")); }else{ $this->messages[$key] = array("text"=>$str,"type"=>$type,"t"=>date("Y-m-d H:i:s")); }        
            return $this; 
        }
        public function hasError(){
            return strlen($this->getMessages("",array("danger","error"))) > 0;
        }
        public function hasWarning(){
            return strlen($this->getMessages("",array("warn","warning"))) > 0;
        }
        public function hasSuccess(){
            return strlen($this->getMessages("",array("success","succ"))) > 0;
        }    
        public  function merge($res2,$checkIfOk=true,$mergeMessages=true,$mergeAttr=true,$mergeList=true,$mergeValue=true){
            if($res2 instanceof SimpleResult){
                if(!$this->isOK() && $checkIfOk){ $this->__isok = false; }
                if($mergeValue){ $this->value = $res2->value;}
                if($mergeMessages){ foreach ($res2->messages as $k=>$v){ $this->messages[] = $v; } }
                if($mergeAttr){ foreach ($res2->attributes as $k=>$v){ $this->attributes[$k] = $v; } }
                if($mergeList){ foreach ($res2->list as $k=>$v){ $this->list[$k] = $v; } }            
            }
        }            
}
?>