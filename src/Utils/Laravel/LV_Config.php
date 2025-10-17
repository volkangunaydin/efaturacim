<?php
namespace Efaturacim\Util\Utils\Laravel;

use Efaturacim\Util\Utils\String\StrUtil;

class LV_Config{
    public static function getConfig($configFile){
        $arr = array();        
        if(file_exists($configFile)){
            $ext = pathinfo($configFile,PATHINFO_EXTENSION);
            if($ext == "env"){
                $ini = parse_ini_file($configFile);
                if(is_array($ini)){
                    foreach($ini as $key => $value){
                        if(StrUtil::notEmpty($value) && substr($value,0,1) == "{"){                            
                            $arr[$key] = json_decode($value,true);
                        }else{
                            $arr[$key] = $value;
                        }
                    }
                }
            }
        }
        return $arr;
    }
}
?>