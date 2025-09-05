<?php
namespace Efaturacim\Util\Utils\Html\Js;

class Jquery{
    public static function getJsLineForCsrfInit($useVar=true){
        $s = ($useVar?'let csrfToken = ':'').'$(\'meta[name="csrf-token"]\').attr(\'content\'); if (csrfToken) { $.ajaxSetup({headers: {\'X-CSRF-TOKEN\': csrfToken } }); }';
        return $s;
    }
    public static function initCsrf(&$doc){
        $doc->addJsLineToDomReady(self::getJsLineForCsrfInit(),'init_csrf',true);
    }
}
?>