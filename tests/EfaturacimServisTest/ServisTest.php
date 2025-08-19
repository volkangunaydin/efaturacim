<?php

namespace Tests\EfaturacimServisTest;

use Efaturacim\Util\Utils\Array\AssocArray;
use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\Console\Console;
use Efaturacim\Util\Utils\IO\IO_Util;
use Efaturacim\Util\Utils\RestApiClient;
use Efaturacim\Util\Utils\SecurityUtil;
use PHPUnit\Framework\TestCase;

class ServisTest extends TestCase
{
    public $options = array();  
    

    
    /**
     * Test to check if curl functions are available
     */
    public function testCurlFunctionsAvailable()
    {
        // Check if curl extension is loaded
        $this->assertTrue(extension_loaded('curl'), 'cURL extension should be loaded');
        
        // Check if basic curl functions exist
        $this->assertTrue(function_exists('curl_init'), 'curl_init function should be available');
        $this->assertTrue(function_exists('curl_setopt'), 'curl_setopt function should be available');
        $this->assertTrue(function_exists('curl_exec'), 'curl_exec function should be available');
        $this->assertTrue(function_exists('curl_close'), 'curl_close function should be available');
        $this->assertTrue(function_exists('curl_error'), 'curl_error function should be available');
        $this->assertTrue(function_exists('curl_getinfo'), 'curl_getinfo function should be available');
        
        // Test if we can actually initialize a curl handle
        $ch = curl_init();
        if($ch){
            $this->assertTrue(true, 'curl_init success');
        }
        
    }
    
    public function ensureServisInit(){                
        $path = IO_Util::getSafePath(__FILE__,true) . '../../private/test.ini';                
        if(file_exists($path)){
            $this->options = parse_ini_file($path);
            return true;
        }
        return false;
    }
    public function testServis(){        
        if($this->ensureServisInit()){
            $this->assertTrue(true, 'Servis initialized');
            $baseUrl = AssocArray::getVal($this->options,"url");
            $user     = AssocArray::getVal($this->options,"user");
            $customer = AssocArray::getVal($this->options,"customer");
            $pass     = AssocArray::getVal($this->options,"pass");

            Console::print('');
            Console::print('=== ServisTest : '.$baseUrl.' ===', 'cyan');
            Console::print('User: '.$user, 'cyan');
            Console::print('Customer: '.$customer, 'cyan');
            Console::print('Pass: '.str_repeat("*",strlen($pass)), 'cyan');
            Console::print('=====================================================', 'cyan');
            $r = RestApiClient::getLogin($baseUrl,"EFaturacim/Login",array("customer"=>$customer,"user"=>$user,"pass"=>$pass,"clientInfo"=>@$_SERVER["HTTP_USER_AGENT"],"clientSecret"=>SecurityUtil::getClientKey(),"ip"=>"127.0.0.1"));                    
            $firmaRef = 0;
            foreach($r->lines as $k=>$v){
                $firmaRef = 0 + @$v["ref"];
                break;
             }                        
            $bearer = $r->getAttribute("bearer");
            if($r->isOK() && strlen("".$bearer)>0 && $firmaRef>0){
                Console::success('Bearer:'.$bearer, 'Login successful');
                Console::success('Firma Ref:'.$firmaRef, 'Firma Secimi');
                
                $r = RestApiClient::getJsonResult($baseUrl,"EFaturacim/Status",array("bearer"=>$bearer));
                Console::printResult($r,"Status");
                $this->assertTrue($r->isOK(), 'Status');

                $r = RestApiClient::getJsonResult($baseUrl,"EFaturacim/FirmaBilgilerim/SifreKontrol",array("bearer"=>$bearer,"firma"=>$firmaRef,"sifre"=>$pass));                
                Console::printResult($r,"Sifre kontrol");
                

                $r = RestApiClient::getJsonResult($baseUrl,"EFaturacim/FirmaBilgilerim",array("bearer"=>$bearer,"firma"=>$firmaRef));                
                $this->assertTrue($r->isOK(), 'Firma Bilgileri');
                Console::printResult($r,"Firma Bilgileri");
                if($r->isOK()){
                    $attr = AssocArray::getVal($r->value,"attributes",array(),CastUtil::$DATA_ARRAY);
                    if(AssocArray::getVal($attr,"firma_ref",0,"int")==$firmaRef){
                        $this->assertTrue(true, 'Firma ref kontrolu başarılı');
                        Console::printSuccess('Firma ref kontrolu başarılı');
                    }
                }                
                
                $r = RestApiClient::getJsonResult($baseUrl,"EFaturacim/FirmaBilgilerim/SifreDegistir",array("bearer"=>$bearer,"firma"=>$firmaRef,"yeni_sifre"=>$pass,"eski_sifre"=>$pass));                
                $this->assertTrue($r->isOK(), 'Şifre Değişikliği');
                Console::printResult($r,"Şifre Değişikliği");

                $r = RestApiClient::getJsonResult($baseUrl,"EFaturacim/Kullanici/HesapKurtarmaBilgileri",array("bearer"=>$bearer));                
                Console::printResult($r,"HesapKurtarmaBilgileri");
                $this->assertTrue($r->isOK(), 'HesapKurtarmaBilgileri');
                $user_reference = AssocArray::getVal($r->attributes,"user_reference",0,CastUtil::$DATA_INT);             
                $this->assertTrue($user_reference>0, 'User reference kontrolu başarılı');


                // XSLT LISTESI
                $r = RestApiClient::getJsonResult($baseUrl,"EFaturacim/FirmaBilgilerim/XsltListesi",array("bearer"=>$bearer,"firma"=>$firmaRef));                
                Console::printResult($r,"XSLT Listesi : ".count($r->lines)." dosya bulundu");
                $this->assertTrue($r->isOK(), 'XSLT Listesi - '.count($r->lines)." dosya bulundu");
                if($r->isOK()){
                    $xsltRef = @$r->lines[0]["ref"];
                    if($xsltRef && $xsltRef>0){
                        Console::printSuccess('XSLT okunuyor : '.$xsltRef,"ok");
                        $r = RestApiClient::getJsonResult($baseUrl,"EFaturacim/FirmaBilgilerim/XsltListesi",array("bearer"=>$bearer,"firma"=>$firmaRef,"xslt_ref"=>$xsltRef));                
                        Console::printResult($r,"XSLT Okuma => Ref:".$xsltRef);
                        $this->assertTrue($r->isOK(), 'XSLT Okuma => Ref:'.$xsltRef);
                    }
                }

                $r = RestApiClient::getJsonResult($baseUrl,"EFaturacim/FirmaBilgilerim/AyarDegistir",array("bearer"=>$bearer,"firma"=>$firmaRef,"tip"=>"kullanici","il" => "ANKARA - 06","ilce" => "ÇANKAYA - 06010"));                
                Console::printResult($r,"Ayar Degistir");
                $this->assertTrue($r->isOK(), 'Ayar Degistir');
               

            }else{
                Console::error('Login failed', 'Login failed', '✗', 80);
            }
            Console::info('End of ServisTest');
        }else{
            $this->assertTrue(true, 'Servis initialized skipped since no test.ini file found');
        }
    }    
}
?>