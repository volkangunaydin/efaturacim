<?php
namespace Efaturacim\Util\Utils\Cache;

use Exception;

class SimpleRedis{
  /**
     * @var \Redis
     */
    protected static $INSTANCE = null;
    protected static $INSTANCE_TYPE = null;
    protected static $PREFIX        = "";    
    protected static $HOST          = "127.0.0.1";
    protected static $PORT          = 6379;
    public static function setPrefix($prefix){
        self::$PREFIX = $prefix."::";
    }
    public static function getPrefix(){
        return self::$PREFIX;
    }
    public static function isOK(){
        if(!is_null(self::$INSTANCE)){
            return true;
        }
        if(class_exists("\Redis")){            
            self::$INSTANCE_TYPE = "phpredis";
            try {
                self::$INSTANCE = new \Redis();
                $a = self::$INSTANCE->connect(self::$HOST, self::$PORT);                
                if($a){  return true; }                
            } catch (Exception $e) {
                self::$INSTANCE = null;
            }            
        }else{

        }
    }
    public static function delete($cacheKey){
        if(self::isOK()){
            $a = self::$INSTANCE->del(self::$PREFIX . $cacheKey);
            if($a && $a>0){
                return true;
            }
        }
        return false;
    }
                
    protected static function get($cacheKey,$defValue=null){
        if(self::isOK()){
            try {
                $a = self::$INSTANCE->get(self::$PREFIX . $cacheKey);
                dd($a);
            } catch (Exception  $e) {
                //throw $th;
            }
        }
        return $defValue;
    }
    public static function getContent($cacheKey,$defValue=null,$timeout=3600){
        if(self::isOK()){
            $a = self::$INSTANCE->get(self::$PREFIX . $cacheKey);            
            if($a !== false && is_string($a)){
                try {                    
                    $b = unserialize($a);
                    //\Vulcan\V::dump($b);
                    if($b !== false && is_array($b) && key_exists("data",$b)){                        
                        $currentTime = time();
                        $isValid = ($currentTime - $b["time"]) < $timeout;
                        if($isValid){
                            return $b["data"];
                        }
                    }
                } catch (Exception $e) {
                    //throw $th;
                }                
            }    
        }
        return $defValue;
    }
    public static function setContent($cacheKey,$data=null,$timeout=3600){
        if(self::isOK()){
            $dataAsArray = serialize(array("data"=>$data,"time"=>time(),"timeout"=>$timeout));
            $a = self::$INSTANCE->setex(self::$PREFIX . $cacheKey,$timeout,$dataAsArray);
            return $a;
        }
        return false;
    }
}
?>