<?php
namespace Efaturacim\Util\Utils\Url;

class UrlObject{
    public $url = null;
    public $newParams = [];
    public $excludeParams = [];
    public $parsedUrl = [];
    
    public function __construct($url=null, $newParams=null, $excludeParams=null){        
        if(is_null($url)){
            $this->url = @$_SERVER["REQUEST_URI"];
        }else{
            $this->url = $url;
        }        
        if(is_array($newParams)){
            $this->newParams = $newParams;
        }        
        if(is_array($excludeParams)){
            $this->excludeParams = $excludeParams;
        }
        
        $this->parseUrl();
    }
    
    /**
     * Parse the URL into components
     */
    private function parseUrl(){
        $this->parsedUrl = parse_url($this->url);
        
        // Parse query parameters
        if(isset($this->parsedUrl['query'])){
            parse_str($this->parsedUrl['query'], $this->parsedUrl['params']);
        }else{
            $this->parsedUrl['params'] = [];
        }
    }
    
    /**
     * Add a new parameter
     */
    public function addParam($key, $value){
        $this->newParams[$key] = $value;
        return $this;
    }
    
    /**
     * Add multiple parameters
     */
    public function addParams($params){
        if(is_array($params)){
            $this->newParams = array_merge($this->newParams, $params);
        }
        return $this;
    }
    
    /**
     * Exclude a parameter
     */
    public function excludeParam($key){
        $this->excludeParams[] = $key;
        return $this;
    }
    
    /**
     * Exclude multiple parameters
     */
    public function excludeParams($params){
        if(is_array($params)){
            $this->excludeParams = array_merge($this->excludeParams, $params);
        }
        return $this;
    }
    
    /**
     * Get current parameters (excluding excluded ones)
     */
    public function getCurrentParams(){
        $params = $this->parsedUrl['params'];
        
        // Remove excluded parameters
        foreach($this->excludeParams as $excludeKey){
            if(isset($params[$excludeKey])){
                unset($params[$excludeKey]);
            }
        }
        
        return $params;
    }
    
    /**
     * Build the final URL with merged parameters
     */
    public function buildUrl(){
        // Get current parameters (excluding excluded ones)
        $params = $this->getCurrentParams();
        
        // Merge with new parameters (new params override existing ones)
        $finalParams = array_merge($params, $this->newParams);
        
        // Build the base URL
        $url = '';
        
        if(isset($this->parsedUrl['scheme'])){
            $url .= $this->parsedUrl['scheme'] . '://';
        }
        
        if(isset($this->parsedUrl['user'])){
            $url .= $this->parsedUrl['user'];
            if(isset($this->parsedUrl['pass'])){
                $url .= ':' . $this->parsedUrl['pass'];
            }
            $url .= '@';
        }
        
        if(isset($this->parsedUrl['host'])){
            $url .= $this->parsedUrl['host'];
        }
        
        if(isset($this->parsedUrl['port'])){
            $url .= ':' . $this->parsedUrl['port'];
        }
        
        if(isset($this->parsedUrl['path'])){
            $url .= $this->parsedUrl['path'];
        }
        
        // Add query string if there are parameters
        if(!empty($finalParams)){
            $url .= '?' . http_build_query($finalParams);
        }
        
        if(isset($this->parsedUrl['fragment'])){
            $url .= '#' . $this->parsedUrl['fragment'];
        }
        
        return $url;
    }
    
    public function __toString(){
        return $this->toString();
    }
    
    public function toUrlString(){
        return $this->toString();
    }
    
    public function toString(){
        return $this->buildUrl();
    }
    
    /**
     * Get the parsed URL components
     */
    public function getParsedUrl(){
        return $this->parsedUrl;
    }
    
    /**
     * Get the current URL without any modifications
     */
    public function getOriginalUrl(){
        return $this->url;
    }
}
?>