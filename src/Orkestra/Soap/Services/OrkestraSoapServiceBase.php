<?php

namespace Efaturacim\Util\Orkestra\Soap\Services;

use Efaturacim\Util\Orkestra\Soap\OrkestraSoapClient;
use Efaturacim\Util\Orkestra\Soap\Result\OrkestraSoapResult;
use Efaturacim\Util\Orkestra\XML\LoginLogoutXml;
use Efaturacim\Util\Utils\Cache\SimpleRedis;
use Efaturacim\Util\Utils\Laravel\LV;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StrUtil;
use Exception;
use Vulcan\Base\MQ\Redis\RedisDefaultClient;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class OrkestraSoapServiceBase{
    public static $DEFAULT_CACHE_TIMEOUT = 600;
    protected $options = null;    
    protected $serviceName = null;    
    public function __construct($options=null){
        $this->options = new Options($options);
        $this->initMe();
    }
    protected function initMe(){
        $host = $this->options->getAsString("host");
        if(StrUtil::isEmpty($host) && LV::isLaravel()){
            $key  = $this->options->getAsString("key","default");
            $conf = LV::configArray("orkestra",$key);
            if(is_array($conf) && count($conf)>0 && key_exists("host",$conf)){
                foreach($conf as $key=>$val){
                    $this->options->setValue($key,$val);
                }
                $host = $this->options->getAsString("host");
            }            
        }                
    }
    public function useRedisCache(){
        $this->options->setValue("useRedisCache",true);
        return $this;
    }
    public function setSessionId($sessionId){
        $this->options->setValue("sessionId",$sessionId);
        return $this;
    }
    public function getSessionId(){
        return $this->options->getAsString("sessionId",null);
    }
    public function getUserName(){
        return $this->options->getAsString("user",null);
    }
    public function getUserPass(){
        return $this->options->getAsString("pass",null);
    }
    public function getPeriod(){        
        return $this->options->getAsInt("period",null);
    }
    public function getUrl(){
        $isSsl = $this->options->getAsBool(array("isSsl","ssl"),false);
        $host  = $this->options->getAsString("host");
        $port  = $this->options->getAsInt("port",8090);
        $url   = '';
        if($isSsl){
            $url = "https://".$url;
        }else{
            $url = "http://".$url;
        }
        $url .= $host;
        if($port>0){
            $url .= ":".$port;
        }
        $url .= "/ws/".$this->serviceName;
        return $url;        
    }
    public function curlExec($url,$action,$postAsString=null,$headersAdded=null,$useBasicAuth=false,$context=null,$returnResult=true,$soapActionStr=null,$userKey=null,$sessionId=null){        
        $result = new OrkestraSoapResult();
        try {                
            if(StrUtil::isEmpty($url)){ 
                $url = $this->getUrl();
            }                                  
            $params = Options::newParams($context);                
            $headers = array(
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache"                                       
            );            
            
            if(is_null($soapActionStr)){
                $headers[] = "SOAPAction: ".$url."/".$action;
            }else{
                $headers[] = 'SOAPAction: "'.$soapActionStr.'"';
            }
            $headers[] = "Content-length: ".mb_strlen("".$postAsString);     
            if(!is_null($headersAdded) && is_array($headersAdded)){
                foreach ($headersAdded as $k=>$v){
                    $headers[] = $k.": ".$v;
                }
            }                 
            //\Vulcan\V::dump($headers);
            if($params->getAsBool("utf8")){
                // $headers[] = 'Content-type: text/xml;charset="utf-8"';                
            }        
            $attributesExtra = array();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_ENCODING,'utf-8');
            //curl_setopt($ch, CURLOPT_MAX_POST_, 100M);
            if($sessionId && strlen("".$sessionId)>10){
                $headers["Authorization"] = "Authorization: Bearer ".$sessionId;   
            }else if($useBasicAuth && strlen($this->getUserName())>0){                             
                curl_setopt($ch, CURLOPT_USERPWD, $this->getUserName().":".$this->getUserPass());
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            }else{
                $sessionId = $this->getSessionId();
                if(StrUtil::notEmpty($sessionId)){
                    $headers["Authorization"] = "Authorization: Bearer ".$sessionId;   
                }
            }            
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            if($postAsString && is_scalar($postAsString)){                                    
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postAsString); // the SOAP request
                $result->requestString = $postAsString;
                $attributesExtra["post__payload"] = $postAsString;
            }
            //VULCAN::dump($context);
            if($headers){
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }             
            $responseHeaders = array();
            $cookiesArray    = array();
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, function($ch, $headerLine) use(&$responseHeaders,&$cookiesArray){
                $responseHeaders[] = $headerLine;
                if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $cookie) == 1){
                    $cookiesArray[] = $headerLine;
                }
                return strlen($headerLine); // Needed by curl - sakin silme
            });
            $response = curl_exec($ch);            
            $info     = curl_getinfo($ch);
            //\Vulcan\V::dump($result->requestString."\r\n".mb_convert_encoding($response,"UTF-8","UTF-8"));
            if($response && is_string($response)){
                $result->responseText = $response;
            }                
            if(key_exists("http_code",$info) && @$info["http_code"]>=200){
                $result->setIsOk(true);
                
            }           
            @curl_close($ch);            
            if($headers && count($headers)>0){
                $result->attributes["request_headers"] = $headers;
            }
            if($attributesExtra && count($attributesExtra)>0){
                foreach ($attributesExtra as $k=>$v){
                    $result->attributes[$k] = $v;
                }
            }
            $result->attributes["response_headers"] = $responseHeaders;                
            $result->attributes["response_cookies"] = $cookiesArray;
            return $result;
        } catch (Exception $e) {
        }
        return $result;
    }        
    public function isLoginOk($tryLogin=true){
        $sessionId = $this->getSessionId();
        if(StrUtil::isEmpty($sessionId)){
            if($tryLogin){
                $r= $this->login();
                dd($r);
            }
            $sessionId = $this->getSessionId();
            if(StrUtil::notEmpty($sessionId)){
                return true;
            }
        }else{
            return true;
        }
        return false;
    }
    public function getOptions(){
        return $this->options;
    }
    public function isAliveWithPing($sessionId=null){
        $xml = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ws="http://ws.server.orkestra.com.tr"><soap:Header/><soap:Body><ws:ping><data>test</data></ws:ping></soap:Body></soap:Envelope>';
        $resPing = $this->curlExec(null,"ping",$xml,null,false,null,true,null,null,$sessionId);
        if($resPing->isOk()  && $resPing->getStringInBetweenTags("return") == "OK: test"){
            return true;
        }
        return false;
    }
    public function login(){
        $r = new SimpleResult();
        $useRedis = $this->options->getAsBool("useRedisCache",false);
        $user = $this->getUserName();
        $pass = $this->getUserPass();
        $cacheKey = null;
        if($useRedis && SimpleRedis::isOK()){
            $timeout  = $this->options->getAsInt("redisCacheTimeout",self::$DEFAULT_CACHE_TIMEOUT);
            $cacheKey = md5("login@".$this->getUserName()."@".$this->getUrl()."@"."".$pass."".date("Y-m-d"));            
            $tmp      = SimpleRedis::getContent($cacheKey,null,$timeout);                        
            if($tmp && $tmp instanceof SimpleResult){    
                $isok = true;
                $checkForPing = $this->options->getAsBool("checkForPing",true);            
                if($checkForPing){
                    $isok = $this->isAliveWithPing($tmp->value);
                }
                if($isok){
                    $sessionId = $tmp->value;                
                    $this->setSessionId($sessionId);
                    $tmp->setAttribute("cacheKey",$cacheKey);
                    $tmp->setAttribute("cacheStatus","ok");
                    return $tmp;    
                }
            }
        }
        $sessionId = $this->getSessionId();
        if(StrUtil::isEmpty($sessionId)){
            if(StrUtil::notEmpty($user) && StrUtil::notEmpty($pass)){                
                $xml = LoginLogoutXml::login($user,$pass);                
                $resCurl = $this->curlExec(null,"login",$xml,null,false,null,true,null,null);
                if($resCurl->isOk()){
                    $sessionId = $resCurl->getStringInBetweenTags("loginSessionId");
                    if(StrUtil::notEmpty($sessionId)){                
                        $r->setIsOk(true);
                        $r->setAttribute("userName",$user);
                        $r->setAttribute("userPass",$pass);
                        $r->setValue($sessionId);          
                        $this->setSessionId($sessionId);
                        $getUserData = $this->options->getAsBool("getUserData",strlen("".$cacheKey)>0);                            
                        if($getUserData){
                            if($this instanceof OrkestraFactoryWebService){
                                $list = $this->newGetPageList("user");
                            }else{
                                $list = OrkestraSoapClient::getFactoryWithRedis()->newGetPageList("user");
                            }                            
                            $list->addFields("reference","userName","name","surname","status");
                            $list->filterByStringEquals("userName","".$user);
                            $userData = $list->first();
                            if($userData && is_array($userData) && count($userData)>0 && key_exists("reference",$userData) && $userData["userName"]==$user){
                                foreach($userData as $key=>$val){
                                    $r->setAttribute($key,$val);
                                }
                            }                            
                        }                    
                        $this->options->setValue("sessionId",$sessionId);
                        if($useRedis && SimpleRedis::isOK() && strlen("".$cacheKey)>0){
                            SimpleRedis::setContent($cacheKey,$r,$timeout);
                            $r->setAttribute("cacheKey",$cacheKey);
                            $r->setAttribute("cacheStatus","set");
                        }                
                    }                    
                }else{
                    $r->addError("Giriş başarısız. Lütfen kullanıcı adı ve şifrenizi kontrol ediniz.");                    
                }                
            }else{
                $r->addError("Kullanıcı adı veya şifre boş olamaz.");
            }
        }else{
            $r->setIsOk(true);
            $r->setValue($sessionId);
        }        
        return $r;
    }
    public function logout(){
        $r = new SimpleResult();        
        $r->setIsOk(true);
        $useRedis = $this->options->getAsBool("useRedisCache",false);
        if($useRedis && SimpleRedis::isOK()){
            $cacheKey = md5("login@".$this->getUserName()."@".$this->getUrl()."@"."".$this->getUserPass()."".date("Y-m-d"));
            SimpleRedis::delete($cacheKey);
            $r->addSuccess("Önbelleklerden giriş kaydı silindi.");
        }
        $sessionId = $this->getSessionId();
        $r->setAttribute("sessionId",$sessionId);        
        if(StrUtil::notEmpty($sessionId)){
            $xml = LoginLogoutXml::logout($sessionId);
            $resCurl = $this->curlExec(null,"logout",$xml,null,false,null,true,null,null);            
            $r->addSuccess("Güvenli çıkış yapıldı.");
        }
        return $r;
    }
}
?>