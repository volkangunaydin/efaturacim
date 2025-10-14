<?php
namespace Efaturacim\Util\Utils\String;

use Efaturacim\Util\Utils\SimpleResult;
use Exception;
use Vulcan\VResult;
            
class StrEMail{
    public static function getResult($str,$options=null,$getDetails=false){
        $res = new SimpleResult();
        $res->setAttribute("org", $str);
        $trim = trim("".$str);
        if(\Vulcan\V::notEmptyString($str)){
            if(self::validateEmail($str,true)){                    
                $res->setIsOk(true);
                $res->value = trim("".$str);
                if($getDetails){
                    $pos = strpos($str, "@");                        
                    if($pos && $pos>0){
                        $res->setAttribute("user", substr($str, 0,$pos) );
                        $res->setAttribute("domain", substr($str, $pos+1) );
                        $res->setAttribute("safe_domain", strtolower(substr($str, $pos+1)) );
                        $res->setAttribute("safe_user", strtolower( $res->attributes["user"] ) );
                        $res->setAttribute("email", $str);
                        $res->setAttribute("safe_email", $res->attributes["safe_user"]."@".$res->attributes["safe_domain"] );
                    }else{
                        $res->setIsOk(false);
                    }
                }
                return $res;
            }else{
                $res->addError("E-posta adresi uygun formatta değil.");
            }
        }else{
            $res->addError("E-posta adresi boş.");
        }
        return $res;
    }	
    public static function isValid($str){
        return self::validateEmail($str);
    }
    
    public static function getEmailAsHtmlLink($str){
        $r = self::getResult($str);
        if($r->isOK()){
            return '<a href="mailto:'.$r->value.'" >'.$r->value.'</a>';
        }
        return "";
    }
    public static function getEmailAsString($str){
        $r = self::getResult($str);
        if($r->isOK()){
            return $r->value;
        }
        return "";
    }
    protected  static function validateEmail($email,$shoulHaveAtSign=true){
        $return  = TRUE;
        if(function_exists("filter_var")){
            $return=  filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
            if ($return && $shoulHaveAtSign){ return strpos($email, "@")!==false; }
            return $return;
        }
        if (@ereg("^([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([0-9,a-z,A-Z]){2}([0-9,a-z,A-Z])*$",$email)) {
            $return = TRUE;
            if (@ereg("[ö,ü,Ü,Ö,İ,ı,ğ,Ğ,ç,Ç,ş,Ş]",$email)) { $return = FALSE; }
        } else {
            $return = FALSE;
        }
        if ($return && $shoulHaveAtSign){ return strpos($email, "@")!==false; }
        return $return;
    }
    public static function mask($eposta){
        $p = strpos("".$eposta, "@");
        if($p && $p>0){
            $p1 = substr($eposta, 0,$p);
            $p2 = substr($eposta, $p+1);
            return StrMask::smart($p1)."@".StrMask::smart($p2);
        }
        return $eposta;
    }
    public static function getMultipleEmailAddressAsString($str,$options=null,$defVal=null){
        return EMailList::getEmailsAsString($str,$options,$defVal);
    }
    public static function getMultipleEmailAddressAsResult($str,$options=null){
        return EMailList::getMultipleEmailAddressAsResult($str,$options);
    }
    public static function getAsString($str,$defVal=null){
        if(StrUtil::notEmpty($str)){
            $r = self::getResult($str);
            if($r->isOK()){
                return $r->value;
            }else{
                return $defVal;
            }
        }
        return $defVal;
    }
}

?>