<?php
namespace Efaturacim\Util\Utils\Laravel;

use Efaturacim\Util\Utils\Laravel\Util\LaravelFolderCheck;
use Exception;
use Vulcan\Base\Database\DatabaseConnection;
use Vulcan\Base\Database\MySQL\MySqlDbClient;
use Vulcan\Orkestra\SmartClient\OrkestraSmartClient;

class LV{
    public static $DEBUG = false;
    protected static $__isLaravel = null;
    protected static $__db = array();
    /**
     * @var OrkestraSmartClient
     */
    protected static $__smartClient = null;
    protected static $__isVEnabled = false;
    public static function boot(){
        if(!class_exists('lv')){
            require_once __DIR__ . '/lv_util.php';
        }
    }
    public static function env($name,$default=null){
        return \env($name,$default);
    }
    public static function isLaravel(){
        if(!is_null(self::$__isLaravel) && is_bool(self::$__isLaravel)){ return self::$__isLaravel; }
        self::$__isLaravel = self::__checkIfIsLaravel();
        if(self::$__isLaravel){
            require_once __DIR__ . '/lv_util.php';
        }
        return self::$__isLaravel;
    }
    protected static function __checkIfIsLaravel(){        
        if(class_exists('Vulcan\V')){
            self::$__isVEnabled = true;
        }
        // Method 1: Check for Laravel helper functions
        if (function_exists('base_path') && function_exists('app')) {
            self::$__isLaravel = true;
            return true;
        }
        
        // Method 2: Check for Laravel Application class and instance
        if (class_exists('Illuminate\Foundation\Application')) {
            try {
                $app = app();
                if ($app instanceof \Illuminate\Foundation\Application) {
                    return true;
                }
            } catch (Exception $e) {
                // Laravel class exists but not initialized
            }
        }
        
        // Method 3: Check for Laravel constants
        if (defined('LARAVEL_START') || defined('APP_PATH')) {
            return true;
        }
        
        // Method 4: Check for Laravel environment variables
        if (isset($_ENV['APP_ENV']) || isset($_SERVER['APP_ENV'])) {
            return true;
        }
        
        // Method 5: Check if we're in a Laravel project directory
        if (self::isLaravelProject()) {
            return true;
        }
        
        return false;
    }
    
    public static function isLaravelProject($path = null){
        return LaravelFolderCheck::isLaravelProject($path);   
    }    
    /**
     * @return MySqlDbClient
     */
    public static function getDBForOrkestra(){
        return self::getDB("orkestra");
    }
    /**
     * @return MySqlDbClient
     */
    public static function getDB($key=null,$resumeOnError=true){    
        if(is_null($key)){ $key = "default"; }
        $laravelKey = $key;        
        if(key_exists($key,self::$__db)){
            return self::$__db[$key];
        }
        if(LV::isLaravel()){            
            $dbArray = \Illuminate\Support\Facades\Config::get('database.connections.'.$key);                  
            if($dbArray && is_string($dbArray) && strlen("".$dbArray)>0){                
                $laravelKey = $dbArray;
                $dbArray = \Illuminate\Support\Facades\Config::get('database.connections.'.$dbArray);                
            }
            if(is_null($dbArray) && $key=="default"){                
                $laravelKey = "mysql";
                $dbArray = \Illuminate\Support\Facades\Config::get('database.connections.mysql');
            }            
            if(is_array($dbArray) && count($dbArray)>0){
                try {
                    $arr = array("user"=>@$dbArray["username"],"pass"=>@$dbArray["password"],"server"=>@$dbArray["host"].":".@$dbArray["port"],"db"=>@$dbArray["database"]);
                    \Vulcan\V::setEnv("db:".$key,$arr);
                    $connection = \Illuminate\Support\Facades\DB::connection($laravelKey);
                    $pdo = $connection->getPdo();                                        
                    $tmp = DatabaseConnection::getDatabaseWithInit($key,$pdo,true,false);
                    $arr["pdo"]       = &$pdo;                    
                    self::$__db[$key] = DatabaseConnection::getDatabaseWithInit($key,$arr,true);                    
                } catch (Exception $e) {
                    //throw $th;
                }
            }
        }
        if(key_exists($key,self::$__db)){
            return self::$__db[$key];
        }
        return null;
    }
    public static function configArray($name,$key=null){
        if(is_null($key)){ $key = "default"; }
        $r =  \Illuminate\Support\Facades\Config::get(''.$name.'.'.$key);
        if(is_array($r)){
            return $r;
        }
        return array();
    }
    public static function view($viewName,$data=[]){
        if(self::isLaravel()){
            return view($viewName,$data);
        }
        return "";
    }
    /**
     * @return OrkestraSmartClient
     */
    public static function getSmartClientForOrkestra(){
        if(is_null(self::$__smartClient)){
            $dbOrkestra = self::getDBForOrkestra();
            if($dbOrkestra instanceof MySqlDbClient){                
                $dbB4B       = LV::getDB();                        
                self::$__smartClient = new OrkestraSmartClient($dbOrkestra->dbKey);
                if($dbB4B instanceof MySqlDbClient){
                    self::$__smartClient->setDbKeyForB4B($dbB4B->dbKey);
                    $orkestraConfig = self::configArray("orkestra","default");
                    if(is_array($orkestraConfig) && count($orkestraConfig)>0 && key_exists("period",$orkestraConfig)){
                        self::$__smartClient->selectPeriod($orkestraConfig["period"]);
                        self::$__smartClient->options->setValue("period_reference",$orkestraConfig["period"]);
                        self::$__smartClient->options->setValue("orkestra_user",$orkestraConfig["user"]);
                        self::$__smartClient->options->setValue("orkestra_pass",$orkestraConfig["pass"]);
                        self::$__smartClient->options->setValue("orkestra_host",$orkestraConfig["host"]);
                        self::$__smartClient->options->setValue("orkestra_port",$orkestraConfig["port"]);
                    }
                    //\Vulcan\V::dump($orkestraConfig);
                }
            }                        
        }
        return self::$__smartClient;
    }
    public static function throwException($message,$code=500){
        throw new Exception($message,$code);
    }
    public static function log($message,$level='info'){
        if($level=='info'){
            \Log::info($message);
        }elseif($level=='error'){
            \Log::error($message);
        }elseif($level=='warning'){
            \Log::warning($message);
        }elseif($level=='debug'){
            \Log::debug($message);
        }else{
            \Log::info($message);
        }   
    }
    public static function getBaseUrl(){
        $isSSL = false;
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
            $isSSL = true;
        }
        return ($isSSL ? "https://" : "http://").$_SERVER['HTTP_HOST'];
    }
    public static function error($message){
        throw new \Exception($message);
    }    
    public static function flash($key=null,$defaultValue=null){
        if(class_exists('Illuminate\Support\Facades\Session')){
            return \Illuminate\Support\Facades\Session::flash($key,$defaultValue);
        }
        return null;
    }
    public static function route($routeName=null,$defaultValue=null,$arguments=null){
        try {
            if (function_exists('route')) {
                return route($routeName,$arguments);
            }            
            // Try to use Laravel's URL facade
            if (class_exists('Illuminate\Support\Facades\URL')) {
                return \Illuminate\Support\Facades\URL::route($routeName,$arguments);
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
    public static function callSmart($method,$arguments=null){

        // Try to call Laravel helper functions
        if (function_exists($method)) {
            return call_user_func_array($method, $arguments);
        }        
        // Try to call Laravel facade methods
        if (class_exists('Illuminate\Support\Facades\URL')) {
            $facadeMap = [
                'url' => ['Illuminate\Support\Facades\URL', 'to'],
                'asset' => ['Illuminate\Support\Facades\URL', 'asset'],
                'route' => ['Illuminate\Support\Facades\URL', 'route'],
                'action' => ['Illuminate\Support\Facades\URL', 'action'],
                'secure' => ['Illuminate\Support\Facades\URL', 'secure'],
            ];
            
            if (isset($facadeMap[$method])) {
                return call_user_func_array($facadeMap[$method], $arguments);
            }
        }
        
        // Try to call other Laravel facades
        $facadeMethods = [
            'config' => ['Illuminate\Support\Facades\Config', 'get'],
            'trans' => ['Illuminate\Support\Facades\Lang', 'get'],
            'app' => ['Illuminate\Support\Facades\App', 'make'],
            'request' => ['Illuminate\Support\Facades\Request', 'instance'],
            'response' => ['Illuminate\Support\Facades\Response', 'make'],
            'redirect' => ['Illuminate\Support\Facades\Redirect', 'to'],
            'back' => ['Illuminate\Support\Facades\Redirect', 'back'],
            'session' => ['Illuminate\Support\Facades\Session', 'get'],
            'cookie' => ['Illuminate\Support\Facades\Cookie', 'get'],
            'cache' => ['Illuminate\Support\Facades\Cache', 'get'],
            'auth' => ['Illuminate\Support\Facades\Auth', 'user'],
            'user' => ['Illuminate\Support\Facades\Auth', 'user'],
            'guest' => ['Illuminate\Support\Facades\Auth', 'guest'],
            'check' => ['Illuminate\Support\Facades\Auth', 'check'],
            'attempt' => ['Illuminate\Support\Facades\Auth', 'attempt'],
            'login' => ['Illuminate\Support\Facades\Auth', 'login'],
            'logout' => ['Illuminate\Support\Facades\Auth', 'logout'],
            'hash' => ['Illuminate\Support\Facades\Hash', 'make'],
            'bcrypt' => ['Illuminate\Support\Facades\Hash', 'make'],
            'validator' => ['Illuminate\Support\Facades\Validator', 'make'],
            'validate' => ['Illuminate\Support\Facades\Validator', 'make'],
            'file' => ['Illuminate\Support\Facades\File', 'get'],
            'storage' => ['Illuminate\Support\Facades\Storage', 'disk'],
            'db' => ['Illuminate\Support\Facades\DB', 'table'],
        ];
        
        if (isset($facadeMethods[$method])) {
            $facadeClass = $facadeMethods[$method][0];
            $facadeMethod = $facadeMethods[$method][1];
            
            if (class_exists($facadeClass)) {
                return call_user_func_array([$facadeClass, $facadeMethod], $arguments);
            }
        }
        return null;
    }
    public static function isPost(){
        if(@$_SERVER["REQUEST_METHOD"]=="POST"){
            return true;
        }
        return false;
    }
    /**
     * @return LV_Route
     */
    public static function getCurrentRoute(){
        return LV_Route::getCurrentRoute();
    }
}
?>