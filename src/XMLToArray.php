<?php

namespace Efaturacim\Util;

use Efaturacim\Util\Utils\Array\ArrayCopy;

class XMLToArray{
    public static function toArray($xmlStr,$appendObjectTag=false,$options=null){
        try {
            if ($appendObjectTag) {
                // Check if the XML string contains the XML declaration tag.
                if (preg_match('/(<\?xml.*?\?>)/i', $xmlStr)) {
                    // XML declaration found. Add the <object> tag after it.
                    $xmlStr = preg_replace('/(<\?xml.*?\?>)/i', '$1<object>', $xmlStr, 1) . '</object>';
                } else {
                    // No XML declaration, wrap the whole string.
                    $xmlStr = '<object>' . $xmlStr . '</object>';
                }
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
        if(Options::ensureParam($options) && $options instanceof VParams){
            $retVal = ArrayCopy::copy($array);
            if($options->getAsBool(array("to_lower_keys","lower_key"))){
                $retVal = ArrayCopy::copy($retVal,array("to_lower_keys"=>true));
            }
            return $retVal;
        }
        return $array;
    }        

}