<?php
namespace Efaturacim\Util\Utils\Debug;

use Efaturacim\Util\Utils\Laravel\LV;
use Efaturacim\Util\Utils\Network\IpUtil;
use Efaturacim\Util\Utils\Sms\SmsAdapter;

class LocalDebug{
    public static function debug(){
        if(IpUtil::isLocalhostOrDev()){
            $db = LV::getDB()->selectSingle("SELECT * FROM b4b_users WHERE reference>0");
            dd($db);
        }
        die("LocalDebug");
    }
}
?>
