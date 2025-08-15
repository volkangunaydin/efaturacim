<?php
namespace Efaturacim\Util\Utils\Url;
class UrlUtil{
    public static function getUrl($url=null,$newParams=null,$excludeParams=null){
        return new UrlObject($url,$newParams,$excludeParams);
    }
}
?>