<?php
namespace Efaturacim\Util\Utils\IO;

class LibPath{
    public static function getLibPath(){
        $r = realpath(dirname(__FILE__)."/../../");
        $r = str_replace("\\","/",$r);
        if(substr($r,-1) !== "/"){
            $r .= "/";
        }
        return $r;
    }
}
?>