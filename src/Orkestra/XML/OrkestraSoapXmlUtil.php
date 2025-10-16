<?php
namespace Efaturacim\Util\Orkestra\XML;

use function PHPSTORM_META\map;

class OrkestraSoapXmlUtil{
    public static function esc($str){
        return htmlentities("".$str,ENT_XML1 | ENT_QUOTES,'UTF-8');
    }
}
?>