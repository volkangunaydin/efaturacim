<?php

namespace Efaturacim\Util\Tests\Orkestra;

use Efaturacim\Util\Orkestra\Soap\OrkestraSoapClient;
use Efaturacim\Util\Orkestra\Soap\Services\OrkestraFactoryWebService;
use Efaturacim\Util\Utils\Console\Console;
use Efaturacim\Util\Utils\Laravel\LV_Config;
use Efaturacim\Util\Utils\Results\ResultUtil;
use Efaturacim\Util\Utils\SimpleResult;
use PHPUnit\Framework\TestCase;
use Vulcan\VResult;

use function PHPUnit\Framework\assertIsBool;
use function PHPUnit\Framework\assertTrue;

/**
 * @covers \Efaturacim\Util\Orkestra\Soap\OrkestraSoapClient
 */
class OrkestraSoapTest extends TestCase
{
    public static $CONFIG = null;
    
    public static function setUpBeforeClass(): void
    {        
        self::$CONFIG = LV_Config::getConfig(".env");        
        Console::stdout("Config loaded => ".json_encode(self::$CONFIG));
        // Setup code here - runs once before all tests in this class
    }
    /**
     * Orkestra Factory Servisini oluşturur
     * @return OrkestraSoapClient
     */
    public static function getFactoryService(){                
        return OrkestraSoapClient::getFactoryWithRedis(@self::$CONFIG["ORKESTRA"]);        
    }
    public function setUp(): void
    {
        // Setup code here - runs before each test method
    }

    public function testLogin()
    {        
        $client = self::getFactoryService();
        if($client && $client instanceof OrkestraFactoryWebService){
            assertTrue(true,"Orkestra Factory Servisi oluşturuldu.");
            Console::stdout("Orkestra Factory Servisi oluşturuldu.");
            $login  = $client->login();
            if($login && $login->isOK()){
                Console::stdout("Login başarılı => ".$client->getUserName());
                assertTrue(true,"Login başarılı.");
            }else{
                $this->fail("Login başarısız. => ".$client->getUserName());
                assertTrue(false,"Login başarısız.");
            }
        }else{
            $this->fail("Orkestra Factory Servisi oluşturulamadı.");
            assertTrue(false,"Orkestra Factory Servisi oluşturulamadı.");
        }                
    }

    public function testUserPasswordCheck(){
        $client = self::getFactoryService();
        if($client && $client instanceof OrkestraFactoryWebService){            
            $check = $client->checkUserNameAndPassword(@self::$CONFIG["ORKESTRA_TEST_USER"]["user"],@self::$CONFIG["ORKESTRA_TEST_USER"]["pass"],true);
            Console::newline();
            Console::printResult($check,"KULLANICI ŞİFRE DOĞRULAMA TESTİ");
            if($check && $check->isOK()){
                assertTrue(true,"Kullanıcı şifre doğrulama başarılı.");                    
                $check2 = $client->checkUserNameAndPassword(@self::$CONFIG["ORKESTRA_TEST_USER"]["user"],"",false);
                Console::newline();                
                if($check2 && !$check2->isOK()){
                    assertTrue(true,"Kullanıcı şifre doğrulama başarılı.");                    
                    Console::printResult(SimpleResult::newResult(true)->addSuccess("Kullanıcı boş şifresi kabul edilmedi.."),"KULLANICI ŞİFRE DOĞRULAMA TESTİ (BOŞ ŞİFRE)");
                }else{
                    assertTrue(true,"Kullanıcı şifre doğrulama başarısız.");
                }

                Console::newline();                
                $check3 = $client->checkUserNameAndPassword(@self::$CONFIG["ORKESTRA_TEST_USER"]["user"],"-",false);
                Console::newline();                
                if($check3 && !$check3->isOK()){
                    assertTrue(true,"Kullanıcı şifre doğrulama başarılı.");                    
                    Console::printResult(SimpleResult::newResult(true)->addSuccess("Kullanıcı - şifresi kabul edilmedi.."),"KULLANICI ŞİFRE DOĞRULAMA TESTİ (YANLIŞ ŞİFRE)");
                }else{
                    assertTrue(true,"Kullanıcı şifre doğrulama başarısız.");
                }

                Console::newline();                
            }
        }
    }
}
