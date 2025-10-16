<?php
namespace Vulcan\Base\Util\StringUtil{

    use Efaturacim\Util\Utils\CastUtil;
    use Efaturacim\Util\Utils\Options;

    class StringUtilForVarname{
        public static  function removeTurkishChars($str){ 
            if(is_array($str)){ 
                $res = array(); 
                foreach ($str as $k=>$v){ $res[$k] = self::removeTurkishChars($v); } 
                return $res; 
            }else if(strlen("".$str)>0){  
                $s = str_replace(array("Ğ","Ü","Ş","İ","Ö","Ç","ğ","ü","ş","ı","ö","ç","Ä","ä","Ё","ё"), array("G","U","S","I","O","C","g","u","s","i","o","c","A","a","E","e"), $str); 
                $englishString = @iconv('UTF-8', 'ASCII//TRANSLIT', $s);
                if ($englishString !== false) {
                    $s = $englishString;
                }
                return $s;
            } 
            return "";
        }
        public static function toLowerTurkish($input){ if (is_array($input)){ $arr = array(); foreach ($input as $k=>$v){ $arr[$k] = self::toLowerTurkish($v); } return $arr;}else{ return mb_strtolower(str_replace( array("Ğ","Ü","Ş","I","İ","Ö","Ç"),array("ğ","ü","ş","ı","i","ö","ç"), $input),"UTF-8"); } }
        public static function toUpperTurkish($input){ if (is_array($input)){ $arr = array();foreach ($input as $k=>$v){ $arr[$k] = self::toUpperTurkish($v); } return $arr; }else{ return mb_strtoupper( str_replace(array("ğ","ü","ş","ı","i","ö","ç"), array("Ğ","Ü","Ş","I","İ","Ö","Ç"), $input),"UTF-8");} }
        public static function toUpperEng($str){
            if ($str==""){ return ""; }
            if(is_array($str)){ $r = array();foreach ($str as $k=>$v){ $r[$k]  = self::toUpperEng($v); } return $r; }
            return strtoupper(self::removeTurkishChars($str));
        }        
        public static function toEng($str){
            return self::removeTurkishChars($str);
        }
        public static function toLowerEng($str){ 
            if(!function_exists("mb_strtolower")){
                return str_replace(array("Ğ","Ü","Ş","İ","Ö","Ç","ğ","ü","ş","ı","ö","ç","Ë"), array("G","U","S","I","O","C","g","u","s","i","o","c","E"), "".$str);
            }            
            return mb_strtolower(self::removeTurkishChars($str)); 
        }
        public static function startsWithNumber($str) { return preg_match('/^\d/', $str) === 1; }
        public static function changeArrayValuesToVarName($arr){
            $a = array();
            foreach ($arr as $k=>$v){
                if(is_scalar($v)){
                    $a[$k] = self::toVariableName($v);
                }else{
                    $a[$k] = $v;
                }                
            }
            return $a;
        }
        public static function toVariableName($str,$defVal=null,$typeOptions=null){
            $strVar = $str;
            if(Options::ensureParam($typeOptions) && $typeOptions instanceof Options){    
                $template   = $typeOptions->getAsString("template","");
                $toLower    = $typeOptions->getAsBool("to_lower",true);
                $canUseDots = $typeOptions->getAsBool("use_dots",false);
                $canUseDash = $typeOptions->getAsBool("use_dash",false);
                $maxLength  = $typeOptions->getAsInt("max_length",0);
                $be_smart   = $typeOptions->getAsBool("be_smart",false);
                $replaceUnderScore = $typeOptions->getAsBool("replace_underscore",false);
                $doNotStartWithNumber = $typeOptions->getAsBool("do_not_start_with_number",true);
                if($template && $template=="file"){
                    $toLower    = false;
                    $canUseDots = true;
                    $canUseDash = true;
                    $maxLength  = 200;
                    $doNotStartWithNumber = false;
                }else if($template && $template=="index"){
                    $toLower    = false;
                    $canUseDots = false;
                    $canUseDash = false;
                    $maxLength  = 200;
                    $doNotStartWithNumber = true;                    
                }else if($template && $template=="url"){
                    $toLower    = false;
                    $canUseDots = true;
                    $canUseDash = true;
                    $maxLength  = 200;
                    $doNotStartWithNumber = false;
                    $replaceUnderScore = true;
                }                
                $strVar  = str_replace("\xEF\xBB\xBF",'',"".$strVar);
                $strVar  = trim($strVar);
                if($be_smart){
                    $strVar = str_replace(array("'",'"',"-","+"," ","\r","\n"), array("_","_","_","_","_","_","_"), $strVar);
                    $strVar = str_replace(array("____","___","__"), array("_","_","_"), $strVar);
                    $strVar = str_replace(array("__"), array("_"), $strVar);                    
                }
                if(V::isEmptyString($strVar) && $typeOptions->getAsBool("force")){  $strVar = "vnone"; }                
                if ($toLower){ 
                    $strVar = self::toLowerEng($strVar);                    
                }else{
                    $strVar = self::removeTurkishChars($strVar); 
                }                
                if($canUseDots){
                    $strVar = str_replace(array("'",'"'), array("",""), $strVar);
                }else if($canUseDash){
                    $strVar = str_replace(array("'",'"'), array("",""), $strVar);
                }else{
                    $strVar = str_replace(array("'",'"',"-","+"), array("","","_neg","_pos"), $strVar);
                }        
                $regex = "/[^a-z_\\-0-9]/i";
                if($canUseDots){ $regex = "/[^a-z_\\-0-9\\.]/i"; }
                $strVar = preg_replace($regex, "_", $strVar);
                if($canUseDots){
                    $strVar = str_replace(array("-_","+_"), array("-","+"), $strVar);   
                }
                if($be_smart){                    
                    $strVar = str_replace(array("____","___","__"), array("_","_","_"), $strVar);
                    $strVar = str_replace(array("__"), array("_"), $strVar);
                    $strVar = str_replace(array("__"), array("_"), $strVar);
                    if(substr($strVar, -1)=="_"){
                        $strVar = substr($strVar, 0,-1);
                    }
                }                
                if ($doNotStartWithNumber && self::startsWithNumber($strVar)){ $strVar = "v_".$strVar; }        
                if (strlen($strVar)>0){ $ret = $strVar; }
                if ($maxLength>0 && strlen($ret)>$maxLength){ $ret = substr($ret, 0,$maxLength); }
                if (strlen($strVar)>2 && substr($strVar, -1)=="_"){ $ret = substr($ret,0,-1); }
                if($replaceUnderScore){
                    $strVar = str_replace(array("__","_"), array("-","-"), $strVar);
                }
                $exlude = $typeOptions->getAs("exclude",array(),CastUtil::$DATA_ARRAY);                
                if($exlude && is_array($exlude) && count($exlude)>0  && in_array($strVar,$exlude)){
                    $tmp = $strVar;$i=0;                    
                    do{
                        $i++;
                        $tmp = $strVar."_".$i;
                        if(!in_array($tmp,$exlude)){
                            $strVar = "".$tmp;
                            break;
                        }    
                    }while($i<1000 && !in_array($strVar,$exlude));
                }
                return $strVar;
            }
            return $strVar;            
        }
        public static function normalizedString($str,$search){
            $replace= array();
            foreach($search as $v){$replace[] = "";}
            return str_replace($search,$replace,$str);
        }
    }
}
?>