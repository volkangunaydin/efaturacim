<?php
namespace Efaturacim\Util\Utils\Html\Bootstrap;
use Efaturacim\Util\Utils\Html\HtmlComponent;

class Alert extends HtmlComponent{
    public function toHtmlAsString(){
        $type = @$this->options['type'];
        if(in_array($type,array("error","err","danger"))){
            $type = 'danger';
        }elseif(in_array($type,array("succ","ok"))){
            $type = 'success';
        }elseif(in_array($type,array("warn"))){
            $type = 'warning';
        }
        $s = '<div class="alert alert-'.$type.'" role="alert">';
        $s .= @$this->options['message'];
        $s .= '</div>';
        return $s;
    }
    public function getDefaultOptions(){
        return array('type'=>'danger','message'=>'');
    }

    public function getJsLines(){
        return null;
    }
    // KISAYOLLAR - SHORTCUTS
    public static function alert($type,$message,$options=null){
        return (new self(array('type'=>$type,'message'=>$message),$options))->toHtmlAsString();
    }
    public static function error($message,$options=null){
        return self::alert('danger',$message,$options);   
    }
    public static function success($message,$options=null){
        return self::alert('success',$message,$options);   
    }
    public static function info($message,$options=null){
        return self::alert('info',$message,$options);   
    }
    public static function warning($message,$options=null){ 
        return self::alert('warning',$message,$options);   
    }
    public static function primary($message,$options=null){
        return self::alert('primary',$message,$options);   
    }
    public static function secondary($message,$options=null){
        return self::alert('secondary',$message,$options);   
    }
}
?>