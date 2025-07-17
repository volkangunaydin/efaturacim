<?php
namespace Efaturacim\Util;


class RestApiClient{
    public static function getResult($baseApiUrl,$relPath,$postVars=null,$options=null){        
        $r = new RestApiResult();
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
    public static function getJsonResult($baseApiUrl,$relPath,$postVars=null,$options=null){        
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
}
?>