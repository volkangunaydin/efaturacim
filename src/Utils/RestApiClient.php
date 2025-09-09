<?php
namespace Efaturacim\Util\Utils;

use Efaturacim\Util\Utils\Events\AppEvents;
use Efaturacim\Util\Utils\IO\FileCache;
use Efaturacim\Util\Utils\Results\ResultUtil;
use Efaturacim\Util\Utils\String\StrUtil;


class RestApiClient{
    public static $DEFAULT_BEARER_TOKEN = null;
    public static $DEFAULT_API_URL      = null;
    public static $SERVER_SECURE_KEY    = null;
    protected static $initCalled        = false;
    public static function init(){
        if(!self::$initCalled){
            self::$initCalled = true;
            $init_events = array("restapiclient.init","init.restapiclient");
            if(AppEvents::has($init_events)){
                AppEvents::fire($init_events,array());
            }
        }
    }
    public static function setDefaultUrl($url){
        self::$DEFAULT_API_URL = $url;
    }
    public static function getDefaultUrl(){
        self::init();
        return self::$DEFAULT_API_URL;
    }
    public static function setServerSecureKey($key){
        self::$SERVER_SECURE_KEY = $key;
    }
    public static function getResult($baseApiUrl,$relPath,$postVars=null,$options=null){        
        self::init();
        $r = new RestApiResult();
        if(is_null($baseApiUrl) && !is_null(self::$DEFAULT_API_URL) && strlen("".self::$DEFAULT_API_URL)>0){
            $baseApiUrl = self::getDefaultUrl();
        }
        if(StrUtil::notEmpty($baseApiUrl) && StrUtil::notEmpty($relPath) && Options::ensureParam($options) && $options instanceof Options){
            if(function_exists("curl_init")){
                $url = $baseApiUrl;
                if(strlen("".$url)>0 && substr($url,-1)!=="/"){
                    $url .= "/";
                }
                if(strlen("".$relPath)>0 && substr($relPath,0,1)==="/"){
                    $url .= substr($relPath,1);
                }else{
                    $url .= $relPath;
                }                
                try {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    if(!is_null($postVars) && is_array($postVars)){
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
                        curl_setopt($ch, CURLOPT_POSTFIELDS , $postVars);
                        $r->setAttribute("post", $postVars);
                    }
                    if($options->getAsBool(array("skip_ssl_check"),true)){
                        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
                    }                    
                    if($options->getAsString(array("http"),"")=="1.1"){
                        curl_setopt($ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
                    }
                    //                    
                    $val = curl_exec($ch);
                    //echo "".$val."";die("");
                    $info = curl_getinfo($ch);
                    $r->setAttribute("content_type","".@$info["content_type"]);
                    $r->setAttribute("http_code","".@$info["http_code"]);
                    $r->setAttribute("scheme","".@$info["scheme"]);
                    $r->setAttribute("primary_ip","".@$info["primary_ip"]);
                    $r->setAttribute("total_time","".@$info["total_time"]);
                    if($val === FALSE) {
                        $r->addError(curl_error($ch));
                    } else {
                        $r->value = $val;
                        if($r->value && strlen($r->value)){
                            $r->setIsOk(true);
                        }
                    }
                    curl_close($ch);
                } catch (\Exception $e) {
                }                                
            }else{
                $r->addError("CURL yukleyınız !");
            }
        }
        return $r;
    }    
    public static function getJsonResultCached($cacheFolder,$cacheTimeout,$baseApiUrl,$relPath,$postParams=null,$options=null){              
        if(is_null($cacheFolder)){
            $cacheFolder = "../content_cache/servis_cache/";            
        }          
        if(strlen("".$cacheFolder)>0 && is_dir($cacheFolder)){
            if(substr($cacheFolder,-1)!=="/"){
                $cacheFolder = $cacheFolder."/";
            }
            $rr = FileCache::getCached($cacheFolder,array("url"=>$baseApiUrl,"relPath"=>$relPath,"postParams"=>$postParams,"options"=>$options),function() use($baseApiUrl,$relPath,$postParams,$options){
                return self::getJsonResult($baseApiUrl,$relPath,$postParams,$options);
            },$cacheTimeout);
            if($rr->isOK() && !is_null($rr->value) && $rr->value instanceof SimpleResult){                
                $rr->value->setAttribute("__cache_age",$rr->getAttribute("cache_age"));
                $rr->value->setAttribute("__cache_file",$rr->getAttribute("cache_file"));
                $rr->value->setAttribute("__cache_status",$rr->getAttribute("cache_status"));
                return $rr->value;
            }
        }
        $r = self::getJsonResult($baseApiUrl,$relPath,$postParams,$options);
        return $r;
    }
    public static function getJsonResult($baseApiUrl,$relPath,$postParams=null,$options=null){      
        $baseApiUrl = env("API_URL_CANLI2");  
        self::init();
        $postVars  = array();
        if($postParams && is_array($postParams)){
            foreach($postParams as $k=>$v){ $postVars[$k] = $v;  }
        }
        $postVars["clientSecret"] = SecurityUtil::getClientKey();
        $postVars["clientInfo"]   = SecurityUtil::getUserAgent();
        $postVars["apiKey"]       = self::$SERVER_SECURE_KEY;
        $postVars["ip"]           = SecurityUtil::getIp();
        if(self::$DEFAULT_BEARER_TOKEN && strlen("".self::$DEFAULT_BEARER_TOKEN)>0){
            $postVars["bearer"] = self::$DEFAULT_BEARER_TOKEN;
        }
        $resResult = self::getResult($baseApiUrl,$relPath,$postVars,$options);
        $r = new RestApiResult();        
        if($resResult->isOK()){
            $jsonString = $resResult->value;                        
            $ct         = $resResult->getAttribute("content_type","");
            if(strlen("".$jsonString)>0 && StrUtil::isJson($jsonString)){                                
                $jsonArray = @json_decode($resResult->value,true);                                                
                $r->value = $jsonArray;
                $r->__isok     = CastUtil::getAs(@$jsonArray["isok"],false,CastUtil::$DATA_BOOL);
                $r->attributes = CastUtil::getAs(@$jsonArray["attributes"],array(),CastUtil::$DATA_ARRAY);
                $r->lines = CastUtil::getAs(@$jsonArray["lines"],array(),CastUtil::$DATA_ARRAY);
                $r->messages = CastUtil::getAs(@$jsonArray["messages"],array(),CastUtil::$DATA_ARRAY);
                $r->dataObject     = @$jsonArray["data"];
                return $r;
            }
        }
        return $r;
    }
    public static function getLogin($baseApiUrl,$relPath,$postParams=null,$options=null){        
        $r = new RestApiResult();
        $res = static::getJsonResult($baseApiUrl,$relPath,$postParams,$options);
        //\Vulcan\V::dump($res);
        if($res->isOK()){
            $bearer  =  $res->getAttribute("bearer");
            $userRef =  $res->getAttribute("user_reference",0,"int");
            if($userRef>0 && strlen("".$bearer)>0){
                self::$DEFAULT_BEARER_TOKEN = $bearer;
                return $res;
            }            
            $res->setIsOk(false);
            $res->statusCode = 401;
        }   
        $r->addError("Kullanıcı doğrulanamadı.");     
        if($res->hasError()){
            ResultUtil::mergeMessages($r,$res);
        }
        return $r;
    }   
    public static function setBearer($bearer){
        self::$DEFAULT_BEARER_TOKEN = $bearer;
    }
    public static function ensureLoginAndGetBearer($baseApiUrl,$customer,$user,$pass,$funcGetBearer,$funcSetBearer,$setAsDefault=false){        
        if(!is_null($funcGetBearer) && is_callable($funcGetBearer)){
            $bearer = call_user_func_array($funcGetBearer,array());
            if(strlen("".$bearer)>0){
                $r2 = self::getJsonResult($baseApiUrl,"EFaturacim/Ping",array("bearer"=>$bearer));
                if($r2->isOK() && $r2->getAttribute("loggedin",false,CastUtil::$DATA_BOOL)){
                    if($setAsDefault){
                        self::$DEFAULT_BEARER_TOKEN = $bearer;
                    }
                    return $bearer;
                }                
            }
        }
        $r = self::getLogin($baseApiUrl,"EFaturacim/Login",array("customer"=>$customer,"user"=>$user,"pass"=>$pass,"clientInfo"=>@$_SERVER["HTTP_USER_AGENT"],"clientSecret"=>SecurityUtil::getClientKey()));                    
        $bearer = ($r && $r->isOK()) ?  $r->getAttribute("bearer") : null;
        if($r->isOK() && strlen("".$bearer)>0){
            if(!is_null($funcSetBearer) && is_callable($funcSetBearer)){
                call_user_func_array($funcSetBearer,array($bearer,$r));
            }
            if($setAsDefault){
                self::$DEFAULT_BEARER_TOKEN = $bearer;
            }
            return $bearer;
        }
        return null;
    }
    public static function getDebugInfo(){
        self::init();
        return array(
            "default_bearer_token"=>self::$DEFAULT_BEARER_TOKEN,
            "default_api_url"=>self::$DEFAULT_API_URL,
            "server_secure_key"=>self::$SERVER_SECURE_KEY,
        );
    }
}
?>