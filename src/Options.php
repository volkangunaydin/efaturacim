<?php
namespace Efaturacim\Util;
class Options{    
        /** @var Array */
        public $params = array();
        public function __construct($params=null,$defVals=null){            
            if(is_array($params)){$this->params = $params;}else if($params){ $this->merge($params); }            
            if(!is_null($defVals)){ $this->merge($defVals,false); }
        }        
        public function merge($arrOrObject,$override=true){
            if(is_null($arrOrObject) || (is_array($arrOrObject) && count($arrOrObject)==0) ){ return $this; }
            if(is_array($arrOrObject)){
                foreach($arrOrObject as $k=>$v){ 
                    if($override || !key_exists($k,$this->params)){
                        $this->params[$k] = $v; 
                    }                    
                }
            }else if (is_object($arrOrObject) && $arrOrObject instanceof Options){
                foreach($arrOrObject->params as $k=>$v){ 
                    if($override || !key_exists($k,$this->params)){
                        $this->params[$k] = $v; 
                    }                    
                }
            }else if (is_object($arrOrObject) && property_exists($arrOrObject, "params")){                
                $this->merge($arrOrObject->params,$override);
            }else if (is_object($arrOrObject) && property_exists($arrOrObject, "param")){
                $this->merge($arrOrObject->param,$override);
            }
            return $this;
        }
        public function hasValue($nameOrNames){
            return V::arrayHasValue($this->params,$nameOrNames);
        }
        public function hasKey($nameOrNames){
            if(is_array($nameOrNames)){
                foreach ($nameOrNames as $name){ if(key_exists($name,$this->params)){ return true; } }
            }else if(is_scalar($nameOrNames) && key_exists($nameOrNames, $this->params)){
                return true;
            }
            return false;
        }
        public function setValue($name,$val,$setOnlyIfNotExists=false,$append=false,$appendChar=" "){
            if($setOnlyIfNotExists && key_exists($name,$this->params)){
                // skip
            }else{
                if($append && key_exists($name, $this->params)){
                    $this->params[$name] = @$this->params[$name].$appendChar.$val;
                }else{
                    $this->params[$name] =$val;
                }
                
            }            
            return $this;
        }  
        public function getAs($nameOrNames,$defVal,$type=null,$typeOptions=null){
            $rightKey = null;
            $val      = ArrayUtil::arrayGetKey($this->params,$rightKey,$nameOrNames,$defVal);
            if(!is_null($type)){
                return CastUtil::getAs($val,$defVal,$type,$typeOptions);
            }
            return $val;
        }
        /** @return Options */
        public function initFrom($params=null,$defVals=null){
            $a = new Options();
            $a->merge($defVals)->merge($params,true);
            return $a;
        }         
        public function copy(){ return $this->cloneOptions(); }
        public function copyWith($params=null,$defVals=null){
            return $this->cloneOptions($params,$defVals);
        }   
        /** @return Options */
        public function cloneOptions($params=null,$defVals=null){
            $a = new Options($this->params);
            $a->merge($defVals,false)->merge($params,true);
            return $a;
        } 
        public static function newParams(&$op,$defVals=null,$newVals=null){
            $newOptions = new Options($op,$defVals);
            if(!is_null($newVals)){ $newOptions->merge($newVals,true); }
            return $newOptions;
        }
        public static function ensureParam(&$op,$defVals=null){
            if(is_null($op) || !($op instanceof Options)){
                $op = new Options($op,$defVals);
            }else if (!is_null($defVals)){ $op->merge($defVals,false); }
            return true;
        }
        public function getAsBool($name,$defVal=false){
            return self::getAs($name,$defVal,CastUtil::$DATA_BOOL);
        }
        public function getAsString($name,$defVal=false){
            return self::getAs($name,$defVal,CastUtil::$DATA_STRING);
        }        
    }  
?>