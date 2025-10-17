<?php
namespace Efaturacim\Util\Utils\Debug;

use Efaturacim\B4B\Models\Mutabakat\MutabakatDonemi;
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
            }else if($route->getPart(1)=="init"){
                return self::handleUpgrade(false,true);
            }else if($route->getPart(1)=="test"){
                return self::handleTest();
            }else{
                return self::handleDefault();
            }
        }                
    }
    public static function handleTest(){
        $s = Alert::warning("B4B Test İşlemi Başlıyor");
        $mutabakatDonemi = MutabakatDonemi::find(1);
        $totalRecords = $mutabakatDonemi->toplamKayit; 
        $s .= Alert::success("Toplam Kayıt: ".$totalRecords);
        return $s;
    }
    public static function handleDefault(){
        return "";
    }
    public static function handleUrl(){
        
    }
    public static function handleUpgrade($createFiles=false,$initData=false){        
        $s = '';
        $s .= Alert::warning("B4B Veritabanı Upgrade İşlemi Başlıyor");
        $res = SmartModelUtil::doMigrationForLaravel(true,$createFiles,$initData,false);
        $s  .= ResultUtil::getResultMessagesAsHtml($res);
        return $s;
    }
}
?>
