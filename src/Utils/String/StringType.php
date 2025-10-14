<?php
namespace Efaturacim\Util\Utils\String;

use Efaturacim\Util\Utils\Array\ArrayIn;
use Efaturacim\Util\Utils\Debug\DebugUtil;
use Efaturacim\Util\Utils\Url\UrlUtil;

class StringType{
    public static $TYPE_STRING = "string";
    public static $TYPE_XML    = "xml";
    public static $TYPE_JSON   = "json";
    public static $TYPE_HTML   = "html";
    public static $TYPE_JS     = "javascript";
    public static $TYPE_BASH   = "bash";
    public static function getStringType($str,$useExtendedSearch=false){
        if($useExtendedSearch){
            return self::getExtendedStringType($str);
        }            
        if(self::isJson($str)){                                
            return self::$TYPE_JSON;
        }else if(self::isHtml($str)){
            return self::$TYPE_HTML;
        }else if(self::isXml($str)){
            return self::$TYPE_XML;
        }
        return self::$TYPE_STRING;
    }
    public static function getExtendedStringType($str){
        if(self::isEmail($str)){
            return "email";
        }else if(self::isPhone($str)){
            return "phone";
        }else if(self::isUrl($str)){
            return "url";
        }else{
            DebugUtil::dump($str);
        }
        return self::$TYPE_STRING;
    }
    public static function getHtmlTags(){
        return array("a","abbr","acronym","address","applet","area","article","aside","audio","b","base","basefont","bdi","bdo","bgsound","big","blockquote","blink","body","br","button","canvas","caption","center","cite","code","col","colgroup","datalist","dd","del","details","dfn","dialog","dir","div","dl","dt","em","embed","fieldset","figcaption","figure","font","footer","form","frame","frameset","h1","h2","h3","h4","h5","h6","head","header","hr","html","i","iframe","img","input","ins","kbd","keygen","label","legend","li","link","main","map","mark","menu","menuitem","meta","meter","nav","nobr","noframes","noscript","object","ol","optgroup","option","output","p","param","picture","pre","progress","q","rp","rt","ruby","s","samp","script","section","select","small","source","span","strike","strong","style","sub","summary","sup","table","tbody","td","textarea","tfoot","th","thead","time","title","tr","track","tt","u","ul","var","video");
    }
    public static function isJson($string,$softCheck=false) {
        $string = trim("".$string);
        if(substr($string,0,1)=="[" && substr($string,-1,1)=="]"){
            return true;                
        }else if(substr($string,0,1)=="{" && substr($string,-1,1)=="}"){
            return true;
        }
        $arr = @json_decode($string,true);
        $err = json_last_error();
        if($err === JSON_ERROR_NONE){
            return true;
        }
        return false;
    }
    public static function isHtml($htmlStr) {
        $htmlStr = trim("".$htmlStr);
        $htmlStr = '<div class="className" id="va_56">
        <div class="newclName">              
            <div class="another">
            <a href="/va56/md"><img src="http://imageshack.us/someimage.jpg" id="name_56" /></a>
            </div>
            <p><a href="/va56/md">md</a></p>
                        
            <p class="ptext">
            <span class="de">
                <span class="done">(5369 max)</span>
                Some text: 82%
            </span>
            </p>
        </div>
        </div>
            

        <div class="className" id="va_57">
        <div class="newclName">              
            <div class="another">
            <a href="/va57/md"><img src="http://imageshack.us/someimage2.jpg" id="name_57" /></a>
            </div>
            <p><a href="/va57/md">md</a></p>
                        
            <p class="ptext">
            <span class="de">
                <span class="done">(469 max)</span>
                Some text: 50%
            </span>
            </p>
        </div>
        </div>';
        if (stripos($htmlStr, '<!DOCTYPE html>') !== false) {
            return true;
        }
        if (stripos($htmlStr, '<html>') !== false) {
            return true;
        }
        $regex = "/<\/?\w+((\s+(\w|\w[\w-]*\w)(\s*=\s*(?:\".*?\"|'.*?'|[^'\">\s]+))?)+\s*|\s*)\/?>/i";$matches = null;
        preg_match_all($regex,$htmlStr,$matches);
        $tags = array();
        if($matches &&  is_array($matches) && key_exists(0,$matches)){
            foreach($matches[0] as $line){
                $m=null;
                preg_match_all("/<([^\s>]+)(\s|>)+/",$line,$m);
                if($m && is_array($m) && key_exists(1,$m)){                        
                    $tagName = strtolower(str_replace("/","",@$m[1][0]));
                    $tags[$tagName] = true;
                }                    
            }
        }            
        if(count($tags)>0 && ArrayIn::isArrayInArray(self::getHtmlTags(),$tags)){
            return true;
        }            
        if(substr($htmlStr,0,1)!="<" && preg_match("/<[^<]+>/", $htmlStr, $m) != 0){
            return true;
        }
        try {
            $xml   = @simplexml_load_string($htmlStr, "SimpleXMLElement", LIBXML_NOCDATA);
            $json  = @json_encode($xml);
            $array = @json_decode($json,TRUE);
            if($array && is_array($array) && (key_exists("a",$array) || key_exists("p",$array) || key_exists("div",$array) || key_exists("b",$array)) ){
                return true;    
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return false;
    }
    public static function isXml($xmlstr) {
        libxml_use_internal_errors(true);
        @simplexml_load_string($xmlstr);
        $errors = libxml_get_errors();          
        libxml_clear_errors();  
        return empty($errors);
    }
    public static function isBase64($str,$validate=false) {
        $a = (bool) preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $str);
        if($a){
            if($validate){
                return base64_encode(base64_decode($str, true)) === $str;
            }
        }
        return $a;
    }
    public static function isEmail($str) {
        return StrEMail::isValid($str);
    }
    public static function isPhone($str) {
        return StrPhone::isValid($str);
    }        
    public static function isUrl($str) {
        return UrlUtil::isValid($str);
    }
    public static function isEMailDomain($domain_name) {
        return substr("".$domain_name, 0,1)=="@" && self::isDomain(substr("".$domain_name, 1));
    }
    public static function isDomain($domain_name) {
        // Check if the domain name is empty
        if (empty($domain_name)) {
            return false;
        }
        
        // Check if the domain name contains only valid characters
        if (!preg_match("/^[a-z0-9-.]+$/i", $domain_name)) {
            return false;
        }
        
        // Check if the domain name starts or ends with a hyphen
        if (strpos($domain_name, '-') === 0 || substr($domain_name, -1) === '-') {
            return false;
        }
        
        // Check if the domain name contains two or more consecutive hyphens
        if (preg_match("/--/", $domain_name)) {
            return false;
        }
        
        // Check if the domain name contains more than 63 characters in a label
        if (preg_match("/^[a-z0-9-]{64,}$/i", $domain_name)) {
            return false;
        }
        
        // Check if the domain name contains more than 253 characters in total
        if (strlen($domain_name) > 253) {
            return false;
        }
        
        // Check if the domain name ends with a top-level domain
        if (!preg_match("/\.[a-z]{2,}$/i", $domain_name)) {
            return false;
        }
        
        // Return true if all checks pass
        return true;
    }
    public static function isIp($ip_address) {
        if (empty($ip_address)) {
            return false;
        }
        // Check if the IP address is in the correct format
        if (!preg_match("/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/", $ip_address)) {
            return false;
        }
        
        // Check if each octet is within the valid range (0-255)
        $octets = explode(".", $ip_address);
        foreach ($octets as $octet) {
            if ((int)$octet < 0 || (int)$octet > 255) {
                return false;
            }
        }
        // Return true if all checks pass
        return true;        
    }
}

?>