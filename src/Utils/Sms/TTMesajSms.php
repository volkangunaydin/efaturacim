<?php
namespace Efaturacim\Util\Utils\Sms;

use Efaturacim\Util\Utils\Network\BrowserUtil;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StrContains;
use Efaturacim\Util\Utils\String\StrParse;
use Efaturacim\Util\Utils\String\StrPhone;
use Efaturacim\Util\Utils\String\StrUtil;

/**
 * TT Mesaj (Türk Telekom) SMS gönderim adaptörü — URL/GET ucu.
 *
 *   http://otp.ttmesaj.com/SendSMS/SendSMSURL.aspx?un={UN}&pw={PASS}&msg={MESAJ}&orgn={SENDER}&list={CEP}&sd={SD}
 *
 * Parametreler: un=kullanıcı adı, pw=parola, msg=mesaj, orgn=gönderici başlığı (header),
 * list=alıcı numara, sd=gönderim zamanı (0 = hemen).
 *
 * Numara formatı: Türkiye için **90** ile başlamalı (yurt dışı 00) — aksi hâlde 3 kodu döner.
 * Başarılı yanıt: {@code *OK*<MesajId>} · Hatalı yanıt: sayısal kod ya da {@code WP:<kod>,<açıklama>}.
 *
 * config/sms.php:
 *   ["adapter"=>"ttmesaj", "user"=>"...", "pass"=>"...", "originator"=>"ASFAT"]
 *
 * Vulcan tarafındaki eşdeğeri: {@see \Vulcan\Projects\SMS\TTMesaj\TTSmsAdapter}
 */
class TTMesajSms extends SmsAdapter
{
    /**
     * TT Mesaj API dönüş kodları (bkz. ttmesaj.com API dokümanı).
     * Pozitif kodlar gövdede çıplak gelir (ör. "3"); negatif kodlar "WP:-16,<açıklama>" biçiminde döner.
     */
    public static $RETURN_CODES = array(
        '0' => 'Hata yok.',
        '-1' => 'Sistem hatası.',
        '-4' => 'Yetersiz kontör.',
        '-6' => 'Hesap aktif değil.',
        '-10' => 'Kullanıcı adı/parola hatalı.',
        '-11' => 'Gönderici başlığı (header) tanımlı değil.',
        '-15' => 'Tarih formatı hatalı (sd parametresi yyyyMMddHHmm olmalı).',
        '-16' => 'Geçerli numara yok.',
        '1' => 'Kullanıcı adı/parola yanlış.',
        '2' => 'IP tanımlı değil.',
        '3' => 'Hatalı GSM no formatı. GSM no Türkiye için 90 ile, yurt dışı için 00 ile başlamalı.',
        '4' => 'SMS içeriği 4 mesaj uzunluğundan fazla veya boş olamaz.',
        '5' => 'Header (gönderici başlığı) sistemde tanımlı değil.',
        '6' => 'Başlangıç veya bitiş tarihi formatı hatalı (yyyyMMddHHmm).',
        '7' => 'Bitiş tarihi başlangıç tarihinden önce.',
        '8' => 'Bitiş tarihi geçmiş tarih olamaz.',
        '9' => 'Bitiş-başlangıç tarihi arası 3 günden fazla.',
        '10' => 'Maksimum 30 gün önceki mesaj raporu çekebilirsiniz.',
        '11' => 'XML formatı yanlış.',
        '12' => 'MesajId hatalı.',
        '13' => 'Anlık trafik limitini aştınız.',
        '14' => 'Yetersiz kontör adedi.',
        '15' => 'En fazla 50.000 kişiye gönderebilirsiniz.',
        '16' => 'Bu servisten OTP SMS gönderimi gerçekleşmemektedir.',
        '17' => 'Hattın durumu SMS gönderimine uygun değildir.',
        '18' => 'XML içeriği hatalıdır.',
        '19' => 'Kullanıcı kodu yanlış.',
        '20' => 'Rapor bulunamadı.',
        '21' => 'Veri bulunamadı.',
        '22' => 'Hesabınız faturalıdır.',
        '23' => 'Yetkiniz bulunmamaktadır.',
        '24' => 'Status parametresi hatalıdır.',
        '25' => 'Email parametresi hatalıdır.',
        '26' => 'Subject parametresi hatalıdır.',
        '27' => 'Alt kullanıcı bulunmamaktadır.',
        '28' => 'Hesap tipiniz ön ödemeli değildir.',
        '29' => 'Bayi kodu hatalıdır.',
        '30' => 'Rapor tarih formatı hatalıdır.',
        '31' => 'Aynı gün içerisinde raporlama limitini aştınız.',
        '32' => 'İptal edilemedi. Maksimum 15 dakikalık geriye yönelik iptal süresi geçirildi.',
        '33' => 'Alıcı tipi BIREYSEL ya da TACIR olarak belirtilmelidir.',
        '34' => 'Gönderim tipi Kampanya olan iletilerde BrandCode boş geçilemez.',
    );

    protected function initMe()
    {
        $this->optionSplitMessages = true;
        $this->adapterType = 'ttmesaj';
        $url = 'http://otp.ttmesaj.com/SendSMS/SendSMSURL.aspx?un={UN}&pw={PASS}&msg={MESAJ}&orgn={SENDER}&list={CEP}&sd={SD}';
        $this->urlHttp = $url;
        $this->urlHttps = $url;
        $this->urlXml = $url;
    }

    /** Kullanıcı adı: standart {@code user} alanı; geriye dönük uyumluluk için {@code un} seçeneği de kabul edilir. */
    protected function getUserNameForApi()
    {
        $un = $this->options->getAsString(array('un', 'UN'));
        if (StrUtil::notEmpty($un)) {
            return $un;
        }

        return $this->userName;
    }

    /**
     * Yanıt gövdesini çözümler; başarılıysa MesajId'yi sonuca yazar.
     *
     * @return SimpleResult
     */
    public static function parseResponse($bodyString)
    {
        $r = new SimpleResult();
        $body = trim(''.$bodyString);
        $r->setAttribute('response', $body);
        if (! StrUtil::notEmpty($body)) {
            $r->addError('TT Mesaj sunucusundan yanıt alınamadı. (Zorunlu parametrelerden biri eksik olabilir.)');

            return $r;
        }
        if (StrContains::startsWith($body, '*OK*')) {
            $msgId = trim(substr($body, 4));
            $r->setIsOk(true);
            $r->value = $msgId;
            $r->setAttribute('msgid', $msgId);

            return $r;
        }
        // "WP:-16,Geçerli numara yok" biçimindeki yanıtlar
        $code = $body;
        $providerMessage = null;
        if (StrContains::startsWith($body, 'WP:')) {
            $rest = substr($body, 3);
            $p = strpos($rest, ',');
            $code = $p === false ? trim($rest) : trim(substr($rest, 0, $p));
            if ($p !== false) {
                $providerMessage = trim(substr($rest, $p + 1));
                $r->setAttribute('provider_message', $providerMessage);
            }
        }
        $r->setAttribute('code', $code);
        $aciklama = @self::$RETURN_CODES[$code];
        if (is_null($aciklama)) {
            $aciklama = $providerMessage;
        }
        if (StrUtil::notEmpty($aciklama)) {
            $r->addError('SMS gönderilemedi: '.$aciklama.' [ Kod : '.$code.' ]');
        } else {
            $r->addError('SMS gönderilemedi. [ Yanıt : '.$body.' ]');
        }

        return $r;
    }

    /**
     * @return SimpleResult
     */
    protected function __sendSingleSms($message, $phoneNumber)
    {
        $cep = StrPhone::getResult($phoneNumber);
        if (! $cep->isOK()) {
            return $cep;
        }
        $params = array(
            'UN' => urlencode($this->getUserNameForApi()),
            'PASS' => urlencode($this->userPass),
            'SENDER' => urlencode($this->originator),
            'CEP' => '90'.$cep->attributes['cell_sms'],
            'MESAJ' => urlencode($message),
            'SD' => urlencode($this->options->getAsString(array('sd', 'SD'), '0')),
        );
        $urlToGo = StrParse::parse($this->urlHttps, $params);
        $res = BrowserUtil::readUrlWithCurl($urlToGo);

        $r = self::parseResponse(''.$res->value);
        // SimpleResult::setValue() tek argüman alır ve value'yu ezer; numara attribute olarak yazılır
        // (başarılı gönderimde value = TT Mesaj MesajId olarak kalmalı).
        $r->setAttribute('phone', $cep->attributes['cell_sms']);
        $r->setAttribute('originator', $this->originator);
        // Parola URL içinde geçtiği için log/teşhis çıktısına maskelenmiş hâli konur.
        $r->setAttribute('url', str_replace('pw='.urlencode($this->userPass), 'pw=***', $urlToGo));

        return $r;
    }
}
