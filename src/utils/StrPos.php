<?php
namespace Efaturacim\Util\Utils;
    class StrPos{
        public $start = -1;
        public $end   = -1;
        public $startText = "";
        public $endText  = "";
        public $haystack = "";
        public function __construct($haystack=""){
            $this->haystack = $haystack;
        }
        public function getStartText(){
            return substr("".$this->haystack,0, $this->start);
        }
        public function getEndText(){
            if($this->isOK()){
                return substr("".$this->haystack,$this->end+1);
            }
            return "";
        }
        
        public function getNextPosFor($needle,$takeStartPos=false){
            if($this->end>0){
                $s2 = substr($this->haystack, $this->end);
                $p2 = StrPos::getPos($s2, $needle);
                if($p2->isOK()){
                    $this->end = $this->end + $p2->end;
                    if($takeStartPos){
                        $this->end = $this->end -(strlen($needle)+1);
                    }
                    //\Vulcan\V::dump($this->getInBetween());
                }else{
                    return $this->invalidate();                    
                }
            }else{
                return $this->invalidate();                
            }
            return $this;
        }
        public function invalidate(){
            $this->start = -1;
            $this->end = -1;
            return $this;
        }
        public function isOK(){
            return $this->start>=0 && $this->end>1;
        }
        public function trimmed(){
            return trim("".$this->getAsString());
        }
        public function getInBetween(){
            if($this->isOK()){
                $i = mb_strlen($this->startText);
                $j = mb_strlen($this->endText);
                return mb_substr($this->haystack, $this->start+$i,$this->end-$this->start-($j+$i));
            }
            return "";
        }
        public function getAsString(){
            if($this->isOK()){
                return mb_substr($this->haystack, $this->start,$this->end-$this->start);
            }
            return "";
        }
        public function removeEnd(){
            if(strlen("".$this->endText)>0){
                $i = mb_strlen($this->endText);
                if($this->end>$i){
                    $this->end = $this->end-$i;
                    $this->endText = '';
                }                
            }
            return $this;
        }
        public function extendTo($nextEl){
            if($nextEl && $nextEl instanceof StrPos && $nextEl->isOK()){
                if($nextEl->end>$this->end){
                    $this->end = $nextEl->end;
                }
            }else{
                $this->invalidate();
            }
        }
        public function replace($str){
            if($this->isOK()){
                $s = "";
                if($this->start>0){
                    $s .= mb_substr($this->haystack, 0,$this->start);
                }                               
                $s .= $str;
                if($this->end<strlen($this->haystack)){
                    $s .= mb_substr($this->haystack, $this->end);
                }                                               
                return $s;
            }
            return $this->haystack;
        }
        public function after($str){
            if($this->isOK()){
                if($this->end<strlen($this->haystack)){
                    return mb_substr($this->haystack,0, $this->end).$str.mb_substr($this->haystack,$this->end);
                }else{
                    return $this->haystack.$str;
                }
            }
            return $this->haystack;
        }
        public function before($str){
            if($this->isOK()){
                if($this->end<strlen($this->haystack)){                    
                    return mb_substr($this->haystack,0, $this->start).$str.mb_substr($this->haystack,$this->start);
                }else{
                    return $str.$this->haystack;
                }
            }
            return $this->haystack;
        }
        public static function getSegment($haystack,$start,$end,$contains=null,$offset=0,$isTag=null,$tag=null){
            $list = self::getSegments($haystack, $start, $end,$contains,$offset,$isTag,$tag);            
            return count($list)>0 ? $list[0] : (new StrPos());
        } 
        public static function subStrCount($haystack,$text){
            return substr_count(strtolower("".$haystack), strtolower("".$text));
        }
        public static function getCompleteTag($haystack,$start,$end,$tag,$needle="",$depth=0){
            if($depth>100){
                return false;
            }
            $p = FALSE;
            $sub = substr($haystack, $start,$end-$start);
            $c1 = self::subStrCount($sub, "<".$tag);
            $c2 = self::subStrCount($sub, "</".$tag);
            $cc = $c1-$c2;
            if($c1>0 && $cc>=0 && $cc<=1){
                // ok
                return $end;
            }
            if($needle && strlen("".$needle)>0){
                $pos2 = mb_stripos($haystack, $needle,$end+1);
                if($pos2!==FALSE && $pos2>=0 && $pos2>$end){
                    $pp = self::getCompleteTag($haystack, $start, $pos2, $tag,$needle,$depth+1);
                    if($pp && $pp>$end){
                        return $pp;
                    }
                }                
            }                        
            return $p;
        }
        public static function getSegments($haystack,$start,$end,$contains=null,$initialOffset=0,$isTag=null,$tag=null){
            $list      = array();
            $offSet    = $initialOffset;
            $count     = 0;
            if($isTag && is_null($tag)){
                $tag = trim("".str_replace("<", "",$start));
            }
            while($count<1000){
                $count++;
                $pos = mb_stripos($haystack, $start,$offSet);                
                if($pos!==FALSE && $pos>=0){
                    $offSet = $pos+1;
                    $pos2 = mb_stripos($haystack, $end,$offSet);
                    if($isTag && $pos2!==FALSE && $pos2>=0){
                        $pos2 = self::getCompleteTag($haystack,$offSet,$pos2,$tag,$end);                                               
                    }
                    if($pos2!==FALSE && $pos2>=0){
                        $a = new StrPos($haystack);
                        $a->start = $pos;
                        $a->end   = $pos2+strlen($end);  
                        $a->startText = $start;
                        $a->endText   = $end;
                        if($contains && is_array($contains) && count($contains)>0){                            
                            if(StrContains::containsAll($a->getAsString(), $contains)){
                                $list[] = $a;
                            }
                        }else{
                            $list[] = $a;
                        }
                    }                   
                }else{
                    return $list;
                }                
            }
            return $list;
        }
        public static function getPos($haystack,$needle,$caseInsensitive=true){
            if(strlen("".$haystack)>0){
                $offSet = 0;
                if($caseInsensitive){
                    $pos = mb_stripos($haystack, $needle,$offSet);     
                }else{
                    $pos = mb_strpos($haystack, $needle,$offSet);     
                }                
                if($pos!==FALSE && $pos>=0){
                    $a = new StrPos($haystack);
                    $a->start = $pos;
                    $a->end   = $pos + strlen($needle); 
                    return $a;
                }
            }
            return new StrPos();
        }
        public static function replaceSegments($haystack,$start,$end,$replaceOrReplaceFunction=null,$contains=null){
            $retVal = "".$haystack;
            $segments = self::getSegments($haystack, $start, $end,$contains);
            $offset   = 0;
            foreach ($segments as $segment){
                if($segment && $segment instanceof StrPos){
                    $s = self::getSegment($retVal, $start, $end,$contains,$segment->start+$offset);
                    if($s && $s instanceof StrPos){
                        $replaceString = "".$replaceOrReplaceFunction;
                        $offset = $offset  + strlen($replaceString) - strlen($s->getInBetween());                        
                        $retVal = $s->replace($replaceString);
                        //\Vulcan\V::dump($retVal);
                    }
                    //\Vulcan\V::dump(array($s->start,$segment->start));
                    //$retVal = $segment->replace($str)
                }
            }
            return $retVal;
        }
        public static function getNextPos($haystack,$needlesArray,$start=0){            
            $currPos = -1;
            foreach ($needlesArray as $needle){
                $p = strpos($haystack, $needle,$start);
                if($p!==false){
                    if($p<=$currPos || $currPos<0){
                        $currPos = $p;
                    }
                }                
            }            
            return $currPos;    
        }
        public static function copySegmentTo($org,$start,$end,$start2,$func=null){
            $s  = $org;
            $p  = StrPos::getSegment($s, $start, $end);
            $p2 = strpos($s, $start2);            
            if($p2>0){
                $p2 = $p2+strlen($start2);
                $a = substr($s, 0,$p2);                
                $b = $p->getInBetween();
                if($func && is_callable($func)){
                    call_user_func_array($func, array(&$b));
                }
                $c = substr($s, $p2+1);                
                return $a.$b.$c;
            }
            return $s;
        }
    }

?>