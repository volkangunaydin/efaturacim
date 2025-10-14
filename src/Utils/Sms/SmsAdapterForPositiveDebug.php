<?php
namespace Efaturacim\Util\Utils\Sms;

use Efaturacim\Util\Utils\SimpleResult;

class SmsAdapterForPositiveDebug extends SmsAdapter{
    protected function initMe(){
        $this->userName = "test";
        $this->userPass = "test";
        $this->company = "test";
        $this->originator = "test";
    }
    public function getCreditCount(){
        return null;
    }
    protected function __sendSingleSms($message,$phoneNumber){
        $r = new SimpleResult();
        $r->setIsOk(true);
        $r->addSuccess("SMS gönderildi.[ ".$phoneNumber." - Debug Positive]");
        return $r;
    }
}   
?>