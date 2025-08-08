<?php
namespace Efaturacim\Util\Utils\Date;

use DateTime;
use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\Number\NumberUtil;
use Efaturacim\Util\Utils\String\StringSplitter;

class DateUtil{
        public static function now($offsetSec=null,$offsetDay=null){
            $a = new SmartDate("now");
            if($offsetSec!=null){
                $a->addRemoveSecond($offsetSec);
            }
            if($offsetDay!=null){
                $a->addRemoveDay($offsetDay);
            }
            
            return $a;
        }
        public static function dbDateToFormDateTime($dbDate,$defVal=""){
            if($dbDate && !is_null($dbDate) && is_string($dbDate)){
                return (new SmartDate($dbDate))->toFormDateTime();
            }
            return $defVal;
        }
        public static function dbDateToFormDate($dbDate,$defVal=""){
            if($dbDate && !is_null($dbDate) && is_string($dbDate)){
                $a = (new SmartDate($dbDate));
                return ($a->isOK()) ? $a->toFormDate() : $defVal;
            }
            return $defVal;
        }
        public static function getAgeInYear($dbDate,$defVal=0,$decimal=-1){
            if($dbDate && !is_null($dbDate) && is_string($dbDate)){
                $a = (new SmartDate($dbDate));
                return $a->getAge(null,$defVal);
            }
            return $defVal;
        }
        public static function nowStr(){
            return date("Y-m-d H:i:s");
        }
        public static function getDayOfWeek($dateStr="now"){
            return SmartDate::newDate($dateStr)->getDayOfWeek();
        }
        public static function isWeekend($dateStr="now"){
            return SmartDate::newDate($dateStr)->isWeekend();
        }
        public static function monthName($dateStr){
            $d = SmartDate::newSmart($dateStr);
            if($d && $d->isOK()){
                return $d->getMonthName();
            }
            return "";
        }
        public static function year($dateStr){
            $d = SmartDate::newSmart($dateStr);
            if($d && $d->isOK()){
                return $d->getYear();
            }
            return "";
        }
        public static function getElapsedTimeInseconds($str,$useAbs=true,$otherDate=null){
            $a = new SmartDate($str);
            if($a->isOK()){
                return $a->getElapsedTimeInSeconds($useAbs,$otherDate);
            }
            return 0;
        }
        public static function notEmpty($dateObj){
            if(!is_null($dateObj) && $dateObj && $dateObj instanceof SmartDate){
                return true;
            }
            return false;
        }
        public static function isDateOrDateString($val){
            if(!is_null($val)){
                if($val && $val instanceof SmartDate){
                    return $val->getYear()>0;
                }else if(is_string($val) && strlen("".$val)>0){
                    $d = SmartDate::newDate($val);
                    return $d->getYear() > 0;
                }
            }
            return false;
        }
        public static function vadeGun($val,$suffix=null,$sign=1,$isAbs=false,$sonGun=null){
            if($val && !is_null($val)){
                $d = new SmartDate($val);       
                $vade = SmartDate::newDate(is_null($sonGun)? "now" : $sonGun)->getDayDifference($d,$isAbs,$sign);
                if(!is_null($vade)){
                    return $vade."".$suffix;
                }
                return $vade;
            }
            return "";
        }
        public static function isElapsedTimeGreaterThan($dateString,$elapsedInSecs,$default=true){
            if(is_null($dateString) || $dateString==""){ return $default; }
            $t = new SmartDate($dateString);
            $e = $t->getElapsedTimeInSeconds(false);
            return $e>=$elapsedInSecs;
        }
        public static function isDayOfWeek($dayStr,$dayIndex){
            $t = new SmartDate($dayStr);
            $di = $t->getDayOfWeek();
            //die("\n\n".$dayIndex."=".($di%7)." ? ".$di."\n\n");
            if(is_array($dayIndex) && in_array($di, $dayIndex)){
                return true;
            }else if (is_int($dayIndex)){
                $dayIndex=($dayIndex%7);                
                return $dayIndex==$di;
            }            
            return false;
        }
        public static function isFirstWeekdayOfMonth($dayStr,$dayIndex=1){
            $t = new SmartDate($dayStr);  
            $di         = $t->getDayOfWeek();
            $dayIndex   = ($dayIndex%7);
            $dayOfMonth = 0 + $t->getDayIndex();            
            if($dayIndex==$di && $dayOfMonth<=7){
                //die("\n\n\nTRUE => ".$dayIndex." ? ".$di." / ".$dayOfMonth."\n\n");
                return true;
            }            
        }
        public static function isFirstSundayOfMonth($dayStr){
            return self::isFirstWeekdayOfMonth($dayStr, 0);
        }
        /**
         *
         * @return SmartDate|null
         */
        public static  function newDate($str){
            return new SmartDate($str);
        }
        /**
         *         
         * @return SmartDate|null
         */
        public static  function fromDateAsInt($val){
            if(is_int($val) && $val>=10000000){
                $str  = substr("".$val, 0,4)."-".substr("".$val, 4,2)."-".substr("".$val, 6,2);
                return self::newDate($str);
            }
            return null;        
        }
        public static function wait($timeInSecs=0){
            $t = round(1000000*$timeInSecs);
            if($t>0){
                @usleep($t);
            }
        }
        public static function coalesce($arguments=null){
            $args = func_get_args();
            foreach ($args as $v){
                if(!is_null($v)){ 
                    if($v && $v instanceof DateUtil){
                        return $v;
                    }else if(is_string($v)){
                        $t = DateUtil::newDate($v);
                        return $t;
                    }
                    return $v;
                }
            }
            return null;            
        }
        public static function getIntervalFromString($str=null){
            if(!is_null($str) && strlen("".$str)>0){
                $chr1 = substr_count($str, "-");
                $chr2 = substr_count($str, " / ");
                $chr3 = substr_count($str, "/");
                $temp = array();
                if($chr1==1){
                    $temp = preg_split("/-/is", $str,2,PREG_SPLIT_NO_EMPTY);
                }else if($chr3==1){
                    $temp = preg_split("/\\//is", $str,2,PREG_SPLIT_NO_EMPTY);
                }else if($chr2==1){
                    $temp = preg_split("/ \\/ /is", $str,2,PREG_SPLIT_NO_EMPTY);
                }
                if($temp && count($temp)==2){
                    $t1 = SmartDate::newDate(trim("".$temp[0]));
                    $t2 = SmartDate::newDate(trim("".$temp[1]));
                    if($t2->isBiggerOrEqual($t1)){
                        return array($t1,$t2);
                    }
                }
            }
            return array(null,null);
        }
        public static function sleep($seconds=1){
            $microseconds = round($seconds*1000000);
            if($microseconds>0){ usleep($microseconds); }            
        }
        public static function emptyDate(){
            $a = new SmartDate();
            $a->clear();
            return $a;
        }
        public static function getAsDate($dateStringOrObj,$minYear=null){
            if(is_null($dateStringOrObj) || $dateStringOrObj==""){
                return self::emptyDate();
            }
            $t =  new SmartDate($dateStringOrObj);
            if(!is_null($minYear) && $t->getYear()<$minYear){
                return self::emptyDate();
            }
            return $t;
        }
        public static function isDateInBetween($t,$t1,$t2){
            if(! $t instanceof SmartDate){ $t = DateUtil::newDate($t); }
            if($t instanceof SmartDate && $t->isBiggerOrEqual($t1) && $t->isSmallerOrEqual($t2) ){
                return true;
            }
            return false;
        }
        public static function getElapsedDay($t2,$t1=null){            
            $start = SmartDate::newDate(is_null($t1)?"now":$t1);            
            $end   = SmartDate::newDate($t2);    
            if(!is_null($start) && !is_null($end) && !is_null($start->toPhpDateTime()) && !is_null($end->toPhpDateTime())){
                $diff  = date_diff($start->toPhpDateTime(), $end->toPhpDateTime());
                return $diff->days;                        
            }            
            return 0;
        }
        public static function isDateEquals($t1,$t2=null){
            if(!is_null($t1)){
                $start = SmartDate::newDate(is_null($t1)?"now":$t1);            
                $end   = SmartDate::newDate(is_null($t2)?"now":$t2);                    
                return $start->toDbDate() === $end->toDbDate();
            }
            return false;
        }
        public static function isDateTimeBiggerThan($date1,$date2,$defVal=false){
            return self::getBoolValue($date1,$date2,$defVal,">");
        }
        public static function isDateTimeSmallerThan($date1,$date2,$defVal=false){
            return self::getBoolValue($date1,$date2,$defVal,"<");
        }
        public static function isDateBiggerThan($date1,$date2,$defVal=false){
            return self::getBoolValue($date1,$date2,$defVal,">>");
        }
        public static function isDateSmallerThan($date1,$date2,$defVal=false){
            return self::getBoolValue($date1,$date2,$defVal,"<<");
        }
        public static function getBoolValue($date1,$date2,$defVal=false,$operand=""){
            $d1 = DateUtil::newDate($date1);
            $d2 = DateUtil::newDate($date2);
            if(!is_null($d1) && !is_null($d2)){
                if($operand==">"){
                    return $d1->toDbDateTime() > $d2->toDbDateTime();
                }else if($operand==">>"){    
                    return $d1->toDbDate() > $d2->toDbDate();
                }else if($operand=="<"){
                    return $d1->toDbDateTime() < $d2->toDbDateTime();
                }else if($operand=="<<"){    
                    return $d1->toDbDate() < $d2->toDbDate();
                }                
            }
            return $defVal;
        }
        public static function getDatesInBetweeen($d1,$d2,$options=null){
            return DateUtil::getDatesInBetweeen($d1,$d2,$options);            
        }
        public static function isValidDate($dateObject,$checkForString=false){
            if($dateObject && $dateObject instanceof SmartDate && $dateObject->isOK()){
                return true;
            }else if (is_string($dateObject) && $checkForString && strlen("".$dateObject)>0 &&  SmartDate::newDate($dateObject)->isOK() ){
                return true;
            }   
            return false;
        }        
        public static function isDateOk($dateStringOrObject){
            return self::isValidDate($dateStringOrObject,true);
        }
        public static function newDateAs($year=null,$month=null,$day=null,$hour=0,$minute=0,$second=0){            
            $year  = NumberUtil::coalesce($year,date("Y"));
            $month = NumberUtil::coalesce($month,1);
            $day   = NumberUtil::coalesce($day,1);
            $hour  = NumberUtil::coalesce($hour,12);
            $minute= NumberUtil::coalesce($minute,0);
            $second= NumberUtil::coalesce($second,0);
            $dateStr =  sprintf("%04d-%02d-%02d %02d:%02d:%02d", $year, $month, $day, $hour, $minute, $second);            
            return new SmartDate($dateStr);
        }
        public static function newDateFromTimestamp($timestamp){
            if(is_numeric($timestamp) && $timestamp>0){
                return new SmartDate(date("Y-m-d H:i:s",$timestamp));
            }
            return null;
        }
 public static function smartCastToDateObject($str,$defVal=null,$dataType=null,$options=null,$applyType=null){            
            $dateObject = new SmartDate();  
            $tryHarder  = true;                   
            if($str && $str instanceof SmartDate && $str->isOK()){
                $dateObject->copyFrom($str);
                return $dateObject->isOK() ? $dateObject : $defVal;
            }else if((is_string($str) && $str==="now") || (is_null($str) && $defVal=="now")){
                $dateObject->initNow();
                $dateObject->orgString = $dateObject->toDbDateTime();
                return $dateObject->isOK() ? $dateObject : $defVal;
            }else if(is_string($str) && strlen($str)>0){
                $pDot = strpos($str,".");                
                if($pDot!==false){                               
                    if(substr_count($str,".")>=2){
                        $tmp = preg_split("/[ \\.:]/is", $str,6,PREG_SPLIT_NO_EMPTY);
                        if(count($tmp)>=3){
                            $dateString = substr("0000".@$tmp[2], -4)."-".substr("0000".@$tmp[1], -2)."-".substr("00".@$tmp[0], -2);
                            if(count($tmp)>3){
                                $dateString .= " ".substr("0000".@$tmp[3], -2).":".substr("0000".@$tmp[4], -2).":".substr("00".@$tmp[5], -2);
                            }
                            $t = new SmartDate($dateString,null,null,$dataType,$str);                            
                            return $t;
                        }
                    }                    
                }
                $p1 = strpos($str,"-");                
                if($p1!==false){                    
                    $p2 = strpos($str,"-",$p1);
                    if($p2!==false){
                        $dateObject = new SmartDate($str,null,null,$dataType,$str);
                        if($dateObject->isOK()){ return $dateObject; }
                    }
                }
            }            
            if($tryHarder && !$dateObject->isOK()){                         
                if(is_int($str) || is_float($str)){
                    $dateString = self::getAsExcelDate($str,null,null);                    
                    if($dateString && strlen($dateString)>0){
                        $dateObject = new SmartDate($dateString,null,null,$dataType);
                    }                    
                }else if(NumberUtil::isNumber($str) && $str>0){                    
                    $dateString = self::getAsExcelDate(CastUtil::getAs($str,0,CastUtil::$DATA_NUMBER),null,null);                    
                    if($dateString && strlen($dateString)>0){
                        $dateObject = new SmartDate($dateString,null,null,$dataType);
                    }                    
                }else{
                    $tmp = StringSplitter::splitWithSpash($str);                                         
                    if(count($tmp)==3 && NumberUtil::isInt(@$tmp[0]) && NumberUtil::isInt(@$tmp[1]) && NumberUtil::isInt(@$tmp[2]) && @$tmp[2]>=1900 ){                        
                        $strDate    = substr("0000".@$tmp[2],-4)."-".substr("0000".@$tmp[1],-2)."-".substr("0000".@$tmp[0],-2);
                        $dateObject = new SmartDate($strDate);                        
                    }                    
                }
            }            
            return $dateObject->isOK() ? $dateObject : $defVal;
        }
        public static function getAsExcelDate($dateVal,$format=null,$defVal=null){
            if(is_float($dateVal) || is_int($dateVal)){            
                $UNIX_DATE1 = ($dateVal - 25569) * 86400;
                $excel_date = 25569 + ($UNIX_DATE1 / 86400);
                $UNIX_DATE = ($excel_date - 25569) * 86400;            
                if(is_null($format)){ 
                    if(($dateVal-floor($dateVal))>0.000001){
                        $format = "Y-m-d H:i:s";
                    }else{
                        $format = "Y-m-d";
                    }                 
                }
                //GAPP::dumpVar(array($dateVal,$UNIX_DATE1,$UNIX_DATE,@gmdate($format,$UNIX_DATE)));
                if($UNIX_DATE>0){
                    if($UNIX_DATE>=0){                                        
                        return @gmdate($format,$UNIX_DATE);
                    }
                }else if($UNIX_DATE<0){
                    $newDateTimeStamp = (-1*$UNIX_DATE);
                    $a = new DateTime("1970-01-01");
                    $a->modify("-".$newDateTimeStamp." second");
                    return $a->format($format);
                }else{
                    return $defVal;
                }
            }
            return $defVal;
        }        
        public static function getAge($dateObj,$refDateStr=null,$defVal=0,$decimal=-1){
            $a = (is_null($refDateStr)) ? new SmartDate("now") : new SmartDate($refDateStr);
            if(is_string($dateObj)){ $dateObj = DateUtil::newDate($dateObj); }
            if($dateObj && $dateObj instanceof SmartDate){
                $elapsed = $dateObj->getElapsedTimeInSeconds(false,$a);
                if($elapsed>0){
                    $age = ($elapsed)/(86400*365.25);
                    if($decimal==-1){
                        return floor($age);
                    }else if ($decimal>=0){
                        return round($age,$decimal);
                    }
                    return $age;
                }                
            }
            return $defVal;
        }        
        public static function getWorkDayDifference($date1,$date2,$isAbsolute=false,$calismaGunleri=null,$tatiller=null,$countStartDay=true,$countEndDate=true){
            if($calismaGunleri && $calismaGunleri=="all"){ $calismaGunleri =  [1, 2, 3, 4, 5,6,7]; }
            if(is_null($calismaGunleri) || !is_array($calismaGunleri) || count($calismaGunleri)==0){ $calismaGunleri =  [1, 2, 3, 4, 5];  }
            $date1 = DateUtil::newDate($date1);
            $date2 = DateUtil::newDate($date2);
            return count(self::getDaysInBetween($date1,$date2,$calismaGunleri,$tatiller,$isAbsolute,$countStartDay,$countEndDate));
        }
public static function getDaysInBetween($date1,$date2,$calismaGunleri=null,$tatiller=null,$isAbsolute=true,$countStartDay=true,$countEndDate=true){
            $date1 = DateUtil::newDate($date1);
            $date2 = DateUtil::newDate($date2);
            $list = array();
            //calismaGunleri - 1:pzt,2:sal - bos gelirse tum gunler
            //$tatiller - '*-12-25', '*-01-01', '2022-12-23'
            if(is_null($calismaGunleri) || !is_array($calismaGunleri) || count($calismaGunleri)==0){ $calismaGunleri =  [1, 2, 3, 4, 5,6,7];  }
            if(is_null($tatiller) || !is_array($tatiller)){ $tatiller = array(); }
            $from = new DateTime($date1->toDbDate());
            $toOrg = new DateTime(SmartDate::newDate($date2)->toDbDate());
            $to    = new DateTime(SmartDate::newDate($date2)->toDbDate());            
            $to->modify('+1 day');
            
            $interval = new \DateInterval('P1D');
            if($isAbsolute && $from->format("Y-m-d H:i:s") >=  $to->format("Y-m-d H:i:s")){
                $periods  = new \DatePeriod($to, $interval, $from);
            }else{
                $periods  = new \DatePeriod($from, $interval, $to);
            }            
            if($countStartDay){
                if (!in_array($from->format('N'), $calismaGunleri)){
                    
                }else if (in_array($from->format('Y-m-d'), $tatiller)){
                    
                }else if (in_array($from->format('*-m-d'), $tatiller)){
                    
                }else{
                    $list[] = $from->format('Y-m-d');
                }                
            }
            
            foreach ($periods as $period) {                
                if(!$countStartDay && $period->format('Y-m-d')==$from->format('Y-m-d')) { continue;}            
                if(!$countEndDate && $period->format('Y-m-d')==$toOrg->format('Y-m-d')) { continue;}
                if (!in_array($period->format('N'), $calismaGunleri)) continue;
                if (in_array($period->format('Y-m-d'), $tatiller)) continue;
                if (in_array($period->format('*-m-d'), $tatiller)) continue;
                $d = $period->format('Y-m-d');
                if(!in_array($d, $list)){ $list[] = $d; }                 
            }            
            //\Vulcan\V::dump($list);
            return $list;
        }        
        public static function getAsDbDate($str,$defVal=null){
            if(is_null($str)){ $str = $defVal; }
            return DateUtil::newDate($str)->toDbDate();
        }
    }
    ?>