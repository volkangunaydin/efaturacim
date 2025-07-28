<?php

namespace Efaturacim\Util;

class XMLToArray{
    public static function toArray($xmlStr,$appendObjectTag=false,$options=null){
        try {
            if($appendObjectTag){
                $xmlStr = '<object>'.$xmlStr.'</object>';
            }
            
            // Namespace'leri temizle
            $xmlStr = preg_replace('/xmlns[^=]*=\"[^\"]*\"/i', '', $xmlStr);
            
            libxml_use_internal_errors(true);
            $xml = simplexml_load_string($xmlStr, "SimpleXMLElement", LIBXML_NOCDATA | LIBXML_NSCLEAN);
            
            if ($xml === false) {
                return array();
            }
            
            $json = json_encode($xml);
            $array = json_decode($json, TRUE);
            
            if(is_array($array)){
                if(!is_null($options)){
                    return self::applyOptionsAndReturn($array,$options);
                }else{
                    return $array;
                }
            }
        } catch (\Exception $e) {
        }
        return array();
    }
    public static function applyOptionsAndReturn(&$array,$options=null){
        if(VParams::ensureParam($options) && $options instanceof VParams){
            $retVal = ArrayCopy::copy($array);
            if($options->getAsBool(array("to_lower_keys","lower_key"))){
                $retVal = ArrayCopy::copy($retVal,array("to_lower_keys"=>true));
            }
            return $retVal;
        }
        return $array;
    }        

}