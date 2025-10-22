<?php
namespace Efaturacim\Util\Utils\Debug;

use Efaturacim\B4B\Models\Mutabakat\MutabakatDonemi;
use Efaturacim\B4B\Models\User\B4B_User;
use Efaturacim\B4B\SmartModels\Base\SmartModelUtil;
use Efaturacim\Util\Orkestra\Soap\OrkestraSoapClient;
use Efaturacim\Util\Utils\Html\Bootstrap\Alert;
use Efaturacim\Util\Utils\Laravel\LV;
use Efaturacim\Util\Utils\Laravel\LV_Route;
use Efaturacim\Util\Utils\Network\IpUtil;
use Efaturacim\Util\Utils\Results\ResultUtil;
use Efaturacim\Util\Utils\Sms\SmsAdapter;
use Vulcan\Orkestra\SmartClient\OrkestraSmartClient;
use Vulcan\Projects\Orkestra\DbSelect\Cari\SelectCari;

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
            }else if($route->getPart(1)=="orkestra"){
                return self::handleOrkestra();
            }else{
                return self::handleDefault();
            }            
        }                
    }
    public static function handleTest(){
        $s = Alert::warning("B4B Test İşlemi Başlıyor");
        $mutabakatDonemi = MutabakatDonemi::find(1);        
        $s .= Alert::success("Dönemdeki Toplam Kayıt Sayısı: ".$mutabakatDonemi->toplamKayit." [ Kabul: ".$mutabakatDonemi->kabulSayisi." Red: ".$mutabakatDonemi->redSayisi."  Cevaplanmamis : ".$mutabakatDonemi->cevaplanmamisSayisi."]");

        $user = B4B_User::find(1);
        $s .= Alert::success("Kullanıcı: ".$user->displayName."<br/>Admin: ".($user->isAdmin?"Evet":"Hayır"));

        //LV::throwException("Test Exception");
        return $s;
    }
    public static function handleDefault(){
        return "";
    }
    public static function handleUrl(){
        
    }
    public static function handleUpgrade($createFiles=false,$initData=false){        
        $s = '';
        $s   .= Alert::warning("B4B Veritabanı Upgrade İşlemi Başlıyor");
        $res = SmartModelUtil::doMigrationForLaravel(true,$createFiles,$initData,false);
        $s  .= ResultUtil::getResultMessagesAsHtml($res);
        return $s;
    }
    public static function handleOrkestra(){
        $s = Alert::warning("Orkestra Test İşlemi Başlıyor");
        $smartClient = LV::getSmartClientForOrkestra();        
        if($smartClient instanceof OrkestraSmartClient){                        
            $cariQuery = SelectCari::newObject($smartClient,"mutabakat");
            // $cariQuery->filterByTedarikci();  $cariQuery->filterByMusteri();
            $cariQuery->filterByTarihtekiBorc("2025-05-31",1000,1000000,1000,10000000);
            $cariQuery->filterBySmartCode("h");
            //$cariQuery->selectFieldsByTarihtekiBakiye("2025-05-31",false);
            $cariQuery->debug(false,100);
        }else{
            $s .= Alert::danger("Orkestra Veritabanı Bağlantısı Başarısız");
        }        
        return $s;
    }
}
?>
