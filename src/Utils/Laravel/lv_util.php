<?php
class lv{
    public static function route($routeName,$defaultValue=null){
        try {
            // Try to use Laravel's route() helper
            if (function_exists('route')) {
                return route($routeName);
            }            
            // Try to use Laravel's URL facade
            if (class_exists('Illuminate\Support\Facades\URL')) {
                return \Illuminate\Support\Facades\URL::route($routeName);
            }
            
            // Try to use Laravel's Route facade
            if (class_exists('Illuminate\Support\Facades\Route')) {
                $route = \Illuminate\Support\Facades\Route::getRoutes()->getByName($routeName);
                if ($route) {
                    return $route->uri();
                }
            }            
        } catch (\Exception $e) {
            // Laravel route() failed, use fallback
        }
        return $defaultValue;
    }
    public static function getBaseUrl(){
        $isSSL = false;
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
            $isSSL = true;
        }
        return ($isSSL ? "https://" : "http://").$_SERVER['HTTP_HOST'];
    }
}
?>