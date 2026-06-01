<?php

namespace Efaturacim\Util\Orkestra\Soap\Util;

use Efaturacim\Util\Orkestra\Soap\OrkestraSoapClient;
use Vulcan\VResult;

class OrkestraSoapDelete
{
    public static function delete($smartClient, $ref=null,$nesneAdi=null,$userRef=null)
    {
        $res = new VResult();
        if($smartClient instanceof OrkestraSmartClient){
            if($userRef > 0){
                $smartClientForUser = OrkestraSoapClient::
            }
        }else{
            $res->setError("Orkestra bağlantısı kurulamadı.");
        }
        return $res;
    }
}