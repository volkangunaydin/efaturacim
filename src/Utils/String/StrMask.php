<?php

namespace Efaturacim\Util\Utils\String;

use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\CastUtil;

class StrMask{
    public static function smart($string, $options=[]){
        // Handle null or empty input
        if (is_null($string) || $string === '') {
            return '';
        }
        
        $string = (string) $string;
        $length = strlen($string);
        
        // Default options
        $char = '*';
        $maxChars = 3;
        $maxPercent = 0.5;
        
        // Apply options if provided
        if (Options::ensureParam($options) && $options instanceof Options) {
            $char = $options->getAsString('char', '*');
            $maxChars = $options->getAsInt('maxChars', 3);
            $maxPercent = $options->getAs('maxPercent', 0.5, CastUtil::$DATA_NUMBER);
        } else if (is_array($options)) {
            // Handle array options directly
            $char = isset($options['char']) ? $options['char'] : '*';
            $maxChars = isset($options['maxChars']) ? (int)$options['maxChars'] : 3;
            $maxPercent = isset($options['maxPercent']) ? (float)$options['maxPercent'] : 0.5;
        }
        
        // Handle very short strings
        if ($length <= 2) {
            return str_repeat($char, $length);
        }
        
        // Calculate how many characters to mask
        $maxMaskChars = min($maxChars, floor($length * $maxPercent));
        
        // For strings longer than 4 characters, mask the middle part
        if ($length > 4) {
            // Calculate how many characters to keep at the beginning and end
            $remainingChars = $length - $maxMaskChars;
            $startKeep = floor($remainingChars / 2);
            $endKeep = $remainingChars - $startKeep;
            
            $result = substr($string, 0, $startKeep) . 
                     str_repeat($char, $maxMaskChars) . 
                     substr($string, $length - $endKeep);
        } else {
            // For shorter strings, mask the middle characters
            $maskStart = floor(($length - $maxMaskChars) / 2);
            $result = substr($string, 0, $maskStart) . 
                     str_repeat($char, $maxMaskChars) . 
                     substr($string, $maskStart + $maxMaskChars);
        }
        
        return $result;
    }
}
