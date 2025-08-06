<?php
namespace Efaturacim\Util\Utils;

use Efaturacim\Util\SimpleResult;

class GitUtil{
    public static function getHookParams($secretGuid=null,$isJson=true){
        $r = new SimpleResult();
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
        if($isJson ){
            $r->value = @json_decode($payload,true);
        }
        return $r;
    }
}

?>