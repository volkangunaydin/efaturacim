<?php
namespace Efaturacim\Util\Utils\Laravel;

use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\Options;

class LV_Route{
    public static $currentRoute = null;
    /**
     * @var Options
     */
    public $getParams = null;
    /**
     * @var Options
     */
    public $postParams = null;
    /**
     * @var Options
     */
    public $requestParams = null;
    public $scheme = null;
    public $host = null;
    public $port = null;
    public $path = null;
    public $query = null;
    public $fragment = null;
    public $user = null;
    public $pass = null;
    public $pathParams = null;
    public $scriptName =  null;
    public $folders = [];
    public $reqMethod = null;
    /**
     * Get the current route
     * @return static     
    */
    public static function getCurrentRoute(){
        if(is_null(self::$currentRoute)){
            self::$currentRoute = new static();
        }
        return self::$currentRoute;
    }
    public function __construct(){
        $this->getParams  = new Options($_GET);
        $this->postParams = new Options($_POST);
        $this->requestParams = new Options($_REQUEST);  
        $this->scheme = $_SERVER['REQUEST_SCHEME'] ?? null;
        $this->host = $_SERVER['HTTP_HOST'] ?? null;
        $this->port = $_SERVER['SERVER_PORT'] ?? null;
        $this->path = $_SERVER['REQUEST_URI'] ?? null;
        $this->query = $_SERVER['QUERY_STRING'] ?? null;
        $this->fragment = $_SERVER['FRAGMENT_ID'] ?? null;
        $this->user = $_SERVER['PHP_AUTH_USER'] ?? null;
        $this->pass = $_SERVER['PHP_AUTH_PW'] ?? null;
        $this->pathParams = $_SERVER['PATH_INFO'] ?? null;
        $this->reqMethod = @$_SERVER['REQUEST_METHOD'] ?? null;
        $pathParts = explode('/', trim($this->path, '/'));        
        $i=0;
        foreach($pathParts as $pathPart){
            $i++;
            if($i==count($pathParts)){
                $p1 = strpos($pathPart,"?");
                if($p1!==false && $p1>=0){
                    if($p1==0){
                        $this->scriptName = "";
                    }else{                        
                        $this->scriptName = substr($pathPart,0,$p1);
                        $ext = pathinfo($this->scriptName,PATHINFO_EXTENSION);
                        if(is_null($ext) || strlen("".$ext)==0){
                            $this->folders[] = $this->scriptName;            
                        }
                    }                    
                }else{
                    $this->scriptName = substr($pathPart,0,$p1);
                    $ext = pathinfo($this->scriptName,PATHINFO_EXTENSION);
                    if(is_null($ext) || strlen("".$ext)==0){
                        $this->folders[] = $pathPart;
                    }                    
                }
            }else if(strlen($pathPart)>0){
                $this->folders[] = $pathPart;
            }            
        }
    }    
    public function getScriptName(){
        return $this->scriptName;
    }
    public function getFolders(){
        return $this->folders;
    }
    public function getPart($index,$default=null,$type=null){
        if(isset($this->folders[$index])){
            if(is_null($type)){
                return $this->folders[$index];
            }else{
                return CastUtil::getAs($this->folders[$index],$default,$type);
            }
        }else{
            return $default;
        }
    }
    public function isPost(){
        return $this->reqMethod == "POST";
    }
    public function isGet(){
        return $this->reqMethod == "GET";
    }
    public function isPut(){
        return $this->reqMethod == "PUT";
    }
    public function isDelete(){
        return $this->reqMethod == "DELETE";
    }
    public function isPatch(){
        return $this->reqMethod == "PATCH";
    }
    public function getDebugArray(){
        return [
            "isPost()" => $this->isPost(),
            "isGet()" => $this->isGet(),
            "isPut()" => $this->isPut(),
            "isDelete()" => $this->isDelete(),
            "isPatch()" => $this->isPatch(),
            "scriptName" => $this->scriptName,
            "folders" => $this->folders,
            "getParams" => $this->getParams->params,
            "postParams" => $this->postParams->params,
            "requestParams" => $this->requestParams->params,
            "scheme" => $this->scheme,
            "host" => $this->host,
            "port" => $this->port,
            "path" => $this->path,
            "query" => $this->query,
            "fragment" => $this->fragment,
            "user" => $this->user,
            "pass" => $this->pass,
            "pathParams" => $this->pathParams,
        ];
    }
}
?>