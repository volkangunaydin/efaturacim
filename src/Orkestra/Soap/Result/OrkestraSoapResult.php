<?php
namespace Efaturacim\Util\Orkestra\Soap\Result;

use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\Soap\SoapResponse;
use Efaturacim\Util\Utils\String\StrBase64;
use Efaturacim\Util\Utils\String\StrUtil;

class OrkestraSoapResult extends SimpleResult{
    public $requestString = "";
    public $responseText  = "";
    public function getStringInBetweenTags($tag,$base64=false){
        $s = null;
        $pattern = '/<'.$tag.'>(.*?)<\/'.$tag.'>/s';
        if(preg_match($pattern,$this->responseText,$matches)){
            $s =  trim("".$matches[1]);
        }
        if($base64 && StrUtil::notEmpty($s)){
            $s = StrBase64::decode($s);
        }
        return $s;
    }
}
?>