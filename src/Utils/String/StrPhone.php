<?php
namespace Efaturacim\Util\Utils\String{

    use Exception;
    use Efaturacim\Util\Utils\SimpleResult;
                
    class StrPhone{
        public static $defCountryCode = "90";
        public static $defCityCode    = "";
        
        public static function isValidCell($str,$options=null,$forceTrCell=false){
            $r = self::getResult($str,null,true);
            return $r->isOK();
        }        
        public static function isValid($str,$options=null,$forceTrCell=false){
            $r = self::getResult($str,null,false);            
            return $r->isOK() && strlen("".@$r->attributes["city"])==3;            
        }
        
        public static function getResult($str,$options=null,$forceTrCell=false){
            $res = new SimpleResult();
            $str = trim("".$str);
            $res->attributes = array("isok"=>false,"in"=>"".$str,"out"=>"","country"=>self::$defCountryCode,"city"=>self::$defCityCode,"code"=>"","line"=>"","org_phone"=>$str,"org_country"=>"","org_city"=>"","org_code"=>"","org_line"=>"","int"=>"","int_val"=>0,"error"=>array());            
            $pos = strpos($str, "/");
            if($pos>0){                
                $res->attributes["int"] = trim("".substr($str, $pos+1));
                $str    = substr($str, 0,$pos);
            }            
            $onlyNumbers = preg_replace("/[^0-9]/", "", "".$str);
            $str = $onlyNumbers;
            if(strlen($onlyNumbers)<7){
                $res->addError("Telefon numarası çok kısa.");
                return $res;
            }else if(strlen($onlyNumbers)>12){                
                $res->addError("Telefon numarası beklenenden daha uzun.[".$onlyNumbers."]");
                return $res;                
            }
            if (substr($str, 0,1)=="0"){ $str = substr($str,1); }
            if (substr($str, 0,1)=="0"){ $str = substr($str,1); }
            $slash = self::findRevPos($str, "/");
            if ($slash>5){
                $arr["org_line"] = $arr["line"] = self::onlyNumberChars(substr($str, $slash+1))+0;
                $str = substr($str, 0,$slash);
            }
            $plusSign = self::findPos($str, "+");
            $bosluk   = self::findPos($str, array(" ","-","."));
            if ($plusSign==0 && $bosluk<4){
                $res->attributes["country"] = self::onlyNumberChars(substr($str, 1,$bosluk))+0;
                $res->attributes["org_country"] = $res->attributes["country"];
                
                $str = trim(substr($str, $bosluk));
            }
            $dash  = self::findRevPos($str, "-");
            
            if ($dash>0 && strlen($arr["line"])==0 ){
                $basi = preg_replace("/[^0-9]/", "", substr($str, 0,$dash) );
                $sonu = preg_replace("/[^0-9]/", "", substr($str, $dash+1) );
                if (strlen($basi)==10){
                    $res->attributes["org_line"] = $res->attributes["line"] = $sonu;
                    $str = $basi;
                }else if (strlen($basi)==7){
                    $res->attributes["org_line"] = $res->attributes["line"] = $sonu;
                    $str = $basi;
                }
            }
            $onlyNumbers = preg_replace("/[^0-9]/", "", $str);
            if (strlen($onlyNumbers)>10){
                $res->attributes["country"] = $res->attributes["org_country"] = substr($onlyNumbers, 0,-10);
                $res->attributes["city"] = $res->attributes["org_city"] = substr($onlyNumbers, -10,3);
                $res->attributes["code"] = $res->attributes["org_code"] = substr($onlyNumbers, -7);
            }else if (strlen($onlyNumbers)==10){
                $res->attributes["city"] = $res->attributes["org_city"] = substr($onlyNumbers, -10,3);
                $res->attributes["code"] = $res->attributes["org_code"] = substr($onlyNumbers, -7);
            }else if (strlen($onlyNumbers)==7){
                $res->attributes["code"] = $res->attributes["org_code"] = substr($onlyNumbers, -7);
            }
            if ($forceTrCell==true && "5"!=@substr("".$res->attributes["city"], 0,1) ){                
                $res->attributes["isok"] = false;
                $res->addError("Cep telefonu operatör kodu 5 ile başlamalıdır. ");
            }      
            if ($forceTrCell==true){
                if(("".$res->attributes["org_country"])=="90" || ("".$res->attributes["org_country"])==""){
                        
                }else{
                    $res->attributes["isok"] = false;
                    $res->addError("Ülke kodu 90 dan faklı olmamalıdır.");
                }                                                    
            }             
            if(strlen("".$onlyNumbers)<10){
                $res->attributes["isok"] = false;
                $res->addError("Cep telefon numarası en az 10 haneli olmalıdır.");
            }else if(strlen("".@$res->attributes["city"])<3){
                $res->attributes["isok"] = false;
                $res->addError("Telefon il ön eki algılanamadı.");
            }
            if(strlen("".@$res->attributes["country"])<=1){
                $res->attributes["isok"] = false;
                $res->addError("Telefon ülke ön eki algılanamadı.");
            }
            //$arr["temp"] = $str." ? ".$onlyNumbers;
            if (!$res->hasError() && strlen("".@$res->attributes["code"])>0){
                $res->setIsOk(true);
                $res->attributes["isok"] = true;
                $res->attributes["out"]  = "+".@$res->attributes["country"]."-".@$res->attributes["city"]."-".@$res->attributes["code"].(strlen(@$res->attributes["line"])>0?"/".@$res->attributes["line"]:"" );
                $res->attributes["orkestra"]  = trim("+".@$res->attributes["country"]." (".@$res->attributes["city"].") ".@substr(@$res->attributes["code"],0,3)." ".@substr(@$res->attributes["code"],3,4).(strlen(@$res->attributes["line"])>0?"/".@$res->attributes["line"]:"" ));
                $res->attributes["cell_sms"]  = "".@$res->attributes["city"].@substr(@$res->attributes["code"],0,7);
                $res->attributes["cell"]      = "0".@$res->attributes["city"].@substr(@$res->attributes["code"],0,7);
                if ($res->attributes["isok"] && strlen($onlyNumbers)>=19){
                    $tmp1 = substr($onlyNumbers, -1*strlen($res->attributes["cell"])+1);
                    $tmp2 = "0".substr($onlyNumbers, -1*strlen($res->attributes["cell"])+1);
                    $tmp3 = str_replace($tmp2, "", $onlyNumbers);
                    $tmp3 = str_replace($tmp1, "", $tmp3);
                }
                $res->value = @$res->attributes["orkestra"];
                $res->attributes["int_val"] = 0 + @$res->attributes["cell_sms"];
            }                
            
            return $res;
        }
        protected  static function onlyNumberChars($str){
            return preg_replace("/[^0-9]/", "", $str);
        }	
        protected static function findRevPos($haystack,$needleOrArray,$offset=null){
            $s = self::findPos(strrev($haystack), $needleOrArray,$offset);
            return $s>=0?(strlen($haystack)-($s+1)):-1;
        }
        protected static function findPos($haystack,$needleOrArray,$offset=null){
            $res = array();
            if (is_array($needleOrArray)){
                foreach ($needleOrArray as $k=>$v){
                    $p = self::findPos($haystack, $v);
                    if ($p>=0){ $res[] = $p; }
                }
            }else if (is_string($needleOrArray) || is_numeric($needleOrArray)){
                $p = strpos("".$haystack, "".$needleOrArray,is_null($offset)?-1:$offset);
                if ($p!==FALSE && is_numeric($p) && $p>=0){
                    return $p;
                }
            }
            return count($res)>0?min($res):-1;
        }	
        public static function isEqual($a,$b,$enableEmpty=true){            
            if($enableEmpty && self::isEmptyString($a) && self::isEmptyString($b)){
                return true;
            }else if (!$enableEmpty && (self::isEmptyString($a) || self::isEmptyString($b))){
                return false;
            }
            $r1 = self::getResult($a);
            $r2 = self::getResult($b);
            if($r1 instanceof SimpleResult && $r2 instanceof SimpleResult && $r1->isOK() && $r2->isOK() && $r1->value===$r2->value){
                return true;
            }
            return false;
        }
        
        protected static function isEmptyString($str){
            return is_null($str) || $str === "" || trim($str) === "";
        }
        
        protected static function __getPhoneNumbersAsArray(&$arr,$phoneNumbers,$depth=0){
            if($depth>100){ return; }
            if(is_array($phoneNumbers)){
                foreach ($phoneNumbers as $v){
                    
                    self::__getPhoneNumbersAsArray($arr, $v,$depth+1);
                    
                }
            }else if (is_string($phoneNumbers) || is_int($phoneNumbers)){
                $tokens = self::tokenize($phoneNumbers,"std");                
                if($tokens && count($tokens)>1){
                    foreach ($tokens as $kk=>$vv){
                        $r = self::getResult("".$vv);
                        if($r->isOK()){
                            $tel =  @$r->attributes["cell"];
                            if(strlen("".$tel)>0 && !in_array($tel, $arr)){
                                $arr[] = $tel;
                            }
                        }
                    }
                }else{
                    $r = self::getResult("".$phoneNumbers);                    
                    if($r->isOK()){
                        $tel =  @$r->attributes["cell"];
                        if(strlen("".$tel)>0 && !in_array($tel, $arr)){
                            $arr[] = $tel;
                        }
                    }
                }                                
            }
        }
        
        protected static function tokenize($str, $type = "std"){
            if($type == "std"){
                return preg_split('/[\s,;|]+/', $str, -1, PREG_SPLIT_NO_EMPTY);
            }
            return array($str);
        }
        
        public static function getPhoneNumbersAsArray($phoneNumbers,$extra=null){
            $arr = array();
            self::__getPhoneNumbersAsArray($arr, $phoneNumbers);            
            if(!is_null($extra)){ self::__getPhoneNumbersAsArray($arr, $extra); }
            return $arr;
        }
        public static function getPhoneNumberAsKey($phoneNumber){
            if(!is_null($phoneNumber) && self::notEmptyString($phoneNumber)){
                $r = self::getResult($phoneNumber);
                if($r->isOK()){ return "".$r->attributes["cell"]; }
            }
            return "";
        }
        
        protected static function notEmptyString($str){
            return !is_null($str) && $str !== "" && trim($str) !== "";
        }
        
        public static function getPhoneNumberAsString($phoneNumber,$type=""){
            if(!is_null($phoneNumber) && self::notEmptyString($phoneNumber)){
                $r = self::getResult($phoneNumber);                
                if($r->isOK()){                    
                    if($type && $type=="link"){
                        return @$r->attributes["cell"];
                    }else if($type && in_array($type,array("sms","cell_sms"))){
                        return @$r->attributes["cell_sms"];
                    }else if($type && in_array($type,array("orkestra","std",""))){
                        return @$r->attributes["orkestra"];
                        
                    }
                    return $r->value;
                }
            }
            return "";
        }
        public static function getPhoneNumberAsHtmlLink($phoneNumber){
            if(!is_null($phoneNumber) && self::notEmptyString($phoneNumber)){
                $r = self::getResult($phoneNumber);
                if($r->isOK()){                    
                    return '<a href="tel:'.$r->attributes["cell"].'" >'.$r->value.'</a>';
                }
            }
            return "";
        }        
    }
}
?>
