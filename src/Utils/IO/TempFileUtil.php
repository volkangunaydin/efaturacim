<?php
namespace Efaturacim\Util\Utils\IO{

    use Efaturacim\Util\Utils\Json\JsonUtil;
    use Efaturacim\Util\Utils\Options;
    use Efaturacim\Util\Utils\SimpleResult;
    use Efaturacim\Util\Utils\String\RandomUtil;
    use Efaturacim\Util\Utils\String\StrUtil;

    class TempFileUtil{
        public static function getTempFilePath($ext=null){
            $r = self::createTempFile(null,array("ext"=>$ext,"write_file"=>false));
            if($r->isOK()){
                return $r->value;
            }else{
                return "";
            }            
        }
        public static function png(){
            return self::getTempFilePath("png");
        }
        public static function getTempFile($ext=null,$content=null){
            $r = self::createTempFile($content,array("ext"=>$ext,"write_file"=>($content && strlen("".$content)>0)));
            if($r->isOK()){
                return $r->value;
            }else{
                return "";
            }
        }
        public static function random($length=20){
            return RandomUtil::randomString($length);
        }
        public static function createTempFile($content,$options=null){
            $r = new SimpleResult();
            if(Options::ensureParam($options) && $options instanceof Options){
                $r->attributes["temp_folder"] = self::getTempFolder($options);                
                $r->attributes["extension"]   = $options->getAsString(array("ext","extension"));
                if(StrUtil::isEmpty($r->attributes["extension"])){
                    $r->attributes["extension"] = "tmp";
                }
                $r->attributes["filename"]    = self::random(20);
                $r->attributes["full_path"]   =  $r->attributes["temp_folder"].$r->attributes["filename"].".".$r->attributes["extension"];
                if(strlen("".@$r->attributes["temp_folder"])>0 &&  file_exists($r->attributes["temp_folder"]) && !file_exists($r->attributes["full_path"])){
                    $r->setIsOk(true);
                    $r->value = $r->attributes["full_path"];
                    if($options->getAsBool("write_file",true)){
                        IO_Util::writeTextFile(@$r->attributes["full_path"],$content);
                    }                    
                }                
            }
            return $r;
        }
        public static function getTempFolder($options=null){
            return str_replace("\\","/",trim( realpath(sys_get_temp_dir()) ))."/";
        }
        public static function getTempFileContent($funcForFileCreate,$ext=null){
            $s = "";
            if($funcForFileCreate && is_callable($funcForFileCreate)){
                $tempFilePath = self::getTempFile($ext);
                if(strlen("".$tempFilePath)>0){
                    call_user_func_array($funcForFileCreate, array($tempFilePath));
                    if(file_exists($tempFilePath)){
                        $s = IO_Util::readFileAsString($tempFilePath);
                        IO_Util::deleteFile($tempFilePath);                        
                    }
                }
            }
            return $s;
        }
        public static function createDebugFile($arrParams=null){
            $p = new Options($arrParams,array("t"=>date("Y-m-d H:i:s")));
            $c = JsonUtil::toJsonFileContent($p->params);
            $path = self::getTempFolder()."debug.json";
            IO_Util::writeTextFile($path,$c);
        }
    }
}
?>