<?php
namespace Efaturacim\Util\Utils\Events;
class AppEvents{
    protected static $events = array();
    public static function register($nameOfEvent,$callback){
        self::$events[$nameOfEvent] = $callback;
    }
    protected static function hasEvent( $nameOfEventOrArray){
        if(is_string($nameOfEventOrArray) && strlen("".$nameOfEventOrArray)>0 && isset(self::$events[$nameOfEventOrArray]) && is_callable(self::$events[$nameOfEventOrArray])){
            return $nameOfEventOrArray;
        }else if (is_array($nameOfEventOrArray) && count($nameOfEventOrArray)>0){
            foreach($nameOfEventOrArray as $nameOfEvent){
                $e = self::hasEvent($nameOfEvent);
                if(!is_null($e) && strlen("".$e)>0){
                    return $e;
                }
            }
        }
        return null;
    }
    public static function has($nameOfEventOrArray){
        $e = self::hasEvent($nameOfEventOrArray);        
        return !is_null($e) && strlen("".$e)>0;
    }
    
    public static function fire($nameOfEventOrArray,$paramsAsArray=null){
        $e = self::hasEvent($nameOfEventOrArray);
        if(!is_null($e) && strlen("".$e)>0 && is_callable(self::$events[$e])){
            if(!is_array($paramsAsArray)){
                $paramsAsArray = array();
            }
            return call_user_func_array(self::$events[$e],$paramsAsArray);
        }
        return null;
    }
}

?>