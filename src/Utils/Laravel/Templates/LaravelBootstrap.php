<?php
namespace Efaturacim\Util\Utils\Laravel\Templates;

use Efaturacim\Util\Utils\Html\Bootstrap\Alert;
use Efaturacim\Util\Utils\SimpleResult;

class LaravelBootstrap{
    public static function autoRenderMessages(){
        $result  = @session('result');
        $msg     = @session('msg');
        $errors  = @session('errors');
        $error   = @session('error');
        $success = @session('success');        
        return self::renderResult($result,$msg,$errors,$error,$success);
    }
    public static function renderSimpleResult($result){
        $s = '';
        if($result instanceof SimpleResult){
            foreach($result->messages as $msg){
                $s .= Alert::alert($msg['type'],$msg['text']);
            }
        }        
        return $s;
    }
    public static function renderMsg($msg,$type='info'){
        $s = '';
        if($msg){
            if(is_array($msg)){
                foreach($msg as $m){
                    $s .= Alert::alert($type,$m);
                }
            }else if (is_string($msg) && strlen($msg)>0){
                $s .= Alert::alert($type,$msg);
            }else{
                if($msg instanceof \Illuminate\Support\MessageBag){
                    foreach($msg->all() as $m){
                        $s .= Alert::alert('danger',$m);
                    }
                }
            }
        }
        return $s;
    }
    public static function renderResult($result,$msg=null,$errors=null,$error=null,$success=null){
        $s = '';
        $s .= self::renderSimpleResult($result);
        $s .= self::renderMsg($msg,'info');
        $s .= self::renderMsg($errors,'danger');
        $s .= self::renderMsg($error,'danger');
        $s .= self::renderMsg($success,'success');        
        return $s;
    }   
}
?>