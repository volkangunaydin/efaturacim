<?php
namespace Efaturacim\Util\Orkestra\XML;

class LoginLogoutXml{
    public static function login($user,$pass){
        return '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ws="http://ws.server.orkestra.com.tr"><soap:Header/><soap:Body><ws:login><loginUser>'.OrkestraSoapXmlUtil::esc($user).'</loginUser><loginPass>'.OrkestraSoapXmlUtil::esc($pass).'</loginPass></ws:login></soap:Body></soap:Envelope>';
    }
    public static function logout($sessionId){
        return '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ws="http://ws.server.orkestra.com.tr">
   <soap:Header/>
   <soap:Body>
      <ws:logout>
         <sessionId>'.OrkestraSoapXmlUtil::esc($sessionId).'</sessionId>
      </ws:logout>
   </soap:Body>
</soap:Envelope>';
    }
}

?>