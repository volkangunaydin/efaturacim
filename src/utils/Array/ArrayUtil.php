<?php
namespace Efaturacim\Util\Utils\Array;
class ArrayUtil
{
    public static function arrayGetKey($array, &$keyToReturn, $keyOrKeys, $defVal = null)
    {
        if (!is_null($keyOrKeys) && is_scalar($keyOrKeys)) {
            if (key_exists($keyOrKeys, $array)) {
                $keyToReturn = $keyOrKeys;
                return @$array[$keyOrKeys];
            }
        } else if (is_array($keyOrKeys)) {
            foreach ($keyOrKeys as $key) {
                if (key_exists($key, $array)) {
                    $keyToReturn = $key;
                    return @$array[$key];
                }
            }
        }
        $keyToReturn = null;
        return $defVal;
    }
    public static function notEmpty($arr)
    {
        if (!is_null($arr) && is_array($arr) && count($arr) > 0) {
            return true;
        }
        return false;
    }
    public static function arrayHasValue($arr, $nameOrNames)
    {
        $k = null;
        $v = self::arrayGetKey($arr, $k, $nameOrNames, null);
        return !self::isEmpty($v);
    }
    public static function isEmpty($strOrObject)
    {
        if (is_null($strOrObject) || $strOrObject === "") {
            return true;
        } else if (is_string($strOrObject) && strlen(trim("" . $strOrObject)) == 0) {
            return true;
        } else if (is_array($strOrObject) && count($strOrObject) == 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function isAssoc(array $array)
    {
        return (bool) !is_null($array) && is_array($array) && \count(array_filter(array_keys($array), 'is_string'));
    }
}
?>