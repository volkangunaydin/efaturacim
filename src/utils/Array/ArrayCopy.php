<?php
namespace Efaturacim\Util\Utils\Array;

use Efaturacim\Util\Options;
use Efaturacim\Util\StrUtil;
use Vulcan\Base\Util\ArrayUtil\ArrayCopy as ArrayUtilArrayCopy;

class ArrayCopy{
    public static function copy($arr,$context=null){
        $r = array();
        if(Options::ensureParam($context)  && $context instanceof Options){
            $to_lower_keys = $context->getAsBool(array("to_lower_keys","lower_key"));
            if(is_array($arr) && $context->getAsBool(array("reindex"))){
                foreach ($arr as $k=>$v){
                    $r[] = $v;
                }
            }else if(is_array($arr)){
                foreach ($arr as $k=>$v){
                    $key   = $k;
                    if($to_lower_keys){                            
                        $key = StrUtil::toLowerEng($key);
                    }
                    $r[$key] = $v;
                }
            }
        }
        return $r;        
    }
    
}
?>