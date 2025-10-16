<?php

namespace Efaturacim\Util\Orkestra\XML;

use Efaturacim\Util\Utils\String\StrUtil;

class ValidateUserPass{
    public static function xml($user,$pass,$period=null){
        return '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:ws="http://ws.server.orkestra.com.tr">
   <soap:Header/>
   <soap:Body>
      <ws:validateUserPass>         
         <params>            
            '.(StrUtil::notEmpty($period) ? '<Period>'.OrkestraSoapXmlUtil::esc($period).'</Period>' : '').'
            <InputType></InputType>            
            <OutputType></OutputType>            
            <UserName>'.OrkestraSoapXmlUtil::esc($user).'</UserName>            
            <Password>'.OrkestraSoapXmlUtil::esc($pass).'</Password>
         </params>
      </ws:validateUserPass>
   </soap:Body>
</soap:Envelope>';
    }
}
?>