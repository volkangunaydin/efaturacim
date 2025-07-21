<?php
namespace Efaturacim\Util;

use DateTime;
use Exception;

class SmartDate{
        /** @var DateTime */
        public $dateTime = null;        
        private $__isok   = false;
        private $dataType = null;
        private $lang     = "tr";
        private $timeZone = 3;
        public  $orgString = null;
        public function __construct($str=null,$timezone=null,$applyType=null,$dataType=null,$orgStr=null){
            if($str && is_object($str) && $str instanceof SmartDate){
                $str = $str->toDbDateTime();
            }
            if($str && is_float($str)){
                $dateVal    = 0 + $str;
                $UNIX_DATE1 = ($dateVal - 25569) * 86400;
                $excel_date = 25569 + ($UNIX_DATE1 / 86400);
                $UNIX_DATE = ($excel_date - 25569) * 86400;
                $format = "Y-m-d H:i:s";
                if($UNIX_DATE>0){
                    if($UNIX_DATE>=0){
                        $str =  @gmdate($format,$UNIX_DATE);
                    }
                }else if($UNIX_DATE<0){
                    $newDateTimeStamp = (-1*$UNIX_DATE);
                    $a = new DateTime("1970-01-01");
                    $a->modify("-".$newDateTimeStamp." second");
                    $str =  $a->format($format);
                }
            }
            if(!is_null($str) && $str=="now"){
                $this->dateTime = new DateTime(date("Y-m-d H:i:s"));
                $this->__isok = true;
            }else  if(!is_null($dataType) && $dataType==CastUtil::$DATA_TIMESTAMP){
                $this->dateTime = new DateTime(date("Y-m-d H:i:s"));
                $this->dateTime->setTimestamp(CastUtil::getAs($str,0,CastUtil::$DATA_INT));                
                $this->__isok = true;
            }else if($str && is_string($str) && strlen($str)>0){                
                $t_str = substr("".$str,10,1);
                if($t_str==="T"){
                    $str = str_replace("T"," ",$str,);
                    $ps  = strpos("".$str,"+",0);
                    if($ps && $ps>0){
                        $ts  = substr($str,$ps);
                        $str = substr($str,0,$ps);
                    }                    
                }                
                try {                
                    $this->dateTime = new DateTime($str,$timezone);
                    if($this->dateTime && $this->dateTime->format("Y")>0){
                        $this->__isok   = true;
                    }                    
                } catch (Exception $e) {
                    $this->__isok = false;                    
                }    
            }else if($str instanceof SmartDate){                
                if($str->dateTime){                    
                    $this->dateTime =  new DateTime( $str->dateTime->format("Y-m-d H:i:s") );
                }else{
                    $this->dateTime =  null;
                }                
            }else{ 
                $this->dateTime = null;
                $this->__isok = false;
            }
            if(!is_null($applyType)){
                $this->applyType($applyType);
            }
            $this->dataType = $dataType;
            if(!is_null($orgStr) || is_string($orgStr) && strlen("".$orgStr)>0){
                $this->orgString = $orgStr;
            }
        }
        public function copy(){
            $a = new SmartDate($this->toDbDateTime());
            return $a;
        }
        public function copyFrom($date){
            if($date instanceof SmartDate && $date->isOK()){
                $this->dateTime = $date->dateTime;
                $this->__isok = true;
            }else{
                $this->dateTime = null;
                $this->__isok = false;
            }
            return $this;
        }        
        public function initNow(){
            $this->dateTime = new DateTime(date("Y-m-d H:i:s"));
            $this->__isok = true;
            return $this;
        }
        public function addRemove($intVal,$suffix,$intervalPrefix=null){
            $zamanInt = round($intVal);
            if(is_null($intervalPrefix)){ $intervalPrefix = "P"; }
            if($zamanInt>0){
                $this->dateTime->add(new \DateInterval($intervalPrefix.$zamanInt.$suffix));
            }else if ($zamanInt<0){
                $this->dateTime->sub(new \DateInterval($intervalPrefix.abs($zamanInt).$suffix));
            }
            return $this;
        }
        public function setTime($hour=0,$min=0,$sec=0){
            if($this->dateTime){
                $this->dateTime->setTime($hour, $min,$sec);
            }            
            return $this;
        }
        function getDaysInMonth($month=null, $year=null){
            if(is_null($year) || $year<=0){ $year = $this->getYear(); }
            if(is_null($month) || $month<=0 ){ $month = $this->getMonth(); }
            return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
        }
        public function getMonthName(){ 
            $months      = array("","Ocak","Şubat","Mart","Nisan","Mayıs","Haziran","Temmuz","Ağustos","Eylül","Ekim","Kasım","Aralık");
            $monthIndex =  $this->getMonth();
            return key_exists($monthIndex,$months) ? @$months[$monthIndex] : $monthIndex;
        }
        public function getDayName(){
            $daysOfWeek = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
            $di = $this->toFormat("w")+0;
            return $daysOfWeek[$di];
        }
        
        public function addRemoveDay($day){ return $this->addRemove($day, "D");  }
        public function addRemoveYear($year){ return $this->addRemove($year, "Y");  }
        public function addRemoveHour($hour){ return $this->addRemove($hour, "H","PT");  }
        public function addRemoveMinute($min){ return $this->addRemove($min, "M","PT");  }
        public function addRemoveSecond($sec){ return $this->addRemove($sec, "S","PT");  }
        public function addRemoveWeek($week){ return $this->addRemove($week*7, "D");  }
        public function addRemoveMonth($month){ return $this->addRemove($month, "M");  }        
        public function setDate($dayIndex=null,$monthIndex=null,$yearIndex=null,$hour=null,$min=null,$sec=null){
            if(!is_null($dayIndex) || !is_null($monthIndex) || !is_null($yearIndex)){
                $yearIndex  = $yearIndex  ? $yearIndex : $this->dateTime->format("Y")+0;
                $monthIndex = $monthIndex ? $monthIndex : $this->dateTime->format("m")+0;
                $dayIndex = $dayIndex ? $dayIndex : $this->dateTime->format("d")+0;
                $this->dateTime->setDate($yearIndex, $monthIndex, $dayIndex);
                if(!is_null($hour) && !is_null($min) && !is_null($sec)){
                    $this->setTime($hour,$min,$sec);
                }
            }          
            return $this;
        }
        public function applyType($applyType=null,$parameter=null){
            if(!is_null($applyType) && strlen($applyType)>0){
                if($applyType=="now"){
                    return $this->initNow();                    
                }else if(in_array($applyType, array("day_end","end","end_day"))){
                    $this->setTime(23,59,59);
                }else if(in_array($applyType, array("day_start","start","start_day"))){
                    $this->setTime(0,0,0);
                }else if(in_array($applyType, array("last_year"))){
                    $this->addRemove(-1, "Y")->setTime(0,0,0);
                }else if(in_array($applyType, array("last_year_start"))){
                    $this->addRemove(-1, "Y")->setDate(1,1)->setTime(0,0,0);
                }else if(in_array($applyType, array("day_start","start"))){
                    $this->setTime(0,0,0);
                }else if(in_array($applyType, array("month_start","monthstart","thismonth","start_month"))){
                    $this->setDate(1,$this->getMonth(1),$this->getYear(),0,0,0);
                }else if(in_array($applyType, array("month_end","monthend","end_month","endmonth"))){                    
                    $this->setDate($this->getDaysInMonth(),$this->getMonth(1),$this->getYear(),23,59,59);
                }else if(in_array($applyType, array("this_year","thisyear","year_start","start_year"))){
                    $this->setDate(1,1,$this->getYear(),0,0,0);
                }else if(in_array($applyType, array("year_end","end_year"))){
                    $this->setDate(31,12,$this->getYear(),23,59,59);
                }else if(in_array($applyType, array("yesterday","dun"))){
                    $this->addRemove(-1, "D");
                }else if(in_array($applyType, array("last7","last7day","7day"))){
                    $this->addRemove(-7, "D")->setTime(0,0,0);
                }else if(in_array($applyType, array("lastmonth"))){
                    $this->addRemoveMonth(-1)->setTime(0,0,0);
                }else if(in_array($applyType, array("lastmonth_end","prev_month_end"))){                    
                    $this->addRemoveMonth(-1)->applyType("month_end");
                }else if(in_array($applyType, array("lastmonth_start","prev_month_start"))){
                    $this->addRemoveMonth(-1)->applyType("month_start");                    
                }else if(in_array($applyType, array("time"))){
                    if($parameter && is_string($parameter) && strlen($parameter)>0){
                        $this->setTimeFromString($parameter);
                    }
                }else if(in_array($applyType, array("workDay"))){
                    if(is_null($parameter) || !is_array($parameter) || count($parameter)==0){ $parameter =  [1, 2, 3, 4, 5];  }
                    if (!in_array($this->toFormat('N'), $parameter)){ $this->addRemoveDay(1); };
                    if (!in_array($this->toFormat('N'), $parameter)){ $this->addRemoveDay(1); };
                }else if(in_array($applyType, array("default_start"))){
                    if(strlen("".$this->orgString)>0 && strlen("".$this->orgString)<11){
                        $this->setTime(0,0,0);
                    }
                }else if(in_array($applyType, array("default_end"))){
                    if(strlen("".$this->orgString)>0 && strlen("".$this->orgString)<11){
                        $this->setTime(23,59,59);
                    }
                }else if(in_array($applyType, array("prev_day_index")) && is_int($parameter) && $parameter>=0 && $parameter<=6){
                    $dow    = $this->getDayOfWeek();
                    $offset = (($dow+7)-$parameter) % 7;
                    $this->addRemoveDay(-1*$offset);
                }else if(in_array($applyType, array("next_day_index")) && is_int($parameter) && $parameter>=0 && $parameter<=6){
                    $dow    = $this->getDayOfWeek();
                    $offset = (($dow+7)-$parameter) % 7;
                    $this->addRemoveDay(-1*$offset);
                }else{
                    \Vulcan\V::dump("PLEASE IMPLEMENT ".$applyType." for SmartDate");
                }
            }
            return $this;
        }
        public function setDayOfMonth($dayIndex){
            if($this->dateTime){
                if($dayIndex>27){
                    $max = cal_days_in_month(CAL_GREGORIAN, $this->getMonth(), $this->getYear());
                    if($dayIndex>$max){ $dayIndex = $max; }                    
                }
                $this->dateTime->setDate($this->getYear(), $this->getMonth(),$dayIndex);
            }
            return $this;
        }        
        public function clear(){
            $this->dateTime = null;
            $this->__isok = false;
        }
        public function isOK(){
            if($this->__isok && !is_null($this->dateTime)){
                return true;
            }
            return false;
        }
        public function getTimeStamp(){
            return $this->dateTime->getTimestamp();
        }
        public function isElapsedMoreThan($timeout){
            $elapsed = $this->getElapsedTimeInSeconds(false,"now");
            return $elapsed>$timeout;
        }
        public function getElapsedTimeInHours($useAbs=false,$dateObj=null,$ceil=true){
            if($ceil){
                return ceil($this->getElapsedTimeInSeconds($useAbs,$dateObj)/3600);
            }else{
                return round($this->getElapsedTimeInSeconds($useAbs,$dateObj)/3600);
            }
            
        }
        public function getElapsedTimeInSeconds($useAbs=false,$dateObj=null){
            if(is_null($this->dateTime)){ return SmartDate::newDate("now")->getElapsedTimeInSeconds($useAbs,$dateObj);  } 
            if(is_null($dateObj)){                
                $t = time() - $this->dateTime->getTimestamp();
                return $useAbs ? abs($t) : $t;
            }else if(is_string($dateObj)){
                $t = (new SmartDate($dateObj))->getTimeStamp() - $this->dateTime->getTimestamp();
                return $useAbs ? abs($t) : $t;
            }else if($dateObj instanceof SmartDate){
                $t = $dateObj->getTimeStamp() - $this->dateTime->getTimestamp();
                return $useAbs ? abs($t) : $t;
            }
            return 0;
        }
        public function toDbDateTime(){
            return $this->dateTime ? $this->dateTime->format("Y-m-d H:i:s") : null;
        }
        public function toTimestamp(){
            return $this->dateTime ? $this->dateTime->getTimestamp() : null;
        }
        public function toDbDate(){
            return $this->dateTime ? $this->dateTime->format("Y-m-d") : null;
        }
        public function toFormat($format=null){
            return $this->dateTime && $format ? $this->dateTime->format($format) : null;
        }
        public function toFormDate(){
            return $this->dateTime ? $this->dateTime->format("d.m.Y") : null;
        }
        public function toFormDateTr(){
            return $this->dateTime ? $this->dateTime->format("d/m/Y") : null;
        }        
        public function toHourMinute(){
            return $this->dateTime ? $this->dateTime->format("H:i") : null;
        }        
        public function toTimeString(){
            return $this->dateTime ? $this->dateTime->format("H:i:s") : null;
        }
        public function toFormDateTime(){
            return $this->dateTime ? $this->dateTime->format("d.m.Y H:i:s") : null;
        }
        public function __toString(){
            return "".$this->toString();
        }
        public function toString(){
            if($this->dataType){
                if($this->dataType==CastUtil::$DATA_DATE){
                    return $this->toDbDate();        
                }else if($this->dataType==CastUtil::$DATA_DATETIME){
                    return $this->toDbDateTime();
                }
            }
            return $this->toDbDateTime();
        }
        public function getYear(){
            return !is_null($this->dateTime) ? CastUtil::getAs($this->dateTime->format("Y"),0,CastUtil::$DATA_INT) : 0;
        }
        public function getMonth($def=null){
            if(!is_null($this->dateTime)){ return $this->dateTime->format("m")+0; }
            return $def;
        }
        public function getDueDay(){
            $date1  = new DateTime(date("Y-m-d"));
            $date2  = new DateTime($this->toDbDate());
            $fark   = 0 + (int)$date1->diff($date2)->format("%r%a");
            //$fark = $this->dateTime->diff(SmartDate::newDate("now")->dateTime)->format("%r%a");
            return 0+$fark;
        }
        /**
         * @return SmartDate
         */
        public static function newDate($str="now",$applyType=null){
            return new SmartDate($str,null,$applyType);
        }
        public static function newSmart($str,$defVal=null,$dataType=null,$options=null,$applyType=null){
            $t = DateUtil::smartCastToDateObject($str,$defVal,$dataType,$options);
            if($t && $t instanceof SmartDate && !is_null($applyType) && strlen("".$applyType)>0){
                $t->applyType($applyType);
            }
            return $t;
        }
        
        public function getAge($refDateStr=null,$defVal=null,$decimal=-1){
           return DateUtil::getAge($this,$refDateStr,$defVal,$decimal);
        }
        public function getDayDifferenceWithoutTime($date2,$isAbsolute=true,$sign=1,$skipTimeCheck=true){
            return $this->getDayDifference($date2,$isAbsolute,$sign,$skipTimeCheck);
        }
        public function getDayDifference($date2,$isAbsolute=true,$sign=1,$skipTimeCheck=false){
            if($skipTimeCheck){                
                $a = DateUtil::newDate($this)->applyType("start");
                $b = DateUtil::newDate($date2)->applyType("start");
                return $a->getDayDifference($b,$isAbsolute,$sign,false);
            }
            if($date2 && $date2 instanceof SmartDate){
                if(is_null($date2->dateTime) || is_null($this->dateTime)){ return 0; }
                $interval = date_diff($this->dateTime, $date2->dateTime,$isAbsolute);
                return $isAbsolute ? $sign*$interval->days : $sign*( $interval->days*((-2*$interval->invert)+1) );
            }else if ($date2 && is_string($date2) && strlen($date2)>0){
                return $sign * $this->getDayDifference( SmartDate::newDate($date2),$isAbsolute);
            }
            return 0;
        }
        public function getWorkDayDifference($date2,$isAbsolute=false,$calismaGunleri=null,$tatiller=null,$countStartDay=true,$countEndDate=true){
            return DateUtil::getWorkDayDifference($this,$date2,$isAbsolute,$calismaGunleri,$tatiller,$countStartDay,$countEndDate);
        }
        public function getDaysInBetween($date2,$calismaGunleri=null,$tatiller=null,$isAbsolute=true,$countStartDay=true,$countEndDate=true){
            $list = array();
            //calismaGunleri - 1:pzt,2:sal - bos gelirse tum gunler
            //$tatiller - '*-12-25', '*-01-01', '2022-12-23'
            if(is_null($calismaGunleri) || !is_array($calismaGunleri) || count($calismaGunleri)==0){ $calismaGunleri =  [1, 2, 3, 4, 5,6,7];  }
            if(is_null($tatiller) || !is_array($tatiller)){ $tatiller = array(); }
            $from = new DateTime($this->toDbDate());
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
        public function toSoapDateTime(){            
            if($this->dateTime){
                $timeZone = "";
                if(!is_null($this->timeZone)){
                    if($this->timeZone>0){
                        $timeZone = '.000+'.substr("00".$this->timeZone, -2).":00";
                    }else if($this->timeZone<0){
                        $timeZone = '.000-'.substr("00".(-1*$this->timeZone), -2).":00";
                    }
                }
                return $this->dateTime->format("Y-m-d")."T".$this->dateTime->format("H:i:s").$timeZone; 
            }else{ return null; }
        }
        public function isBiggerThan($timeStr){            
            return $this->toDbDateTime() > self::newDate($timeStr)->toDbDateTime();
        }
        public function isBiggerOrEqual($timeStr){
            return $this->toDbDate() >= self::newDate($timeStr)->toDbDate();
        }
        public function isSmallerOrEqual($timeStr){
            return $this->toDbDate() <= self::newDate($timeStr)->toDbDate();
        }
        
        public function newCopy(){
            return SmartDate::newDate($this->toDbDateTime());
        }
        
        public function isDateEqual($timeStr){
            $d = self::newDate($timeStr);
            if(!is_null($d)){
                return $this->toDbDate()==$d->toDbDate();
            }
            return false;
        }
        public function getDayOfWeek(){
            if($this->dateTime){
                return 0 + $this->dateTime->format("w");
            }
            return 0;
        }
        public function getDayIndex(){
            if($this->dateTime){
                return $this->dateTime->format("d");
            }
            return "";
        }
        public function getMonthIndex(){
            if($this->dateTime){
                return $this->dateTime->format("m");
            }
            return "";
        }
        public function isWeekend(){
            return in_array($this->getDayOfWeek(), array(0,6));
        }
        public function getHourAsInt(){
            if($this->dateTime){ return CastUtil::asInt($this->dateTime->format("H")); }else{ return 0; }
        }
        public function getMinuteAsInt(){
            if($this->dateTime){ return CastUtil::asInt($this->dateTime->format("m")); }else{ return 0; }
        }
        public function getHourAsNumber(){
            return $this->getHourAsInt() + round($this->getMinuteAsInt()/60,5);
        }
        public function setTimeFromString($s){
            $tmp = preg_split("/:/", "".$s);
            if($tmp && count($tmp)>1){
                $this->setTime(CastUtil::asInt(@$tmp[0]),CastUtil::asInt(@$tmp[1]),CastUtil::asInt(@$tmp[2]));
            }
            return $this;
        }
        public function inBetween($t1,$t2){
            return DateUtil::isDateInBetween($this,$t1, $t2);
        } 
        public function isSunday(){
            return $this->getDayOfWeek()==0;
        }
        public function isSaturday(){
            return $this->getDayOfWeek()==6;
        }
        public function toPhpDateTime(){
            return $this->dateTime;
        }
        public function yearGreaterOrEqual($year=1970){
            return $this->getYear() >= $year;
        }
        public function isBefore($date2){
            $b = SmartDate::newDate($date2);
            return $this->toDbDateTime() < $b->toDbDateTime();
        }
    }
    ?>