<?php
namespace Efaturacim\Util\Utils\String;

use Efaturacim\Util\Utils\CastUtil;
use Vulcan\UI\Bootstrap\Components\FormElements;
use Vulcan\VResult;
            
class EMailList{
    public $result = null;
    public $list = array();
    public function __construct($strEmails="",$strDefName=null){
        $this->result = new  VResult();
        $this->result->setIsOk(true);
        if(!is_null($strEmails) && is_string($strEmails) && strlen("".$strEmails)>0){                
            $this->add($strEmails,$strDefName);                
        }else if ($strEmails && is_array($strEmails) && count($strEmails)>0){
            foreach($strEmails as $email=>$name){
                if(StrEMail::isValid($email)){                        
                    $this->add($email,$name);                        
                }else  if(StrEMail::isValid($name)){
                    $this->add($name,$name);
                }
            }            
        }
    }
    protected function validateEmail($email,$shoulHaveAtSign=true){
        $return  = TRUE;
        if(function_exists("filter_var")){
            $return =  filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
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
    protected function getAsEmailString($email,$name=""){
        if(!is_null($name) && $name!=$email && strlen($name)>0){
            $name = str_replace(array('"',"'"), array("",""), $name);
            return '"'.@$name.'"'."<".$email.">";
        }
        return $email;
    }
    protected function add($eposta,$nameSurname=null,$tryPunc=false){
        if(\Vulcan\V::notEmptyString($eposta) && \Vulcan\V::notEmptyString($nameSurname) && StrEMail::isValid($eposta)){
            $this->list[$eposta] = array("email"=>$eposta,"name"=>$nameSurname);
        }else if(\Vulcan\V::notEmptyString($eposta)){
            //$eposta = str_replace(array(";",","), array("\n","\n"), $eposta);                
            $lines = StringSplitter::newLines($eposta,true,true);                
            foreach ($lines as $k=>$v){
                $v = trim($v);
                $p_quoute = strrpos($v, '"');
                $offset = 0;
                if($p_quoute && $p_quoute>0){
                    $offset = $p_quoute;
                }
                $p1 = strpos($v, "<",$offset);
                $p2 = strpos($v, ">",$offset);
                if(strlen($v)>0 && $p1!==FALSE && $p2>$p1 && strlen($v)>($p2+1)){
                    $parts =  preg_split("/,|;/", $v,-1,PREG_SPLIT_NO_EMPTY);                        
                    if(count($parts)>1){
                        foreach ($parts as $part){
                            $this->add($part);
                        }
                        continue;
                    }
                }                    
                if(strlen($v)>0 && $p1!==FALSE && $p2>$p1){
                    $eposta      = substr($v, $p1+1,$p2-$p1-1);
                    if($p1>0){
                        $nameSurname = substr($v, 0,$p1);
                        $nameSurname = trim($nameSurname);
                        if(strlen($nameSurname)>0){
                            if(StrUtil::endsWith($nameSurname, '"') && StrUtil::startsWith($nameSurname, '"')){
                                $nameSurname = substr($nameSurname,1,strlen($nameSurname)-2);
                            }else if(StrUtil::endsWith($nameSurname, "'") && StrUtil::startsWith($nameSurname, "'")){
                                $nameSurname = substr($nameSurname,1,strlen($nameSurname)-2);
                            }
                            $nameSurname = str_replace(array('"',"'","\r","\n"), array("","","",""), $nameSurname);
                            $nameSurname = str_replace(array("<",">","\\","/"), array("","","",""), $nameSurname);
                        }
                    }else{ $nameSurname = ""; }
                    if(!key_exists($eposta, $this->list)){
                        $this->list[$eposta] = array("email"=>$eposta,"name"=>$nameSurname);
                    }
                }else if(strlen($v)>0 && $this->validateEmail($v)){
                    if(!key_exists($v, $this->list)){
                        $this->list[$v] = array("email"=>$v,"name"=>"");
                    }
                }else{                        
                    $parts =  preg_split("/,| |;/", $v,-1,PREG_SPLIT_NO_EMPTY);                        
                    if(count($parts)>1){
                        foreach ($parts as $part){
                            $this->add($part);
                        }
                    }
                }
            }
        }else if ($eposta && strlen($eposta)>0 && $this->validateEmail($eposta) && !key_exists($eposta, $this->list)){
            $this->list[$eposta] = array("email"=>$eposta,"name"=>$nameSurname);
        }
        return $this;
    }
    
    // **************************************
    public function toPlainString($sep=null){
        if(is_null($sep) || $sep==""){
            $sep = "\r\n";
        }
        $arr = array();
        foreach ($this->list as $k=>$v){
            if(is_array($v) && key_exists("email", $v) && strlen(@$v["email"])>0){
                $arr[] = $v["email"];
            }
        }
        return count($arr)>0 ? implode($sep, $arr): null;
    }
    public function toPlainStringWithComa(){
        return $this->toPlainString(",");
    }
    public function toHtmlString(){
        $s = '';            
        foreach ($this->list as $k=>$v){
            if(key_exists("name", $v) && strlen(@$v["name"])>0){
                $s .= '<div>"'.@$v["name"]."\"&lt;".@$v["email"].'&gt;</div>';
            }else{
                $s .= '<div>'.@$v["email"].'</div>';
            }
        }
        return $s;
    }                
    public function toArray(){
        $arr = array();   
        foreach ($this->list as $k=>$v){
            if(is_array($v) && key_exists("email", $v) && strlen(@$v["email"])>0){
                if(key_exists("name", $v) && strlen(@$v["name"])){
                    $arr[$v["email"]] = $v["name"];
                }else{
                    $arr[@$v["email"]] = $v["email"];
                }
            }
        }
        return $arr;
    }
    public function toEmailString(){
        $arr = array();
        $list = $this->toArray();
        foreach ($list as $email=>$name){
            if($email==$name){ $arr[] = $email; }else{ $arr[] = '"'.$name.'"'."<".$email.">"; }
        }
        return count($arr)>0 ? implode("\r\n", $arr): null;
    }
    public static function getEmailsAsArray($str,$defVal=null,$options=null){
        $list = new EMailList();
        $list->add($str);
        return $list->toArray();
    }
    
    public static function getEmailsAsString($str,$defVal=null,$options=null){
        $list = new EMailList();
        $list->add($str);
        $s = $list->toEmailString();
        return strlen("".$s)>0 ? $s  : $defVal;
    }
    public static function getMultipleEmailAddressAsResult($str,$options=null){
        $list = new EMailList();
        $list->add($str);
        $list->result->list = $list->list;
        if(count($list->result->list)==0){
            $list->result->setIsOk(false);
            $list->result->addError("Geçerli bir e-posta adresi bulunamadı.");
        }
        return $list->result;
    }
    public function getSize(){
        return count($this->list);
    }
    
    public static function fromForm($name,$nameForEMailName=null){
        return new EMailList(FormElements::getVal($name,"",CastUtil::$DATA_STRING),FormElements::getVal($nameForEMailName,null,CastUtil::$DATA_STRING));
    }
    /**
     * @param string $strEmails
     * @param string $strDefName
     * @return EMailList
     */
    public static function fromString($strEmails=null,$strDefName=null){
        return new EMailList($strEmails,$strDefName);
    }
}

?>