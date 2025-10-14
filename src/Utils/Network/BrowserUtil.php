<?php
namespace Efaturacim\Util\Utils\Network;

use Efaturacim\Util\Utils\Header\HeaderUtil;
use Efaturacim\Util\Utils\Json\JsonUtil;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\PreviewUtil;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StrUtil;

class BrowserUtil{
    protected static $__disableSSLVerification = false;
    public static function remoteFetchAndShowOnBrowser($url,$fileName){
        $res = self::readUrlWithCurl($url);
        if($res->isOK()){
            PreviewUtil::showFileContent(null, $res->value,$fileName);
        }else{
            die("");
        }                        
        return null;
    }
    public static function disableSSLVerification(){
        self::$__disableSSLVerification = true;
    }
    public static function curlResponseHeaderCallback($ch, $headerLine){
        
    }
    public static function postBody($url,$postBodyString,$options=null,$headersExtra=null,$postField=null){
        return self::smart($url,$options,$headersExtra,array("payload"=>$postBodyString,"postfield"=>$postField));
    }
    public static function smart($url,$options=null,$headersExtra=null,$defVals=null){
        $res = new SimpleResult();
        if(Options::ensureParam($options,$defVals) && $options instanceof Options){
            $headers = array();                                
            try {
                $use_same_session = $options->getAsBool(array("same_session","use_session","use_same_session"));
                $bg               = $options->getAsBool(array("bg","background"));
                $useCurlExec      = $options->getAsBool(array("use_exec"));
                if($bg || $use_same_session){ $useCurlExec = true; }
                if($headersExtra && is_array($headersExtra)){
                    foreach ($headersExtra as $key=>$headerString){
                        $headers[] = $key.": ".$headerString;
                    }
                }
                $payload  = $options->getAsString(array("payload","post_string"));
                $dataType = $options->getAsString(array("dataType","datatype","data_type"));                    
                if($dataType && strlen("".$dataType)>0){
                    if($dataType=="json"){
                        $headers[] = "Content-Type: application/json;charset=utf-8";
                    }
                }
                //\Vulcan\V::dump($headers);
                if($useCurlExec){
                    $optionsString  = "";
                    //($bg?"-v ":"-v ")
                    if(!$bg){
                        $optionsString .= "-i ";
                    }                         
                    $optionsString .= "--insecure ";
                    if($use_same_session){
                        $optionsString .= ' --cookie "'.session_name()."=".session_id().'"';
                    }                               
                    $command = 'curl '.$optionsString.' "'.$url.'"';
                    $resExec = OperatingSystem::run($command,array("bg"=>true));
                    if($resExec->isOK()){
                        $res->setIsOk(true); 
                    }                        
                    if(!$bg){       
                        $headersAsString = array();
                        $c       = '';
                        $contentStarted = false;
                        foreach ($resExec->list as $k=>$v){
                            if($contentStarted){
                                $c .= (strlen($c)>0?"\r\n":"").$v;
                            }else{
                                if(is_null($v) || $v==""){
                                    $contentStarted = true;
                                }else{
                                    $headersAsString[] = $v;
                                }                                    
                            }
                        }
                        $res->setAttribute("headers", $headersAsString);
                        $res->value = $c;
                    }
                    //\Vulcan\V::dump($res);
                    $res->setAttribute("command", $command);
                    return $res;
                }
                
                $attributesExtra  = array();
                $cookieString     = '';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                if(self::$__disableSSLVerification){
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);                        
                }else if($options->getAsBool(array("check_ssl"))){
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);                        
                }else{
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);                        
                }
                if($headers && count($headers)>0){                        
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);                        
                }                    
                if($payload && strlen("".$payload)>0){
                    $postfield = $options->getAsString(array("postfield","post_field"));
                    curl_setopt($ch, CURLOPT_POST, 1);
                    if(StrUtil::notEmpty($postfield)){
                            $sPost = http_build_query(array( $postfield => $payload ));                             
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $sPost);                             
                    }else{                            
                            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
                    }                        
                    $attributesExtra["post__payload"] = $payload;                        
                }
                if(StrUtil::notEmpty($cookieString)){
                    curl_setopt($ch, CURLOPT_COOKIE, $cookieString);                        
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
                $val = curl_exec($ch);
                if($val === FALSE) {
                    $res->addError(curl_error($ch));
                } else {
                    $res->value = $val;
                    if($res->value && strlen($res->value)){
                        $res->setIsOk(true);
                    }
                }
                $info = curl_getinfo($ch);                    
                curl_close($ch);
                if(is_array($info)){
                    $res->attributes = $info;
                    $res->setAttribute("smart_type", StringType::getStringType($res->value));
                }    
                if($headers && count($headers)>0){   
                    $res->attributes["request_headers"] = $headers;
                }
                if($attributesExtra && count($attributesExtra)>0){
                    foreach ($attributesExtra as $k=>$v){
                        $res->attributes[$k] = $v;
                    }                                               
                }
                $res->attributes["response_headers"] = $responseHeaders;
                $res->attributes["response_cookies"] = $cookiesArray;
                
                //\Vulcan\V::dump($responseHeaders);
            } catch (\Exception $e) {
            }                
        }
        return $res;            
    }

    public static function readUrlWithCurl($url,$postParams=null,$options=null){
        $res = new SimpleResult();
        $res->setAttribute("url", $url);
        if(Options::ensureParam($options) && $options instanceof Options){
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                if(!is_null($postParams) && is_array($postParams)){
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
                    curl_setopt($ch, CURLOPT_POSTFIELDS , $postParams);
                    $res->setAttribute("post", $postParams);
                }
                if($options->getAsBool(array("skip_ssl_check"),true)){
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                }                    
                if($options->getAsString(array("http"),"")=="1.1"){
                    curl_setopt($ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
                }
                //                    
                $val = curl_exec($ch);
                if($val === FALSE) {
                    $res->addError(curl_error($ch));
                } else {
                    $res->value = $val;
                    if($res->value && strlen($res->value)){
                        $res->setIsOk(true);
                    }
                }
                curl_close($ch);
            } catch (\Exception $e) {
            }                
        }
        return $res;
    }
    public static function readUrlAsString($url){
        $r = self::readUrlWithCurl($url);
        return "".$r->value;
    }
    public static function post($url,$postParams=null,$options=null){
        return self::readUrlWithCurl($url,$postParams,$options);
    }
    public static function get($url,$options=null){
        return self::readUrlWithCurl($url,null,$options);
    }
    public static function getJson($url,$postParams=null,$options=null){
        $a =  self::readUrlWithCurl($url,$postParams,$options);
        if($a && $a->isOK() && strlen("".$a->value)>0){
            return  JsonUtil::getAsArray($a->value);
        }
        return array();            
    }
    public static function stream($urlToGet,$options=null){
        if(StrUtil::notEmpty($urlToGet) && Options::ensureParam($options) && $options instanceof Options){
            $ch = curl_init();                
            // 2. Set cURL options
            curl_setopt($ch, CURLOPT_URL, $urlToGet);
            $basic_auth = $options->getAsBool(array("basic_auth"));
            if($basic_auth){
                $userName = $options->getAsString("user");
                $userPass = $options->getAsString("pass");                    
                curl_setopt($ch, CURLOPT_USERPWD, $userName . ':' . $userPass);
            }
            $forceSslCheck = $options->getAsBool("force_ssl_check",false);
            if($forceSslCheck){

            }else{
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);                        
            }
            $fileName = $options->getAsString(array("file_name","filename","fileName"));
            $file_size = $options->getAsInt(array("file_size","filesize","fileSize"));
            HeaderUtil::downloadHeader($fileName);

            curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) {                    
                echo $data;                    
                // Return the number of bytes written to tell cURL that we processed the chunk successfully
                return strlen($data);
            });
            curl_exec($ch);
            if (curl_errno($ch)) {
                die("ERROR =>".\curl_error($ch)."[  ".curl_errno($ch)." ]");
            }
        }
        die("");
    }
}

?>