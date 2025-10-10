<?php
namespace Efaturacim\Util\Utils;
class CastUtil{
        public static  $DATA_STRING    = "string";
        public static  $DATA_INT       = "int";
        public static  $DATA_BOOL      = "bool";
        public static  $DATA_NUMBER    = "number";
        public static  $DATA_MONEY     = "money";
        public static  $DATA_ARRAY     = "array";
        public static  $DATA_DATE      = "date";
        public static  $DATA_DATETIME  = "datetime";
        public static  $DATA_TIME      = "time";
        public static  $DATA_REFERENCE = "reference";
        public static  $DATA_NESTED    = "nested";
        public static  $DATA_OBJECT    = "object";        
        public static  $DATA_TIMESTAMP    = "timestamp";        
        public static  $DATA_ORIGINAL    = "org";    
        public static  $DATA_PARAMS     = "params";    
        public static function getAs($value,$defVal=null,$type=null,$typeOptions=null){            
            $type = "".(!is_null($type)?strtolower($type):"");
            if(is_null($type) || $type=="" || $type==self::$DATA_STRING){
                if(is_null($value)){ return ""; }
                if(is_scalar($value)){
                    if($typeOptions && strlen("".$typeOptions)>0){ return CastUtil::smart($value,$typeOptions); }
                    return "".$value; 
                }                
            }else if($type==self::$DATA_ORIGINAL){
                return !is_null($value) ? $value : $defVal;
            }else if($type==self::$DATA_INT){
                return intval( is_numeric($value) ? $value : ( $defVal ? $defVal : 0 ));
            }else if($type==self::$DATA_BOOL){
                if(is_null($value) || $value===""){ return $defVal && true; }
                if(is_bool($value)){ return $value; }
                if(is_numeric($value)){ return $value>0 ? true : false; }
                $valAsVar = strtolower($value);                              
                if(in_array($valAsVar, array("true","evet","yes","e","y","on"))){ return true; }
                if(in_array($valAsVar, array("false","hayir","hayır","no","h","n","off"))){ return false; }
                return $defVal;
            }else if($type==self::$DATA_NUMBER){         
                if(!is_null(value: $typeOptions) && is_numeric($value) && is_int($typeOptions)){
                    return round($value,$typeOptions);
                }       
                return is_numeric($value) ? floatval($value) :  call_user_func_array(array("\\Vulcan\\Base\\Util\\StringUtil\\StringUtilForNumbers","smartCastToFloat"),array($value,$defVal,$typeOptions));
            }else if($type==self::$DATA_DATE || $type==self::$DATA_DATETIME || $type==self::$DATA_TIME){
                \Vulcan\V::dump("IMPLEMENT THIS");
            }else if($type==self::$DATA_REFERENCE){
                return (is_numeric($value) && $value>0) ? intval($value) : null;            
            }else if($type==self::$DATA_ARRAY){
                return (is_array($value)) ? $value : (is_array($defVal) ? $defVal: null);            
            }else if($type==self::$DATA_DATE_OBJECT){
                return CastUtil::getAs($value,$defVal,$type,$typeOptions);
            }else{                
                \Vulcan\V::dump("IMPLEMENT THIS");
            }
            return $defVal;
        }    
        public static function asInt($str){
            return self::getAs($str,0,self::$DATA_INT);
        }
        public static function asBool($str){
            return self::getAs($str,false,self::$DATA_BOOL);
        }        
}

?>