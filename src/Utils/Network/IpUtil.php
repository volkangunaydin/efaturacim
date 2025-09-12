<?php
namespace Efaturacim\Util\Utils\Network;

class IpUtil{
    public static function getIp(){
        return $_SERVER['REMOTE_ADDR'];
    }    
    public static function isLocalhostOrDev(){
        $ip    = "".@$_SERVER['REMOTE_ADDR'];
        $host  = "".@$_SERVER["HTTP_HOST"];
        if($ip=="::1" && $host=="localhost"){
            return true;    
        }else if(strlen("".$host)>14 && substr($host, -14)===".localhost.com"){
            return true;
        }else if(strlen("".$host)>5 && substr($host, -5)===".test"){
            return true;
        }
        return false;
}
}
?>