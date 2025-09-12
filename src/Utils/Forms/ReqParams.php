<?php
namespace Efaturacim\Util\Utils\Forms;

use Efaturacim\Util\Utils\CastUtil;

class ReqParams{
    public static function getVal($name,$default=null,$type=null){
        $r = $default;
        if(isset($_REQUEST[$name])){
            $r =  $_REQUEST[$name];
        }
        if(!is_null($type)){
            $r = CastUtil::getAs($r,$default,$type);
        }
        return $r;
    }
    public static function isEqualToString($name,$value){     
        return self::getVal($name,null,"string")===$value;
    }

    
}
?>