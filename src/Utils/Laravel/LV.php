<?php
namespace Efaturacim\Util\Utils\Laravel;

use Exception;
use Vulcan\Base\Database\DatabaseConnection;
use Vulcan\Base\Database\MySQL\MySqlDbClient;
use Vulcan\Orkestra\SmartClient\OrkestraSmartClient;

class LV{
    protected static $__isLaravel = null;
    protected static $__db = array();
    /**
     * @var OrkestraSmartClient
     */
    protected static $__smartClient = null;
    protected static $__isVEnabled = false;
    public static function env($name,$default=null){
        return \env($name,$default);
    }
    public static function isLaravel(){
        if(!is_null(self::$__isLaravel) && is_bool(self::$__isLaravel)){ return self::$__isLaravel; }
        self::$__isLaravel = self::__checkIfIsLaravel();
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
    
    /**
     * Check if current directory is a Laravel project
     */
    public static function isLaravelProject($path = null){
        if ($path === null) {
            $path = getcwd();
        }
        
        // Check for artisan file (Laravel's command-line interface)
        if (!file_exists($path . '/artisan')) {
            return false;
        }
        
        // Check composer.json for Laravel framework dependency
        $composerFile = $path . '/composer.json';
        if (file_exists($composerFile)) {
            $composer = json_decode(file_get_contents($composerFile), true);
            if (isset($composer['require']['laravel/framework'])) {
                return true;
            }
        }
        
        // Check for bootstrap/app.php (Laravel 11+)
        if (file_exists($path . '/bootstrap/app.php')) {
            return true;
        }
        
        // Check for app/ directory and typical Laravel structure
        if (file_exists($path . '/app') && 
            file_exists($path . '/config') && 
            file_exists($path . '/routes')) {
            return true;
        }
        
        return false;
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
}
?>