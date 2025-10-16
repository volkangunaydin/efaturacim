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
            $login  = $client->login();
            if($login->isOK()){
                //$list = $client->newGetPageList("user")->addFields("reference","userName","name","surname","status");
                //$list->filterByRef("reference",array(1,2,3));
                //$list->filterByStringEquals("userName","admin");                
                //$res  = $list->getResult();
                //\Vulcan\V::dump($res);
                //$logout = $client->logout();
                \Vulcan\V::dump($login);
            }            
        }
        die("LocalDebug");
    }
}
?>
