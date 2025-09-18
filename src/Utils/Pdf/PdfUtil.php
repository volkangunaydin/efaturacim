<?php

namespace Efaturacim\Util\Utils\Pdf;

use Efaturacim\Util\Utils\IO\IO_Util;
use Efaturacim\Util\Utils\IO\TempFileUtil;
use Efaturacim\Util\Utils\Options;
use Efaturacim\Util\Utils\SimpleResult;
use Efaturacim\Util\Utils\String\StrUtil;


class PdfUtil{
    public static $wkHtmlToPdfExe = null;
    public static $exeOptions = array();
    public static function getPdfFromHtml($html,$options=array()){
        $r = new SimpleResult();
        $exe = self::getWkHtmlToPdfExe();
        if(StrUtil::notEmpty($exe) && Options::ensureParam($options) && $options instanceof Options){
            $r->setAttribute("exe",$exe);
            $template = $options->getAsString("template","ubl");
            $cmd = $exe;            
            if($template && in_array("".$template,array("ubl"))){
                self::setExeOption($cmd,"--viewport-size","\"1024x768\"","viewport");
                self::setExeOption($cmd,"--page-size","\"A4\"","viewport");                
                self::setExeOption($cmd,"--encoding","\"utf-8\"","encoding");
                self::setExeOption($cmd,"--enable-javascript", "","js");
                self::setExeOption($cmd,"--enable-external-links", "","ext");
                self::setExeOption($cmd,"--enable-forms", "","form");
                self::setExeOption($cmd,"--enable-internal-links", "","intlinkjs");
                self::setExeOption($cmd,"--enable-local-file-access", "","local");
                self::setExeOption($cmd,"--enable-smart-shrinking", "","shrink");
                //self::setExeOption("--disable-smart-shrinking ", "","shrink");                
                self::setExeOption($cmd,"--javascript-delay", "2000","delay");                
            }
            $htmlFile = TempFileUtil::getTempFile("html",$html);
            $pdfFile  = TempFileUtil::getTempFile("pdf");
            $cmd .= " \"".$htmlFile."\" \"".$pdfFile."\"";
            $output = array();
            @exec($cmd,$output);            
            if(file_exists($htmlFile)){
                IO_Util::deleteFile($htmlFile);
            }
            if(file_exists($pdfFile)){
                $r->setIsOk(true);
                $r->value  = IO_Util::readFileAsString($pdfFile);                
                IO_Util::deleteFile($pdfFile);
            }else{
                $r->addError("Pdf dosyası oluşturulamadı");
            }
            $r->setAttribute("cmd",$cmd);
        }else{
            $r->addError("WkHtmlToPdf exe bulunamadı");
        }
        return $r;
    }
    public static function setExeOption(&$cmd,$name,$val,$key=null){
        if(is_null($key)){ $key = $name; }
        self::$exeOptions[$key] = array("name"=>$name,"val"=>$val,"key"=>$key);
        if(is_null($val) || $val==""){
            $cmd .= " ".$name;
        }else{
            $cmd .= " ".$name." ".$val;
        }        
        return $cmd;
    }
    public static function getWkHtmlToPdfExe(){
        if(self::$wkHtmlToPdfExe){
            return self::$wkHtmlToPdfExe;
        }
        $possiblePaths = array("/usr/local/bin/wkhtmltopdf","/usr/bin/wkhtmltopdf","C:/wamp64/bin/wkhtmltopdf/bin/wkhtmltopdf.exe");
        foreach($possiblePaths as $path){
            if(file_exists($path)){
                self::$wkHtmlToPdfExe = $path;
                return $path;
            }
        }        
        return null;
    }
}

?>