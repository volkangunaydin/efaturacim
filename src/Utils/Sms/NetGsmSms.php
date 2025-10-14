<?php
namespace Efaturacim\Util\Utils\Sms;

use Efaturacim\Util\Utils\Equality\CompareUtil;
use Efaturacim\Util\Utils\Network\BrowserUtil;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StringSplitter;
use Efaturacim\Util\Utils\String\StrParse;
use Efaturacim\Util\Utils\String\StrPhone;

class NetGsmSms extends SmsAdapter{
        /**
         * 00	Görevinizin tarih formatinda bir hata olmadığını gösterir.
         * 01	Mesaj gönderim baslangıç tarihinde hata var. Sistem tarihi ile değiştirilip işleme alındı.
         * 02	Mesaj gönderim sonlandırılma tarihinde hata var.Sistem tarihi ile değiştirilip işleme alındı.Bitiş tarihi başlangıç tarihinden küçük girilmiş ise, sistem bitiş tarihine içinde bulunduğu tarihe 24 saat ekler.
         * 347022009 Gönderdiğiniz SMS'inizin başarıyla sistemimize ulaştığını gösterir. Bu görevid niz ile mesajınızın durumunu sorguyabilirsiniz.
         * 00 5Fxxxxxx-2xxx-4xxE-8xxx-8A5xxxxxxxxxxxx   Gönderdiğiniz SMS'inizin başarıyla sistemimize ulaştığını gösterir. Bu görev(bulkid) sorgulanabilir, Raporlama servisinde bulkID bilgisi olarak 5Fxxxxxx-2xxx-4xxE-8xxx-8A5xxxxxxxxxxxx verilebilir. Bu outputu almanızın sebebi, 5 dakika boyunca ard arda gönderdiğiniz SMS'lerin sistemimiz tarafında çoklanarak (biriktirilerek) 1 dakika içerisinde gönderileceği anlamına gelir.
         * 
         *  20	Mesaj metninde ki problemden dolayı gönderilemediğini veya standart maksimum mesaj karakter sayısını geçtiğini ifade eder. (Standart maksimum karakter sayısı 917 dir. Eğer mesajınız türkçe karakter içeriyorsa Türkçe Karakter Hesaplama menüsunden karakter sayılarının hesaplanış şeklini görebilirsiniz.)
         *  30	Geçersiz kullanıcı adı , şifre veya kullanıcınızın API erişim izninin olmadığını gösterir.Ayrıca eğer API erişiminizde IP sınırlaması yaptıysanız ve sınırladığınız ip dışında gönderim sağlıyorsanız 30 hata kodunu alırsınız. API erişim izninizi veya IP sınırlamanızı , web arayüzden; sağ üst köşede bulunan ayarlar> API işlemleri menüsunden kontrol edebilirsiniz.
         *  40	Mesaj başlığınızın (gönderici adınızın) sistemde tanımlı olmadığını ifade eder. Gönderici adlarınızı API ile sorgulayarak kontrol edebilirsiniz.
         *  50	Abone hesabınız ile İYS kontrollü gönderimler yapılamamaktadır.
         *  51	Aboneliğinize tanımlı İYS Marka bilgisi bulunamadığını ifade eder.
         *  70	Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder.
         *  85	Mükerrer Gönderim sınır aşımı. Aynı numaraya 1 dakika içerisinde 20'den fazla görev oluşturulamaz.
         * {@inheritDoc}
         * @see \Vulcan\Projects\SMS\SmsAdapter::initMe()
         */
        protected function initMe(){
            $this->adapterType = "netgsm";
            $this->urlHttp  = "https://api.netgsm.com.tr/sms/send/get/?usercode={USER}&password={PASS}&gsmno={CEP}&message={MESAJ}&msgheader={SENDER}";
            $this->urlHttps = "https://api.netgsm.com.tr/sms/send/get/?usercode={USER}&password={PASS}&gsmno={CEP}&message={MESAJ}&msgheader={SENDER}";
            $this->urlPost  = "https://api.netgsm.com.tr/sms/send/get/";
        }
        protected function __sendSingleSms($message,$phoneNumber){
            $r = new SimpleResult();
            $cep = StrPhone::getResult($phoneNumber);
            if($cep->isOK()){
                $r->setValue("phone",$cep->attributes["cell_sms"]);
                $r->setAttribute("originator", $this->originator);
                $params  = array(
                    "USER"=>urlencode($this->userName)
                    ,"PASS"=>urlencode($this->userPass)
                    ,"SENDER"=>urlencode($this->originator)
                    ,"CEP"=>$cep->attributes["cell_sms"]
                    ,"MESAJ"=>urlencode($message)
                );
                //\Vulcan\V::dump($params);
                //$urlToGo = StrParse::parse($this->urlHttps, $params);
                $urlToGo = $this->urlPost;                
                if($urlToGo==$this->urlPost){
                    $postParams=  array("usercode"=>$this->userName
                        ,"password"=>$this->userPass
                        ,"gsmno"=>$cep->attributes["cell_sms"]
                        ,"message"=>$message
                        ,"msgheader"=>$this->originator                        
                    );
                    if($this->optionEnableTurkishChars){
                        $postParams["dil"] = "TR";
                    }                    
                    $res = BrowserUtil::readUrlWithCurl($urlToGo,$postParams,array("http"=>"1.1"));                    
                    //\Vulcan\V::dump($res);
                }else{
                    $res = BrowserUtil::readUrlWithCurl($urlToGo);
                }
                
                if($res->value && strlen("".$res->value)>0){
                    $r->value = $res->value;
                    $arr = StringSplitter::withSpaces($res->value);
                    if(count($arr)>0){                        
                        if(count($arr)>1 && CompareUtil::str(@$arr[0], "00")){
                            $r->setAttribute("msgid", $arr[1]);
                            $r->setIsOk(true);
                        }else if(CompareUtil::str(@$arr[0], "20")){
                            $r->addError("Mesaj metninde ki problemden dolayı gönderilemedi veya standart maksimum mesaj karakter sayısını geçtiği için gönderilemedi.");
                        }else if(CompareUtil::str(@$arr[0], "30")){
                            $r->addError("Geçersiz kullanıcı adı , şifre veya kullanıcınızın API erişim izni.");
                        }else if(CompareUtil::str(@$arr[0], "40")){
                            $r->addError("Mesaj başlığınızın (gönderici adınızın) sistemde tanımlı olmadığı için gönderilemedi.");
                        }else if(CompareUtil::str(@$arr[0], "50")){
                            $r->addError("Abone hesabınız ile İYS kontrollü gönderimler yapılamamaktadır.");
                        }else if(CompareUtil::str(@$arr[0], "51")){
                            $r->addError("Aboneliğinize tanımlı İYS Marka bilgisi bulunamadığını ifade eder.");
                        }else if(CompareUtil::str(@$arr[0], "70")){
                            $r->addError("Hatalı sorgulama. Gönderdiğiniz parametrelerden birisi hatalı veya zorunlu alanlardan birinin eksik olduğunu ifade eder.");
                        }else if(CompareUtil::str(@$arr[0], "80")){
                            $r->addError("Gönderim sınır aşımı.");
                        }else if(CompareUtil::str(@$arr[0], "85")){
                            $r->addError("Mükerrer Gönderim sınır aşımı. Aynı numaraya 1 dakika içerisinde 20'den fazla görev oluşturulamaz.");                            
                        }                                                                        
                    }else{
                        $r->addError("Bilgi alınamadı.");
                    }
                }
                return $r;
            }else{
                return $cep;
            }                       
            return $r;
        }
        private function xmlForSmsToMany($userName="",$company_code="",$password="",$mesaj="",$numbers="",$originator="",$sDate=""){
            return "<MainmsgBody><UserName>".$userName."-".$company_code."</UserName><PassWord>".$password."</PassWord><Action>".($this->optLargeMessagesEnabled?"40":"0")."</Action><Mesgbody>".htmlentities($this->escapeSMSText($mesaj))."</Mesgbody><Numbers>".$numbers."</Numbers><Originator>".$originator."</Originator><SDate>".$sDate."</SDate></MainmsgBody>";
        }
        public function debug($msg=null,$cep=null){            
            $url = StrParse::parse($this->urlHttps,array( "USER"=>urlencode($this->userName),"PASS"=>urlencode($this->userPass),"CEP"=>"5355554979" ,"MESAJ"=>"TEST","SENDER"=>urlencode($this->originator)  ));
            \Vulcan\V::dump(array("url"=>$url,"this"=>$this));
        }                 
}
?>