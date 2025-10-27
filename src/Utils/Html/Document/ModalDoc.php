<?php
namespace Efaturacim\Util\Utils\Html\Document;

use Efaturacim\Util\Utils\Html\Bootstrap\Alert;

class ModalDoc{
    public static function alert($content,$title=null,$type="error",$options=null){
        $d = new BootstrapDocument($options);
        $s = Alert::alert($type,$content,array("title"=>$title));
        $d->setBodyContent($s);
        return $d->show();
    }
    public static function error($content, $title=null,$options=null){
        return self::alert($content, $title, "error", $options);
    }
    public static function success($content, $title=null,$options=null){
        return self::alert($content, $title, "success", $options);
    }
    public static function info($content, $title=null,$options=null){
        return self::alert($content, $title, "info", $options);
    }
    public static function warning($content, $title=null,$options=null){
        return self::alert($content, $title, "warning", $options);
    }
    public static function danger($content, $title=null,$options=null){
        return self::alert($content, $title, "danger", $options);
    }
}