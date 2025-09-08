<?php
namespace Efaturacim\Util\EFaturacim;

use Efaturacim\Util\Utils\CookieUtil;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\RestApiClient;
use Efaturacim\Util\Utils\SecurityUtil;
use Vulcan\Base\Util\Session\SessionUtil;

class EFaturacimRestApiClient{
    public static  $DEFAULT_OPTIONS  = array();
    public static  $BEARERS          = array();
    public static  $BASE_API_URL     = "https://eservis.orkestra.com.tr/";
    
    public static function setDefaultUserNamePassword($customer,$user,$pass,$isTest=null){
        self::$DEFAULT_OPTIONS["customer"] = $customer;
        self::$DEFAULT_OPTIONS["user"] = $user;
        self::$DEFAULT_OPTIONS["pass"] = $pass;
        if(!is_null($isTest) && is_string($isTest) && strlen("".$isTest)){
            self::$BASE_API_URL = "".$isTest;
        }else if(is_bool($isTest) && $isTest===true){
            self::$BASE_API_URL = "https://eservistest.orkestra.com.tr/";
        }
    }
    
    public static function getDefaultBearerFromSession(){
        self::setDefaultBearer(SessionUtil::getValue("efaturacim_bearer",null));
    }
    public static function setDefaultBearerFromSession(){
        $bearer = self::getBearerKey(null,null,null);
        if(strlen("".$bearer)>0){
            SessionUtil::setValue("efaturacim_bearer",$bearer);
        }else{
            SessionUtil::setValue("efaturacim_bearer",null);
        }
    }
    public static function setDefaultBearer($bearer=null){
        self::$BEARERS["default"] = $bearer;
    }
    
    public static function initUserDataIfNotSet(&$customer,&$user,&$pass,&$bearerKey){
        $bearerKey = null;
        if(is_null($customer) && is_null($user) && is_null($pass) && key_exists("customer",self::$DEFAULT_OPTIONS) && strlen("".self::$DEFAULT_OPTIONS["customer"])>0){
            $customer = self::$DEFAULT_OPTIONS["customer"];
            $user     = self::$DEFAULT_OPTIONS["user"];
            $pass     = self::$DEFAULT_OPTIONS["pass"];
        }
        if(strlen("".$customer)>0){
            $bearerKey = md5("".$customer."@@".$user."@@".$pass);
        }
    }
    public static function getBearerKey($customer=null,$user=null,$pass=null,$checkWithPing=false){        
        $bearerKey = null;
        $isDefault = is_null($customer) && is_null($user) && is_null($pass) && key_exists("customer",self::$DEFAULT_OPTIONS);
        if($checkWithPing==false && $isDefault && strlen("".@self::$BEARERS["default"])>0){
            return "".self::$BEARERS["default"];
        }
        self::initUserDataIfNotSet($customer,$user,$pass,$bearerKey);
        
        if(strlen("".$bearerKey)>0){                        
            if(key_exists("".$bearerKey,self::$BEARERS)){                                
                return self::$BEARERS[$bearerKey];
            }else{
                if(strlen($customer)>0){
                    $r = RestApiClient::getLogin(self::$BASE_API_URL,"EFaturacim/Login",array("customer"=>$customer,"user"=>$user,"pass"=>$pass,"clientInfo"=>"PHP UNIT TEST","clientSecret"=>"SecretForTestSys"));                    
                    if($r->isOK() && strlen("".$r->getAttribute("bearer"))>0){
                        self::$BEARERS[$bearerKey] = $r->getAttribute("bearer");
                        if($isDefault){
                            self::$BEARERS["default"] = $r->getAttribute("bearer");
                        }                        
                        return self::$BEARERS[$bearerKey];
                    }
                }
            }
        }else{
            return @self::$BEARERS["default"];
        }
        return null;
    }
    public static function loginWithPing($customer=null,$user=null,$pass=null){
        return self::login($customer,$user,$pass,true);
    }
    public static function login($customer=null,$user=null,$pass=null,$checkWithPing=false){
        if($checkWithPing==false && is_null($customer) && is_null($user) && is_null($pass) && key_exists("customer",self::$DEFAULT_OPTIONS) && strlen("".@self::$BEARERS["default"])>0){
            return true;
        }
        $bearer = self::getBearerKey($customer,$user,$pass,$checkWithPing);
        if(strlen($bearer)>0){            
            return true;
        }
        return false;
    }
    public static function getStatus(){
        if(self::login()){
            RestApiClient::$DEFAULT_BEARER_TOKEN = self::getBearerKey();
            return RestApiClient::getJsonResult(self::$BASE_API_URL,"EFaturacim/Status",array());
        }
    }
    protected static function  getPostParams($default=null,$params=null){
        $r = array();
        if($default && is_array($default)){
            foreach($default as $k=>$v){
                if(is_scalar($v)){
                    $r[$k] = $v;
                }
            }
        }
       if($params && is_array($params)){
            foreach($params as $k=>$v){
                if(is_scalar($v)){
                    $r[$k] = $v;
                }
            }
        } 
        return $r;
    }
    public static function getMesajListesi($firmaRef=null,$postParams=null){
        if(self::login()){
            RestApiClient::$DEFAULT_BEARER_TOKEN = self::getBearerKey();
            return RestApiClient::getJsonResult(self::$BASE_API_URL,"EFaturacim/Mesaj/Liste",self::getPostParams(array("firma"=>$firmaRef),$postParams));
        }
    }
    public static function getMesajIcerigi($firmaRef=null,$mesajRef=0,$postParams=null){
        if(self::login()){
            RestApiClient::$DEFAULT_BEARER_TOKEN = self::getBearerKey();
            return RestApiClient::getJsonResult(self::$BASE_API_URL,"EFaturacim/Mesaj/Icerik",self::getPostParams(array("firma"=>$firmaRef,"mesaj_ref"=>$mesajRef),$postParams));
        }
    } 
      
}

?>