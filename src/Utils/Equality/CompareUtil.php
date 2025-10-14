<?php
namespace Efaturacim\Util\Utils\Equality;

use Vulcan\Base\Util\StringUtil\StringUtilForVarname;
use Vulcan\Base\Util\StringUtil\CastUtil;
use Vulcan\Base\Util\StringUtil\StrUtil;
use Vulcan\Base\Util\Date\VDate;
use Vulcan\Base\Util\StringUtil\StrPhone;
                                                                            
class CompareUtil{
    public static function str($a,$b){
        if($a===$b){  return true; }
        if($a && $b && is_scalar($a) && is_scalar($b) && ($a."")===("".$b)){
            return true;
        }
        return false;
    }
    public static function strEquals($a,$b){
        if(is_array($a) || is_array($b) || is_object($a) || is_object($b)){ return false; }
        if($a===$b || ($a."")===("".$b)){  return true; }
        if($a && $b && is_scalar($a) && is_scalar($b) && ($a."")===("".$b)){
            return true;
        }
        return false;
    }
    public static function stri($a,$b){
        if($a && $b && is_scalar($a) && is_scalar($b) && strlen("".$a)>0 && strlen("".$b)>0){
            if($a===$b){ return true; }
            $a1 = StrUtil::toLowerEng("".$a);
            $b1 = StrUtil::toLowerEng("".$b);
            if($a1==$b1){ return true; }
        }
        return false;            
    }
    public static function int($a,$b){
        $p1 = CastUtil::asInt($a);
        $p2 = CastUtil::asInt($b);
        if($p1>0 && $p1==$p2 ){
            return true;
        }
        return false;
    }
    public static function date($a,$b){
        if(\Vulcan\V::isEmptyString($a) && \Vulcan\V::isEmptyString($b)){ return true; }            
        if( VDate::newDate($a)->toDbDate() == VDate::newDate($b)->toDbDate() ){
            return true;   
        }            
        return false;
    }
    public static function money($a,$b){
        $p1 = CastUtil::asCleanNumber($a,2);
        $p2 = CastUtil::asCleanNumber($b,2);
        if($p1==$p2){
            return true;
        }
        return false;
    }
    public static function strSmart($a,$b){
        if($a && $b && is_scalar($a) && is_scalar($b) && strlen("".$a)>0 && strlen("".$b)>0){
            if($a===$b){ return true;}
            if(strtolower($a)===strtolower($b)){  return true; }
            $a_safe = StringUtilForVarname::toVariableName($a);
            $b_safe = StringUtilForVarname::toVariableName($b);
            if($a_safe && strlen($a_safe)>0 && $a_safe===$b_safe){  return true; }
        }
        return false;            
    }
    public static function isRef($a){
        if(!is_null($a) && is_numeric($a) && $a>0 && floor($a)==$a){
            return true;
        }
        return false;
    }
    public static function phone($a,$b){
        if($a && $b && strlen("".$a)>0 && strlen("".$b)>0){
            $r1 = StrPhone::getResult($a);
            $r2 = StrPhone::getResult($b);
            if($r1->isOK() && $r2->isOK() && $r1->value==$r2->value){
                return true;
            }                
        }
        return false;
    }
    public static function otp($a,$b){
        $a = StrUtil::trimAllSpaces($a,true);
        $b = StrUtil::trimAllSpaces($b,true);
        if($a && $b && strlen("".$a)>3 && strlen("".$b)>3){
            return self::stri($a, $b);
        }
        return false;
    }
    
}

?>