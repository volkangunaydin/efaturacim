<?php
namespace Efaturacim\Util\Utils\Debug;

use Efaturacim\Util\Orkestra\Soap\OrkestraSoapClient;
use Efaturacim\Util\Utils\Laravel\LV;
use Efaturacim\Util\Utils\Network\IpUtil;
use Efaturacim\Util\Utils\Sms\SmsAdapter;

class LocalDebug{
    public static function debug(){        
        if(IpUtil::isLocalhostOrDev()){
            $client = OrkestraSoapClient::getFactoryWithRedis();
            $res  = $client->checkUserNameAndPassword("volkan","deneme");
            \Vulcan\V::dump($res);
        }
        die("LocalDebug");
    }
}
?>
