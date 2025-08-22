<?php
namespace Efaturacim\Util\Utils\Url;

use Efaturacim\Util\Utils\Array\AssocArray;

class CurrentUrl{
   protected static $urlParts = [];
   protected static $urlGetParams = [];   
   protected static $urlFullPath = '';
   protected static $folders = [];
   
   protected static function initIfNot(){
        if(count(self::$urlParts) === 0){
            if (!isset($_SERVER)) {
                self::$urlParts = [
                    'scheme' => 'http',
                    'host' => 'localhost',
                    'port' => '',
                    'path' => '/',
                    'query' => '',
                    'fragment' => '',
                    'basePath' => '/',
                    'fullUrl' => 'http://localhost/'
                ];
                return;
            }
            
            // Detect protocol with proxy support (like UrlUtil::isSsl)
            $protocol = self::detectProtocol();
            
            // Get host with proxy support (like UrlUtil::getBaseHost)
            $host = self::detectHost();
            
            $port = $_SERVER['SERVER_PORT'] ?? '';
            $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
            
            $fullUrl = $protocol . '://' . $host;
            if ($port && $port != '80' && $port != '443') {
                $fullUrl .= ':' . $port;
            }
            $fullUrl .= $requestUri;
            
            $parsedUrl = parse_url($fullUrl);
            
            self::$urlParts = [
                'scheme' => $parsedUrl['scheme'] ?? $protocol,
                'host' => $parsedUrl['host'] ?? $host,
                'port' => $parsedUrl['port'] ?? $port,
                'path' => $parsedUrl['path'] ?? '/',
                'query' => $parsedUrl['query'] ?? '',
                'fragment' => $parsedUrl['fragment'] ?? '',
                'basePath' => dirname($parsedUrl['path'] ?? '/'),
                'fullUrl' => $fullUrl
            ];
            
                         $path = $parsedUrl['path'] ?? '/';
             $folders = explode('/', trim($path, '/'));
             $folderIndex = 1;
             self::$folders = []; // Reset folders array
             foreach ($folders as $folder) {
                 if (!empty($folder)) {
                     self::$urlParts['folder' . $folderIndex] = $folder;
                     self::$folders[] = $folder; // Store in folders array
                     $folderIndex++;
                 }
             }
            
            if (!empty($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], self::$urlGetParams);
            }
            
            self::$urlFullPath = $parsedUrl['path'] ?? '/';
        } 
   }
   
   /**
    * Detect protocol with proxy support (similar to UrlUtil::isSsl)
    */
   protected static function detectProtocol() {
        if ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||  
            isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1) ||
            isset($_SERVER['HTTPS_PROXY']) && $_SERVER['HTTPS_PROXY'] === 'on') {
            return 'https';
        }
        return 'http';
   }
   
   /**
    * Detect host with proxy support (similar to UrlUtil::getBaseHost)
    */
   protected static function detectHost() {
        if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            // The proxy has provided the original host requested by the client.
            return $_SERVER['HTTP_X_FORWARDED_HOST'];
        } elseif (isset($_SERVER['HTTP_HOST'])) {
            // Standard host check (for non-proxied setups).
            return $_SERVER['HTTP_HOST'];
        } else {
            // Fallback if HTTP_HOST isn't set.
            $host = $_SERVER['SERVER_NAME'] ?? 'localhost';
            $port = $_SERVER['SERVER_PORT'] ?? '';
            $isSsl = self::detectProtocol() === 'https';
            $isStandardPort = (!$isSsl && $port == 80) || ($isSsl && $port == 443);
            
            if (!$isStandardPort && $port) {
                $host .= ':' . $port;
            }
            return $host;
        }
   }
   
   public static function getBasePath(){
        self::initIfNot();  
        return self::$urlParts["basePath"];
   }
   
   public static function getFolderIndex($index=1){
        self::initIfNot();  
        return @self::$urlParts["folder".$index];
   }
   
   public static function getParam($nameOrNames,$default=null,$type="string"){
        self::initIfNot();  
        return AssocArray::getVal(self::$urlGetParams,$nameOrNames,$default,$type);
   }
       public static function getFolders(){
         self::initIfNot();
         return self::$folders;
    }
}

?>  