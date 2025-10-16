<?php
namespace Efaturacim\Util\Utils\Debug;

use Efaturacim\B4B\SmartModels\Base\SmartModelUtil;
use Efaturacim\Util\Orkestra\Soap\OrkestraSoapClient;
use Efaturacim\Util\Utils\Html\Bootstrap\Alert;
use Efaturacim\Util\Utils\Laravel\LV;
use Efaturacim\Util\Utils\Laravel\LV_Route;
use Efaturacim\Util\Utils\Network\IpUtil;
use Efaturacim\Util\Utils\Results\ResultUtil;
use Efaturacim\Util\Utils\Sms\SmsAdapter;

class LocalDebug{
    public static function debug(){                
        $route = LV_Route::getCurrentRoute();        
        if(!$route->isGet()){
            die("GET haricinde diğer istek tipleri desteklenmiyor.");
        }else{                        
            if($route->getPart(1)=="url"){
                return self::handleUrl();
            }else if($route->getPart(1)=="upgrade"  || $route->getPart(1)=="database"){                
                return self::handleUpgrade();                
            }else if($route->getPart(1)=="fullupgrade"){
                return self::handleUpgrade(true);                
            }else{
                return self::handleDefault();
            }
        }                
    }
    public static function handleDefault(){
        return "";
    }
    public static function handleUrl(){
        
    }
    public static function handleUpgrade($createFiles=false){        
        $s = '';
        $s .= Alert::warning("B4B Veritabanı Upgrade İşlemi Başlıyor");
        $res = SmartModelUtil::doMigrationForLaravel(true,$createFiles,false,false);
        $s  .= ResultUtil::getResultMessagesAsHtml($res);
        return $s;
    }
}
?>
