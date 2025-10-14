<?php
namespace Efaturacim\Util\Utils\Debug;

use Efaturacim\Util\Utils\Network\IpUtil;
use Efaturacim\Util\Utils\Sms\SmsAdapter;

class LocalDebug{
    public static function debug(){
        if(IpUtil::isLocalhostOrDev()){
            $sms = SmsAdapter::newAdapter();
            $r = $sms->send("Test SMS @ ".date("Y-m-d H:i:s"),"5355554979");
            dd($r);
        }
        die("LocalDebug");
    }
}
?>
