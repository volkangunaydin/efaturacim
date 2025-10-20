<?php

use Efaturacim\Util\Utils\Laravel\LV as LaravelLV;

class lv{
    public static function __callStatic($method, $arguments) {
        if(!class_exists(LaravelLV::class)){
            require_once __DIR__ . '/LV.php';
        }        
        // Check if the static method exists on LV class
        if(method_exists(LaravelLV::class, $method)){
            return LaravelLV::$method(...$arguments);
        }        
        return LaravelLV::callSmart($method, $arguments);        
    }
}
?>