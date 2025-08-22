<?php
namespace Efaturacim\Util\Utils\PhpDev;

use Efaturacim\Util\Utils\Html\Form\FormParams;
use Efaturacim\Util\Utils\Json\JsonUtil;
use Efaturacim\Util\Utils\SimpleResult;

class GitUtil{
    public static function getHookParams($secretGuid=null,$isJson=true,$getFromRequest=false){
        $r = new SimpleResult();
        if(is_array($secretGuid)){
            foreach($secretGuid as $secret){
                $rr = self::getHookParams($secret,$isJson,$getFromRequest);
                if($rr->isOk()){
                    return $rr;
                }
            }
            $r->addError("Forbidden: Secret token not found.");
            return $r;   
        }
        if($getFromRequest){
            $key = FormParams::getRequestParam("secretGuid","","string");
            if($key==$secretGuid){
                $r->setIsOk(true);                
                return $r;
            }
        }
        $signature_header = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? null;        
        if (!$signature_header) {            
            $r->addError("Forbidden: Signature header not set.");
            return $r;
        }
        // The signature is in the format "sha256=...". We need the hash part.
        list($algo, $hash) = explode('=', $signature_header, 2);
        $payload = file_get_contents('php://input');
        
        $payload_hash = hash_hmac($algo, $payload, $secretGuid);
        if (!hash_equals($hash, $payload_hash)) {
            $r->addError("Forbidden: Signatures do not match.");
            return $r;
        }        
        $r->setIsOk(true);
        $r->value = $payload;
        if($isJson && JsonUtil::isJson($payload)){
            $r->value = JsonUtil::getAsArray($payload);
        }
        return $r;
    }
}

?>