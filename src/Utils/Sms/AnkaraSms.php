<?php
namespace Efaturacim\Util\Utils\Sms;

use Efaturacim\Util\Utils\Equality\CompareUtil;
use Efaturacim\Util\Utils\Network\BrowserUtil;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StringSplitter;
use Efaturacim\Util\Utils\String\StrParse;
use Efaturacim\Util\Utils\String\StrPhone;

class AnkaraSms extends SmsAdapter
{
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
    protected function initMe()
    {
        $this->adapterType = "ankaratoplusms";
        $this->urlHttp = "http://panel.ankaratoplusms.net/jsonapi/SubmitMulti";
        $this->urlHttps = "https://panel.ankaratoplusms.net/jsonapi/SubmitMulti";
        $this->urlPost = "http://panel.ankaratoplusms.net/jsonapi/SubmitMulti";
    }
    protected function __sendSingleSms($message, $phoneNumber)
    {

        $r = new SimpleResult();
        $cep = StrPhone::getResult($phoneNumber);
        if ($cep->isOK()) {
            $post = json_encode([
                "auth" => [
                    "username" => $this->userName,
                    "password" => $this->userPass
                ],
                "MsgType" => "Turkey",
                "DataCoding" => "Standart",
                "SecureCode" => "olXhDX&R[A",
                "Originator" => $this->originator,
                "Messages" => [
                    [
                        "Message" => $message,
                        "To" => [$cep->getAttribute('cell_sms')],
                    ]
                ]
            ]);
            $apiKey = $this->options->getAsString(array("api_key", "apiKey", "ApiKey", "APIKEY"));
            $apiSecret = $this->options->getAsString(array("api_secret", "apiSecret", "ApiSecret", "APISECRET"));
            $url = curl_init($this->urlHttp);
            curl_setopt($url, CURLOPT_POSTFIELDS, $post);
            curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($url, CURLOPT_TIMEOUT, 10);
            curl_setopt($url, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($url, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($url, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt(
                $url,
                CURLOPT_HTTPHEADER,
                array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($post),
                    'Api-Key: ' . $apiKey,
                    'Api-Secret: ' . $apiSecret,
                )
            );
            $result = curl_exec($url);
            $httpCode = curl_getinfo($url, CURLINFO_HTTP_CODE);
            curl_close($url);
            if ($httpCode == 200 || $httpCode == 201) {
                $r->setIsOk(true);
                $r->value = $result;
                $r->addSuccess('Doğrulama kodu ' . $phoneNumber . ' numarasına gönderildi.');
            } else {
                $r->setIsOk(false);
                $r->addError('SMS gönderilemedi. Lütfen tekrar deneyin.');
            }
        } else {
            return $cep;
        }
        return $r;
    }
    private function xmlForSmsToMany($userName = "", $company_code = "", $password = "", $mesaj = "", $numbers = "", $originator = "", $sDate = "")
    {
        return "<MainmsgBody><UserName>" . $userName . "-" . $company_code . "</UserName><PassWord>" . $password . "</PassWord><Action>" . ($this->optLargeMessagesEnabled ? "40" : "0") . "</Action><Mesgbody>" . htmlentities($this->escapeSMSText($mesaj)) . "</Mesgbody><Numbers>" . $numbers . "</Numbers><Originator>" . $originator . "</Originator><SDate>" . $sDate . "</SDate></MainmsgBody>";
    }
    public function debug($msg = null, $cep = null)
    {
        $url = StrParse::parse($this->urlHttps, array("USER" => urlencode($this->userName), "PASS" => urlencode($this->userPass), "CEP" => "5355554979", "MESAJ" => "TEST", "SENDER" => urlencode($this->originator)));
        \Vulcan\V::dump(array("url" => $url, "this" => $this));
    }

}
?>