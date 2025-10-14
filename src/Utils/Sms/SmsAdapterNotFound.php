<?php
namespace Efaturacim\Util\Utils\Sms;

use Efaturacim\Util\Utils\SimpleResult;

class SmsAdapterNotFound extends SmsAdapter{
    protected function initMe(){
    }
    public function isOK(){
        return false;
    }
    public function getCreditCount(){
        return null;
    }
    protected function __sendSingleSms($message,$phoneNumber){
        $r= new SimpleResult();
        $r->addError("SMS adapter bulunamadı.");
        return $r;
    }
}