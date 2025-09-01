<?php
namespace Efaturacim\Util\Utils\String;

use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StrUtil;

class StrNameSurname
{

    public static function isEqual($ad, $soyad, $ad2, $soyad2, $searchSimilar = false, $upperCaseCompare = true)
    {
        if ($ad == $ad2 && $soyad == $soyad2) {
            return true;
        }
        $ad = trim("" . @$ad);
        $soyad = trim("" . @$soyad);
        $ad2 = trim("" . $ad2);
        $soyad2 = trim("" . $soyad2);
        if ($ad == $ad2 && $soyad == $soyad2) {
            return true;
        }
        if ($upperCaseCompare) {
            $ad = StrUtil::toUpperTurkish($ad);
            $soyad = StrUtil::toUpperTurkish($soyad);
            $ad2 = StrUtil::toUpperTurkish($ad2);
            $soyad2 = StrUtil::toUpperTurkish($soyad2);
            if ($ad == $ad2 && $soyad == $soyad2) {
                return true;
            }
        }
        return false;
    }
    public static function isEqualNameSurname($adSoyad, $adSoyad2, $searchSimilar = false, $upperCaseCompare = true)
    {
        if ($adSoyad == $adSoyad2) {
            return true;
        }
        $adSoyad = trim("" . @$adSoyad);
        $adSoyad2 = trim("" . @$adSoyad2);
        if ($upperCaseCompare) {
            $adSoyad = StrUtil::toUpperTurkish($adSoyad);
            $adSoyad2 = StrUtil::toUpperTurkish($adSoyad2);
        }
        $adSoyad = str_replace(array("  "), array(" "), $adSoyad);
        $adSoyad2 = str_replace(array("  "), array(" "), $adSoyad2);

        if ($adSoyad == $adSoyad2) {
            return true;
        }
        return false;
    }
    public static function notEmptyString($str)
    {
        return !is_null($str) && is_scalar($str) && strlen(trim("" . $str)) > 0;
    }
    public static function isEqualOrNearlyEqual($adSoyad1, $adSoyad2, $searchSimilar = true)
    {
        if (self::notEmptyString($adSoyad1) && self::notEmptyString($adSoyad2)) {
            $adSoyad1 = str_replace(array("  ", "  ", "\t"), array(" ", " ", " "), trim($adSoyad1));
            $adSoyad2 = str_replace(array("  ", "  ", "\t"), array(" ", " ", " "), trim($adSoyad2));
            if (("" . $adSoyad1) == ("" . $adSoyad2)) {
                return true;
            } else if ($searchSimilar && StrLike::isSimilar($adSoyad1, $adSoyad2, 90, 3)) {
                return true;
            }
        }
        return false;
    }
    public static function getAsResult($nameSurname, $options = null)
    {
        $r = new SimpleResult();
        $r->setAttribute("org", $nameSurname);
        if (Options::ensureParam($options) && $options instanceof Options) {
            $str = trim(str_replace(array("'", "\""), array(" ", " "), $nameSurname));
            if (($pos = strpos($str, ",")) !== false) {
                $arr = array(trim(substr($str, 0, $pos)), trim(substr($str, $pos + 1)));
            } else if (($pos = strpos($str, "-")) !== false) {
                $arr = array(trim(substr($str, 0, $pos)), trim(substr($str, $pos + 1)));
            } else {
                $arr = self::tokenizeString($str);
            }
            $r->attributes["name"] = "";
            $r->attributes["surname"] = "";
            $r->attributes["ad1"] = "";
            $r->attributes["ad2"] = "";
            $r->attributes["ad3"] = "";
            $r->attributes["token_count"] = 0;
            if (count($arr) > 1) {
                $r->attributes["surname"] = array_pop($arr);
                $r->attributes["name"] = implode(" ", $arr);
                $r->attributes["token_count"] = 2;
                if (count($arr) > 0) {
                    $tokens = self::tokenizeString($r->attributes["name"]);
                    if (count($tokens) > 0) {
                        foreach ($tokens as $kk => $vv) {
                            $r->attributes["ad" . ($kk + 1)] = $vv;
                        }
                        $r->attributes["token_count"] = count($tokens) + 1;
                    } else {
                        $r->attributes["ad1"] = $r["name"];
                    }
                }
                $r->setIsOk(true);
            } else if (count($arr) == 1) {
                $r->attributes["name"] = @$arr[0];
                $r->attributes["token_count"] = 1;
            }
        }
        return $r;
    }
    protected static function tokenizeString($str)
    {
        $arr = array();
        try {
            $str = str_replace(array(chr(194), chr(160)), array(" ", " "), $str);
            $tmpArr = preg_split('/[\s]+/', $str);
            foreach ($tmpArr as $v) {
                if (strlen(trim($v)) > 0) {
                    $arr[] = trim($v);
                }
            }
        } catch (\Exception $e) {
        }
        return $arr;
    }
    public static function isEmpty($str)
    {
        if (is_null($str) || $str == "" || trim("" . $str) == "") {
            return true;
        }
        $r = self::getAsResult($str);

        return !$r->isOK();
    }
    public static function str($name, $surname)
    {
        $s = StrCase::camelCase($name);
        if (strlen("" . $surname) > 0) {
            $s .= " " . StrCase::toUpperTurkish($surname);
        }
        return $s;
    }
    public static function isValid($nameSurname)
    {
        return self::getAsResult($nameSurname)->isOK();
    }
    public static function getAsString($name, $surname, $defaultValue = null)
    {
        if (StrUtil::notEmpty($name)) {
            return trim("" . $name . " " . $surname);
        } else {
            return $defaultValue;
        }

    }
}
?>