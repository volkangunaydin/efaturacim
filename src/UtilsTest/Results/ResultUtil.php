<?php
namespace Efaturacim\Util\Utils\Results;

use Efaturacim\Util\Utils\CastUtil;

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
}
?>