<?php
namespace Efaturacim\Util\Utils\Results;

use Efaturacim\Util\Utils\CastUtil;
use Efaturacim\Util\Utils\Html\Bootstrap\Alert;
use Efaturacim\Util\Utils\Html\Bootstrap\BootstrapDocument;
use Efaturacim\Util\Utils\SimpleResult;
use Vulcan\Base\Util\Result\ResultUtil as ResultResultUtil;
class ResultUtil{
    public static function newFromJson($jsonStringOrArray,$class){        
        if(!is_null($class) && class_exists($class)){            
            if(is_array($jsonStringOrArray)){                
                $r = new $class();                
                foreach($jsonStringOrArray as $k=>$v){                    
                    if ($k=="isok"){
                        $r->setIsOk(CastUtil::getAs($v,false,CastUtil::$DATA_BOOL));
                    }else if(property_exists($r,$k)){
                        $r->$k = $v;
                    }
                }
                //\Vulcan\V::dump(array("res"=>$r,"arr"=>$jsonStringOrArray));
                return $r;
            }else if (is_string($jsonStringOrArray)){                
                try {
                    $arr = json_decode($jsonStringOrArray,true);                    
                    if(is_array($arr)){
                        return self::newFromJson($arr,$class);
                    }    
                } catch (\Throwable $th) {
                    //throw $th;
                }                
            }
            $r = new $class();
            return $r;
        }
        return null;
    }
    public static function mergeMessages(&$result,$resForMerge){
        if($result instanceof SimpleResult && $resForMerge instanceof SimpleResult){
            foreach ($resForMerge->messages as $msg){
                $result->addMessage($msg["text"],@$msg["type"]);                   
            }
        }
        return $result;
    }    
    public static function showResultAsHtml($result,$returnString=true){
        if($returnString){
            $s = '';
            if($result instanceof SimpleResult){
                if($result->isOK()){
                    $s .= Alert::success("Result is OK");
                }else{
                    $s .= Alert::danger("Result is not OK");
                }
                if($result->attributes && count($result->attributes)>0){
                    $s .= Alert::info("<h4>Attributes</h4><pre>".json_encode($result->attributes,JSON_PRETTY_PRINT)."</pre>");
                }
                foreach($result->messages as $msg){
                    $s .= Alert::alert(@$msg["type"],@$msg["text"].'<br/><span style="font-size:0.8em;">'.@$msg["t"].'</span>');
                }
            }
            return $s;
        }else{
            $s = self::showResultAsHtml($result,true);
            $d = new BootstrapDocument();
            $d->addBodyContent($s);
            $d->show();
        }
    }
    public static function getResultMessagesAsHtml($result){
        $s = '';
        if($result instanceof SimpleResult){
            foreach($result->messages as $msg){
                $s .= Alert::alert(@$msg["type"],@$msg["text"].'');
            }
        }
        return $s;
    }
}
?>