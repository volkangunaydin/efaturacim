<?php
namespace Efaturacim\Util\Utils\Network;

class IpUtil{
    public static function getIp(){
        return $_SERVER['REMOTE_ADDR'];
    }    
}
?>