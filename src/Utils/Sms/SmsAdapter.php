<?php
namespace Efaturacim\Util\Utils\Sms;

use Efaturacim\Util\Utils\Array\ArrayUtil;
use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\Laravel\LV;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StringSplitter;
use Efaturacim\Util\Utils\String\StrPhone;
use Efaturacim\Util\Utils\String\StrUtil;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class SmsAdapter{
    protected $userName = "";
    protected $userPass = "";
    protected $company  = "";
    protected $originator = "";
    protected $optLargeMessagesEnabled = false;
    protected $optLargeMessageLength   = 160;        
    protected $urlHttp  = "";
    protected $urlHttps = "";
    protected $urlPost  = "";
    protected $urlXml   = "";
    protected $options  = null;      
    protected    $optionTextEscapeType = "upper";
    protected    $optionEnableTurkishChars = false;
    protected    $optionSplitMessages = false;
    protected    $adapterType = "";
    protected    $forcedSendValue = null;
    public function __construct($key=null,$options=null){
        if(is_null($this->options)){ $this->options = new Options($options); }            
        $this->initMe();
        $this->initFromKey($key);                        
        $user = $this->options->getAsString(array("user","username"));                        
        if(StrUtil::notEmpty($user)){
            $this->userName   = $this->options->getAsString(array("user","username"));
            $this->userPass   = $this->options->getAsString(array("pass","userpass"));
            $this->company    = $this->options->getAsString(array("company","originator"));
            $this->originator = $this->options->getAsString(array("originator","originator"));
        }                        
    }
    protected function initMe(){
        
    }
    public function hasInitData(){
        if( StrUtil::notEmpty($this->adapterType) && StrUtil::notEmpty($this->originator) && StrUtil::notEmpty($this->userName) ){
            return true; 
        }
        return false;
    }
    public function initFromKey($key){
        if(StrUtil::isEmpty($key)){ $key = "default"; }
        $p = null;
        if($this->options instanceof Options && count($this->options->params)>0 && $this->options->hasValue("user")){
            $p = $this->options;
        }
        if((is_null($p) || ArrayUtil::isEmpty($p)) && LV::isLaravel()){
            $p = \Illuminate\Support\Facades\Config::get('sms.'.$key);                
            if(ArrayUtil::isEmpty($p)){
                $p = null;
            }
        }
        if(is_null($p) || ArrayUtil::isEmpty($p)){
            if(class_exists("\Vulcan\V")){
                $p = \Vulcan\V::env();
            }            
        }                        
        $arrData = array();
        if(is_null($p) && $p instanceof Options){
            if($p->hasValue("sms:".$key)){
                $arrData = $p->getAsArray("sms:".$key);
            }else if(($key=="default" || $key=="")&& $p->hasValue("sms:")){
                $arrData = $p->getAsArray("sms:");
            }else if(($key=="default" || $key=="") && $p->hasValue("sms")){
                $arrData = $p->getAsArray("sms");
            }                
        }
        if(is_array($arrData) && count($arrData)>0 && key_exists("user",$arrData)){
            $this->userName = "".@$arrData["user"];
            $this->userPass = "".@$arrData["pass"];
            $this->company = "".@$arrData["company"];
            $this->originator = "".@$arrData["originator"];
        }            
    }
    public function setEscapeTextTypeNone(){
        $this->optionTextEscapeType = "none";
        return $this;
    }
    protected static function escapeSMSText($str,$convertToUpper=true,$escapeType=null){            
        if(is_bool($convertToUpper) && $convertToUpper && is_null($escapeType)){
            $escapeType = "upper";
        }
        if($escapeType && $escapeType=="std"){
            return StrUtil::removeTurkishChars($str);
        }else if($escapeType && $escapeType=="none"){
            return $str;
        }else{
            return StrUtil::toUpperEng($str);
        }            
    }
    protected function postSimpleXml($prmPostAddress,$prmSendData,$headers=null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$prmPostAddress);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $prmSendData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if($headers && is_array($headers)){
            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
        }
        $result = @curl_exec($ch);
        //\Vulcan\V::dump(array("res"=>$result,"info"=>curl_getinfo($ch),"xml"=>$prmSendData,"url"=>$prmPostAddress));
        return $result;
    }	

    /**
     * 
     * @return array         
     */
    public function sendMultipleSms($message,$phoneNumbers){
        $resArray = array();
        $phoneNumbersAsArray = StrPhone::getPhoneNumbersAsArray($phoneNumbers);
        foreach ($phoneNumbersAsArray as $k=>$v){
            $resArray[$v] = $this->send($message, $v);
        }            
        return $resArray;
    }
    /**
     * @return VResult
     */
    public function send($message,$phoneNumbers){
        $res = new SimpleResult();        
        if(!is_null($this->forcedSendValue) && is_bool($this->forcedSendValue)){
            $res->setIsOk($this->forcedSendValue);
            $res->value = 1;
            return $res;
        }
        $res->setAttribute("msg", $message);
        $res->setAttribute("org_phone", $phoneNumbers);
        $phoneNumbersAsarray = StrPhone::getPhoneNumbersAsArray($phoneNumbers);
        //dd($message,$phoneNumbersAsarray,$phoneNumbers);
        if($this->optionTextEscapeType=="none"){
            $this->optionEnableTurkishChars = true;
        }            
        if(StrUtil::notEmpty($message)){
            if($phoneNumbersAsarray && is_array($phoneNumbersAsarray) && count($phoneNumbersAsarray)>0){
                if(!$this->optionEnableTurkishChars){
                    $message = StrUtil::toEng($message);
                }
                if($this->optionTextEscapeType=="upper"){
                    $message = strtoupper($message);
                }                    
                foreach ($phoneNumbersAsarray as $phoneNumber){       
                    //\Vulcan\V::dump( array($phoneNumbersAsarray,$message,strlen($message)));
                    if($this->optionSplitMessages && strlen($message)>=$this->optLargeMessageLength){
                        $messages = StringSplitter::splitWithWidth($message,$this->optLargeMessageLength,true,true);
                        $r = new SimpleResult();
                        foreach ($messages as $k=>$mesaj){
                            if($k==0){
                                $r = $this->__sendSingleSms($mesaj,$phoneNumber);
                            }else{
                                $r = $this->__sendSingleSms($mesaj,$phoneNumber);
                            }                                                        
                            if($r->isOK()){
                                $res->merge($r,false,true,true,false,false);
                                $res->addSuccess("SMS gönderildi.[ ".$phoneNumber." ]");
                                $res->setIsOk(true);
                                $res->value = $r->value;                                    
                            }else{
                                $res->merge($r);                                    
                            }
                            return $res;
                        }                                                                               
                    }else{
                        $r = $this->__sendSingleSms($message,$phoneNumber);
                    }                        
                    if($r->isOK()){
                        $res->merge($r,false,true,true,false,false);
                        $res->addSuccess("SMS gönderildi.[ ".$phoneNumber." ]");
                        $res->setIsOk(true);
                        $res->value = $r->value;
                    }else{
                        $res->merge($r);
                    }
                }
            }else{
                $res->addError("Lütfen SMS için telefon numarası belirtiniz.");
            }
        }else{
            $res->addError("Lütfen SMS için mesaj belirtiniz.");
        }
        return $res;
    }
    public function isOK(){
        if(StrUtil::notEmpty($this->userName) && StrUtil::notEmpty($this->userPass)){
            return true;
        }
        return false;
    } 
    public function getCreditCount(){
        return null;
    }
    /**
     * @return SimpleResult
    */
    protected function __sendSingleSms($message,$phoneNumber){
        return new SimpleResult();
    }
    /**
     * 
     * @return SmsAdapter
    */
    public static function newAdapter($key=null,$ref=null,$conf=null){                    
        if(is_null($conf) || ArrayUtil::isEmpty($conf)){
            if(LV::isLaravel()){            
                if(is_null($key)){ $key = "default"; }
                $conf = \Illuminate\Support\Facades\Config::get('sms.'.$key);                
                if(is_string($conf) && StrUtil::notEmpty($conf)){
                    $conf = \Illuminate\Support\Facades\Config::get('sms.'.$conf);
                }
            }    
        }        
        if(ArrayUtil::notEmpty($conf) && key_exists("adapter",$conf)){
            $type = StrUtil::toLowerEng(@$conf["adapter"]);
            if($type=="netgsm"){
                return new NetGsmSms(null,$conf);
            }else if($type=="ankaratoplusms"){
                return new AnkaraSms(null,$conf);
            }else if(in_array("".$type,array("+","positive"))){
                return new SmsAdapterForPositiveDebug();
            }else if(in_array("".$type,array("-","negative"))){
                return new SmsAdapterForNegativeDebug();
            }            
        }
        return new SmsAdapterNotFound();    
    }   
}

?>