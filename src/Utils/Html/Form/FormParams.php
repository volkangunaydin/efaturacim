<?php
namespace Efaturacim\Util\Utils\Html\Form;

use Efaturacim\Util\Utils\Array\AssocArray;

class FormParams{
    public static $AUTO = "__AUTO__";
    public static function getGetParam($nameOrNames,$defVal=null,$type=null){
        return AssocArray::getVal($_GET,$nameOrNames,$defVal,$type);
    }
    public static function getPostParam($nameOrNames,$defVal=null,$type=null){
        return AssocArray::getVal($_POST,$nameOrNames,$defVal,$type);
    }
    public static function getRequestParam($nameOrNames,$defVal=null,$type=null){
        $post = self::getPostParam($nameOrNames,null,$type);
        if(is_null($post)){
            $get = self::getGetParam($nameOrNames,$defVal,$type);
            if(is_null($get)){
                return $defVal;
            }
        }else{
            return $post;
        }        
    }
}
?>