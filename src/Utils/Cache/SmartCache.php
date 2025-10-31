<?php
namespace Efaturacim\Util\Utils\Cache;

use Efaturacim\Util\Utils\CastUtil;
use Exception;



class SmartCache{
    private static $defaultMode     = null;
    private static $prefix          = "mysite";
    public  static $redisHost       = "127.0.0.1";
    public  static $redisPort       = 6379;
    public  static $redisTimeout    = 0.5; // Connection timeout in seconds
    public  static $defaultTimeout  = 600;
    protected static $redisInstance = null;
    protected static $MEMORY_CACHE  = [];
    public static function setMode($mode="memory"){
        return self::$defaultMode = $mode;        
    }
    public static function getMode(){
        if(self::$defaultMode === null){
            try {
                if(class_exists("\Redis")){ 
                    self::$redisInstance = new \Redis();                    
                    $a = self::$redisInstance->connect(self::$redisHost, self::$redisPort, self::$redisTimeout);    
                    if($a){  
                        return self::setMode("redis");
                    }  
                }                    //code...
            } catch (Exception $th) {
                return self::setMode("memory");
            }
        }
        return self::$defaultMode;
    }

    public static function getKeyForContext($key,$context=null){
        if(!is_null($context) && strlen($context)>0){
            if($context=="session"){
                return $key."@@".session()->getId();
            }
        }
        return $key;
    }
    public static function getCacheEngine(&$cacheEngine){
        if(is_null($cacheEngine) || $cacheEngine==""){            
            $cacheEngine = self::getMode();                        
        }
        return "".$cacheEngine;
    }    

    public static function getData($key,$timeout=0,$callback=null,$cacheEngine=null,$timeoutForPrepare=0){      
        $cacheTime = null;
        return self::getDataWithCacheTime($cacheTime,$key,$timeout,$callback,$cacheEngine,false,$timeoutForPrepare);
    }
    public static function getDataWithCacheTime(&$cacheTime,$key,$timeout=0,$callback=null,$cacheEngine=null,$timeoutForPrepare=0,$forceSkipCache=false,$depth=0,$sleepTime=1){        
        $cacheTime = null;
        if($forceSkipCache && is_bool($forceSkipCache) && is_callable($callback)){
            return call_user_func_array($callback, array());            
        }
        self::getCacheEngine($cacheEngine);          
        if($depth>30){ $timeoutForPrepare = 0; }         
        //  $lastTime = self::$redisInstance->get("__preparing@".self::$prefix."@".$key);                     
        //\Vulcan\V::dump(array("key"=>$key,"timeout"=>$timeout,"timeoutForPrepare"=>$timeoutForPrepare,"prepareTime"=>self::$redisInstance->get("__preparing@".self::$prefix."@".$key),"depth"=>$depth,"sleepTime"=>$sleepTime,"exists"=>self::exists($key,$timeout+$timeoutForPrepare,$cacheEngine)));
        if(self::exists($key,$timeout+$timeoutForPrepare,$cacheEngine)){
            try {
                if($cacheEngine === "redis"){                          
                    $data = self::$redisInstance->get(self::$prefix."@".$key);                          
                    if($data && strlen($data)>0){
                        $data = @unserialize($data);                                    
                        //\Vulcan\V::dump(array("data"=>$data));
                        if($data && is_array($data)){                            
                            $elapsed = time() - @$data["time"];                                                         
                            //\Vulcan\V::dump(array("elapsed"=>$elapsed,"timeout"=>$timeout,"timeoutForPrepare"=>$timeoutForPrepare));
                            if($elapsed <= $timeout){
                                $cacheTime = date("Y-m-d H:i:s",0+$data["time"]);                                                                                                
                                return @$data["data"];
                            }else if($timeoutForPrepare && $timeoutForPrepare>0){                                
                                $lastTime = self::$redisInstance->get("__preparing@".self::$prefix."@".$key);                                                                                                
                                if ($lastTime && is_numeric($lastTime) && $lastTime > 0 ){
                                    $diff = time() - CastUtil::asInt($lastTime);                                    
                                    //\Vulcan\V::dump(array("lastTime"=>$lastTime,"diff"=>$diff,"timeoutForPrepare"=>$timeoutForPrepare));                                    
                                    if($diff < $timeoutForPrepare){
                                        $cacheTime = date("Y-m-d H:i:s",0+$data["time"]);                                                                                                
                                        return @$data["data"];        
                                    }                                    
                                }
                            }                                                        
                            $s = self::setData($key,$timeout,null,$cacheEngine,$callback,$timeoutForPrepare);                                        
                            return $s;
                       }
                    }                    
                }    
            } catch (\Throwable $th) {
                if(!is_null($callback) && is_callable($callback)){
                    $rawString = call_user_func_array($callback, array());                                
                    return $rawString;
                }                
            }
        }else{                                    
            if($timeoutForPrepare && $timeoutForPrepare>0){
                try {
                    $lastTime = self::$redisInstance->get("__preparing@".self::$prefix."@".$key);                                
                } catch (Exception $e) {
                    $lastTime = null;
                }                                      
                if ($lastTime && is_numeric($lastTime) && $lastTime > 0 && (time() - CastUtil::asInt($lastTime)) < $timeoutForPrepare  ){                    
                    if($sleepTime<=0){ $sleepTime = 1;}
                    if($sleepTime>0){
                        sleep($sleepTime);
                        return self::getDataWithCacheTime($cacheTime,$key,$timeout,$callback,$cacheEngine,$timeoutForPrepare,false,$depth+1,$sleepTime);
                    }                    
                }                
            }            
            $s =  self::setData($key,$timeout,null,$cacheEngine,$callback,$timeoutForPrepare);            
            return $s;
        }
        return null;
    }
    public static function delete($key,$cacheEngine=null){
        self::getCacheEngine($cacheEngine);
        if($cacheEngine === "redis"){
            try {
                return self::$redisInstance->del($key);
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }
    public static function setData($key,$timeout=0,$rawStringOrObject=null,$cacheEngine=null,$callback=null,$timeoutForPrepare=0){
        self::getCacheEngine($cacheEngine);        
        if(!is_null($callback) && is_callable($callback)){
            if($timeoutForPrepare && $timeoutForPrepare>0){                
                if($cacheEngine === "redis"){
                    self::$redisInstance->setex("__preparing@".self::$prefix."@".$key,$timeoutForPrepare,time());
                }                
            }        
            $rawString = call_user_func_array($callback, array());                   
            if($timeoutForPrepare && $timeoutForPrepare>0){
                if($cacheEngine === "redis"){
                    try { self::$redisInstance->del("__preparing@".self::$prefix."@".$key);} catch (Exception $e) {}                
                }                
            }
        }        
        $timeout = $timeout >= 0 ? $timeout : self::$defaultTimeout;
        $stringToSend = serialize(array("time"=>time(),"timeout"=>$timeout,"data"=>$rawString));          
        if($cacheEngine === "redis"){
            try {                            
                if($timeoutForPrepare && $timeoutForPrepare>0){
                    $timeout = $timeout + (10 * $timeoutForPrepare);
                }                
                $temp = self::$redisInstance->setex(self::$prefix."@".$key,$timeout,$stringToSend);                                                                                                
            } catch (Exception $e) {
                return $rawString;
            }
        }
        return $rawString;
    }    
    public static function clearAll($cacheEngine=null){
        self::getCacheEngine($cacheEngine);
        if($cacheEngine === "redis"){
            if(self::$redisInstance){
                self::$redisInstance->flushAll();
            }
        }
        if($cacheEngine === "memory"){
            self::$MEMORY_CACHE = array();
        }
    }
    public static function exists($key,$timeout=0,$cacheEngine=null){
        $cacheKey = self::$prefix."@".$key;
        self::getCacheEngine($cacheEngine);        
        if($cacheEngine === "redis"){
            try {                
                $exists = self::$redisInstance->exists($cacheKey);                
                if($exists) {                 
                    if($timeout > 0) {
                        $ttl = self::$redisInstance->ttl($cacheKey);
                        return $ttl > 0 || $ttl == -1;
                    }
                    return true;
                }
                return false;
            } catch (Exception $e) {
                return false;
            }
        }
        return false;
    }        
    public static function serveCachedFile($cacheFile,$mimeType,$timeout,$callback,$forceSkipCache=false,$showFile=true){
        $lastModified = null;
        $forceCreate  = false;
        if($forceSkipCache){
            $forceCreate = true;
        }else if(file_exists($cacheFile)){
            $lastModified = filemtime($cacheFile);
            $elapsed = time() - $lastModified;            
            if($elapsed <= $timeout){
                $forceCreate = false;
            }else{
                $forceCreate = true;
            }                    
        }else{
            $forceCreate = true;
        }        
        if($forceCreate && $callback && is_callable($callback)){
            $content = call_user_func_array($callback, array());
            if(!is_null($content) && is_string($content) && strlen($content)>0){
                $dir = dirname($cacheFile);
                if(!file_exists($dir)){
                    mkdir($dir, 0777, true);
                }
                file_put_contents($cacheFile, $content);
            }
        }
        if(!$showFile){
            return null;   
        }
        if(file_exists($cacheFile)){
            if(is_null($lastModified)){
                $lastModified = filemtime($cacheFile);
            }
            header("Last-Modified: " . gmdate('D, d M Y H:i:s', $lastModified) . ' GMT');
            if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && 
                strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModified) {
                header('HTTP/1.1 304 Not Modified');
                exit;
            }
            header("Content-Type: ".$mimeType);
            @readfile($cacheFile);
        }
    }
}