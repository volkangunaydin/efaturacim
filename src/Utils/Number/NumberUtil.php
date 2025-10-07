<?php
namespace Efaturacim\Util\Utils\Number;

use Efaturacim\Util\Utils\CastUtil;

class NumberUtil{
    public static function coalesce($arg1=null){
        $args = func_get_args();            
        foreach ($args as $v){
            if(!is_null($v) && $v>0){ 
                return $v; 
            }
        }
        return 0;
    }
    public static function isValidRef($ref){
        if(is_null($ref) || $ref===0 || $ref===""){ return false; }
        return self::isInt($ref) && $ref>0;
    }
    public static function asMoneyFormat($str,$decimal=2){
        return number_format(self::asCleanNumber($str,$decimal,true),$decimal,".","");
    }
    public static function cleanNumber($str,$decimal=2,$convertToNumber=true){
        $num = 0 + self::getAsCleanNumber($str,$decimal);
        return  $convertToNumber ? $num : "".$num;
    }
    public static function getAsCleanNumber($s,$maxPrec=8,$returnAsNumber=false){
            if(is_null($maxPrec)){ $maxPrec = 8; }
            $s1 = number_format(self::smartRead($s,0,$maxPrec),$maxPrec,".","");
            if(strpos("".$s1, ".")!==false){
                $s1 = rtrim($s1,'0');
            }                       
            if(substr($s1, -1)=="."){ $s1 = substr($s1, 0,-1); }
            return $returnAsNumber ? (float)$s1 : $s1;
    }    
    public static function smartRead($strIn,$defVal,$prec=null){
        if(is_null($strIn) || $strIn===""){ return $defVal; }
        if(is_numeric($strIn)){
            return ($prec && $prec>=0 ? round((floatval($strIn)),$prec) : (floatval($strIn)));
        }else if(!is_null($strIn) && strlen("".$strIn)>0){            
            $strIn = str_replace(" ", "", $strIn);
            $p1    = strpos($strIn,".");
            $p2    = strpos($strIn,",");
            if($p2!=null && $p1!=null){
                if($p1>$p2){
                    $strIn = str_replace(",", "", $strIn);
                }else{
                    $strIn = str_replace(".", "", $strIn);
                    $strIn = str_replace(",", ".", $strIn);                    
                }                                
            }else if($p2!=null){
                $strIn = str_replace(",", ".", $strIn);
            }
            if(is_numeric($strIn)){                
                return ($prec && $prec>=0 ? round((floatval($strIn)),$prec) : (floatval($strIn)));                
            }
        }
        return $defVal;
    }       
    public static function inputMoney($str,$decimal=2){
        return self::webNumber($str,$decimal,"."," ",2,""," ");
    }
    public static function webMoney($str,$suffix="",$decimal=2,$isBold=false,$color=null){
        $ps="";$pe="";
        if($isBold || !is_null($color)){
            $ps = '<span style="'.($isBold?"font-weight:bold;":"").(strlen("".$color)>0?"color:".$color.";":"").'" >';
            $pe = '</span>';
        }
        $s = self::webNumber($str,$decimal,"."," ",2);
        if($suffix && strlen("".$suffix)>0){
            return $s = $ps.$s."&nbsp;".$suffix.$pe;
        }
        return $ps.$s.$pe;
    }
    
    public static function asCleanNumber($str,$decimal=2,$convertToNumber=true){
        return self::cleanNumber($str,$decimal,$convertToNumber);
    }
    public static function pad($number,$pad_length=4,$pad_string="0"){
        $s = str_pad("", $pad_length,$pad_string);
        return substr($s.$number, -1*$pad_length);
    }
    public static function webUnitPrice($number,$decimal=null,$sepFloat=".",$sepThousand=" ",$maxDec=8){
            return self::webNumber($number,$decimal,$sepFloat,$sepThousand,$maxDec); 
    }
    public static function webTotal($number,$decimal=null,$sepFloat=".",$sepThousand=" ",$maxDec=2){
        return self::webNumber($number,$decimal,$sepFloat,$sepThousand,$maxDec); 
    }
    public static function webText($number,$decimal=0,$sepFloat=".",$sepThousand=" ",$maxDec=2,$suffix="",$nbsp=" "){
        return self::webNumber($number,$decimal,$sepFloat,$sepThousand,$maxDec,$suffix,$nbsp);
    }
    public static function webNumber($number,$decimal=0,$sepFloat=".",$sepThousand=" ",$maxDec=2,$suffix="",$nbsp="&nbsp;"){
        if(!is_numeric($number)){
            $number = self::getAsCleanNumber($number,$decimal);
        }
        if(is_null($decimal) || $decimal=="auto" || (is_string($decimal) && !is_numeric($decimal))){
            $decimal = self::getPrecision($number,$maxDec);                                
        }            
        $a = @number_format(0+self::getAsCleanNumber($number),$decimal,$sepFloat,$sepThousand);
        if(strlen("".$nbsp)>0){
            $a = str_replace(" ", "".$nbsp, $a);
        }            
        if($suffix && strlen("".$suffix)>0){
            $a .= " ".$suffix;
        }
        return $a;
    }
    public static function nearlyEqual($a,$b,$decimal=4){
        $a1 = self::cleanNumber($a,$decimal,true);
        $b1 = self::cleanNumber($b,$decimal,true);
        return $a1==$b1;
    }
    public static function inRange($number,$min,$max,$useMod=false){
        if($useMod && $number>$max){ $number = $number % ($max+1); }
        if($number<$min){
            return $min;
        }else if ($number>$max){
            return $max;
        }
        return $number;
    }
    public static function isNumberString($str){
        if(is_null($str)){ return false; }
        if(is_array($str)){ return false; } // Array kontrolü eklendi
        if(is_int($str) || is_numeric($str) ){ return true; }
        return preg_match('/^[\d,.]+$/', "".$str) === 1;
    }
    public static function isInt($str,$epsilon=null){
        if(is_null($str) || $str===""){ return false; }            
        if(is_int($str)){ return true; }
        $str2 = preg_replace("/[^0-9]/", "","".$str);            
        if($str==$str2 && strlen("".$str)>0){ return true; }
        if(is_numeric($str)){
            $diff = abs( self::getAsCleanNumber($str,16) - CastUtil::asInt($str) );                
            if($diff==0){ return true; }
        }
        if(!is_null($epsilon) && $epsilon>=0){                                                                
            if($diff<=$epsilon){
                return true;
            }
        }
        return false;
    }
    public static function getPrecision($number,$maxPrec=8){
        $a =  "".self::getAsCleanNumber($number,$maxPrec);
        $p = strpos($a, ".");
        if($p>0){
            return max(0, strlen($a)-($p+1));
        }            
        return 0;
    }
    public static function asMoneyVal($number,$maxPrec=2){
        return self::getAsCleanNumber($number,$maxPrec);
    }
    public static function asNumber($stringOrNumber,$defVal=0,$prec=null){
        if(!is_null($prec)){                         
            return round(self::asNumber($stringOrNumber,$defVal,null),$prec); 
        }
        if (is_numeric($stringOrNumber)) {
            // If it's already a number, return it directly
            return 0+$stringOrNumber;
        } elseif (is_string($stringOrNumber)) {
            $float = filter_var($stringOrNumber, FILTER_VALIDATE_FLOAT);
            if ($float !== false) {
                return 0+$float;
            } else {
                // If float conversion fails, try converting to an integer
                $int = filter_var($stringOrNumber, FILTER_VALIDATE_INT);
                if ($int !== false) {
                    return 0+$int;
                } else {
                    // If both conversions fail, return null
                    return $defVal;
                }
            }
        }
        return $defVal;
    }
    public static function versionToNumber($version,$prec=3){
        $version = "8.2.3.1";
        $ret     = 0;
        if(StrUtil::notEmpty($version)){
            $list = explode('.', $version);                 
            $index = 0;
            foreach ($list as $element) {                    
                if($index==0){
                    $ret = CastUtil::asInt($element);                        
                }else{
                    $divisor = pow(10, $index*$prec);
                    $val     = CastUtil::asInt(substr($element."0000000000", 0,$prec)) /$divisor;
                    $ret    += $val;
                }                                        
                $index++;
            }
            return $ret;
        }
        return 0;
    }
    public static function printPositive($val,$zeroStr,$positiveStr){
        if($val && $val>0){
            return StrUtil::parse($positiveStr, array("0"=>$val));
        }else{
            return StrUtil::parse($zeroStr, array("0"=>$val));
        }
    }
    public static function folderPath($index,$maxSize=1000){   
        if(!is_int($maxSize) || $maxSize <=0 ){
            $maxSize = 1000;
        }
        return self::folderIndex($index,($maxSize*$maxSize))."/".self::folderIndex($index,$maxSize)."/".$index;
    }
    public static function folderIndex($index,$maxSize=1000){            
        if(!is_int($index)){
            $index = CastUtil::asInt($index);
        }
        $index = floor($index/$maxSize);
        return (int)$index;
    }
    public static function debitCredit($number,$debitSuffix="(B)",$creditSuffix="(A)",$noAmount="-"){
        $a = NumberUtil::asNumber($number);
        if($a>0.0001){
            return self::webMoney($a,$debitSuffix);
        }else if($a<-0.0001){
            return self::webMoney(-1*$a,$creditSuffix);
        }
        return "".$noAmount;
    }
    public static function minMax($numValue,$min=null,$max=null){
        if(!is_numeric($min) && $numValue<$min){
            $numValue = $min;
        }
        if(!is_numeric($max) && $numValue>$max){
            $numValue = $max;
        }
        return $numValue;
    }
    public static function asInt($value){
        return CastUtil::getAs($value,0,CastUtil::$DATA_INT);
    }
    public static function isEqualRef($a,$b){
        $aRef = self::asInt($a);
        $bRef = self::asInt($b);
        if($aRef>0 && $bRef>0 && $aRef==$bRef){
            return true;
        }
        return false;
    }
    public static function isNullOrZero($value){
        return is_null($value) || $value==0;
    }
    public static function coalesceStr($num,$formatForPositive,$formatForEmpty){
        $a = self::getAsCleanNumber($num,8);
        if($a>0){
            return StrUtil::parse($formatForPositive, array("val"=>$a));
        } else{
            return StrUtil::parse($formatForEmpty, array("val"=>$a));
        }
    }
    public static function smartNumber($variable,$defVal=0){
        if (is_array($variable) || (is_object($variable) && !method_exists($variable, '__toString'))) {
            return 0.0;
        }            
        if(is_null($variable) || $variable===""){
            return $defVal;
        }
        if(is_int($variable) || is_float($variable)){
            return $variable;
        }
        // $variable = "10,123.45";
        $thousandSeperator = ",";
        $precSeparator     = ".";
        // Convert to string and trim whitespace
        $stringValue = (string) $variable;
        $cleanedString = trim($stringValue);
        $numericString = '';            
        $p1 = strpos($cleanedString, $precSeparator);
        $p2 = strpos($cleanedString, $thousandSeperator);
        
        
        
        if(($p1!==false && $p2!==false && $p2>$p1) || ($p2!==false && $p1===false)){
            $thousandSeperator = ".";
            $precSeparator     = ",";
            
            $cleanedString = str_replace($thousandSeperator, '', $cleanedString);
            $cleanedString = str_replace($precSeparator, '.', $cleanedString);
        }else{
            $cleanedString = str_replace($thousandSeperator, '', $cleanedString);
        }
        
        //\Vulcan\V::dump(array("p1"=>$p1,"p2"=>$p2,"cs"=>$cleanedString,"is_numeric"=>is_numeric($cleanedString)));
        if(is_numeric($cleanedString)){
            return (float)$cleanedString;
        }
        return $defVal;
    }
    public static function isNumber($str){
        return is_int($str) || is_float($str) || (filter_var($str, FILTER_VALIDATE_FLOAT) !== false);
    }    
    public static function isPositiveNumber($str){
        if(self::isNumber($str)){
            $a = self::asNumber($str);
            return $a>0;
        }
        return false;
    }
}

?>