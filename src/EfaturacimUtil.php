<?php
namespace Efaturacim\Util;
class EfaturacimUtil{
    public static function incAll(){
        require_once("CastUtil.php");
        require_once("ArrayUtil.php");
        require_once("StrUtil.php");          
        require_once("SimpleResult.php");        
        require_once("CookieUtil.php");
        require_once("SecurityUtil.php");
        require_once("Options.php");
        require_once("RestApiResult.php");
        require_once("RestApiClient.php");
        
    }
}
?>