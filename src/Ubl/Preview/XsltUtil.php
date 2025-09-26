<?php
namespace Efaturacim\Util\Ubl\Preview{

    use Efaturacim\Util\Utils\IO\IO_Util;
    use Efaturacim\Util\Utils\Options;
    use Efaturacim\Util\Utils\SimpleResult;

    class XsltUtil{
        public static function getHtmlFromXml($xml,$xslt,$options=null){
            return self::showXmlFileWithXslt($xml,$xslt,"html_string",$options);
        }
        
        public static function showXmlFileWithXslt($xml,$xslt,$output=null,$opt=null){
            $retVal  = new SimpleResult();
            $options = new Options($opt);
            $retVal->attributes["xml"]  = "";
            $retVal->attributes["xslt"] = "";            
            $isDebug =$options->getAsBool("debug");
            //
            if($xml && strlen("".$xml)<300 && file_exists($xml)){
                $retVal->attributes["xml"] = IO_Util::readFileAsString($xml);
            }else if($xml && strlen($xml)>0){
                $retVal->attributes["xml"] = $xml;
            }
            
            if(is_null($xslt) && $options->hasValue("xslt")){
                $xslt = $options->getAsString("xslt",null);
            }   
            
            if($xslt && strlen("".$xslt)>300){
                $retVal->attributes["xslt"] = $xslt;
            }else if($xslt && strlen("".$xslt)<300 && file_exists($xslt)){
                $retVal->attributes["xslt"] = IO_Util::readFileAsString($xslt);
            }else if($xslt && strlen($xslt)>0){
                $retVal->attributes["xslt"] = $xslt;
            }else if (is_null($xslt) && $options->getAsBool("autoload_xslt",true)){                
                $xslt = self::getXsltFromXml($retVal->attributes["xml"]);                
                if($xslt && strlen($xslt)>0){
                    $retVal->attributes["xslt"] = $xslt;
                }
            }
            
            if(strlen("".$retVal->attributes["xml"])>0 && strlen("".$retVal->attributes["xslt"])>0 ){
                
                $isAdmin = $options->getAsBool(array("isadmin"),false);                
                try {
                    if($isAdmin){
                        error_reporting(E_ALL);
                    }
                    $newdom = null;
                    $xml = new \DOMDocument();
                    $xsl = new \DOMDocument();
                    $proc = new \XSLTProcessor();                                        
                    $xml->loadXML($retVal->attributes["xml"]);
                    @$xsl->loadXML($retVal->attributes["xslt"]);
                    @$proc->importStyleSheet($xsl); // XSL kuralları
                    if($proc){
                        if($isAdmin){
                            try {                                
                                $newdom = $proc->transformToDoc($xml);                                
                            } catch (\Exception $e2) {
                                $retVal->addError("XML ve XSLT belge içeriği bulunamadı.");
                            }
                        }else{
                            $newdom = @$proc->transformToDoc($xml);
                        }                        
                    }
                    if($newdom){
                        $retVal->attributes["html"] = $newdom->saveHTML();
                        if(strlen("".$retVal->attributes["html"])>0){
                            $retVal->setIsOk(true);
                        }
                    }else{
                        $retVal->addError("Xslt çalıştırılırken hata alındı.");
                    }
                } catch (\Exception $e) {
                }
            }else{
                $retVal->addError("XML ve XSLT belge içeriği bulunamadı.");
            }
            if(!is_null($output)){
                if(in_array($output, array("screen","web","html"))){
                    echo @$retVal->attributes["html"];                
                    die("");                              
                }else if(in_array($output, array("string","html_string"))){    
                    return @$retVal->attributes["html"];
                }else if(in_array($output, array("pdf","pdfodc"))){
                    return self::showPdfFileFromHtml($retVal->attributes["html"],$output);
                }else if(in_array($output, array("xml_file","file_xml"))){
                    $filePath = self::getTargetFilePath($options);
                    IO_Util::writeTextFile($filePath,$retVal->attributes["xml"]);
                }
            }
            return $retVal;
        }
        public static function isValid($xsltString){
            if($xsltString && strlen("".$xsltString)>0){
                try {
                    error_reporting(0);
                    $processor = new \XSLTProcessor();
                    $xslDom = new \DOMDocument();                    
                    $isok = $xslDom->loadXML($xsltString);                    
                    if($isok){                        
                        $isok = $processor->importStylesheet($xslDom);                        
                        if(is_bool($isok)){
                            return $isok;
                        }                        
                    }                    
                } catch (\Exception $e) {
                }
            }
            return false;
        }
        public static function getXsltFromXml($xmlString){
            $xsltString = '';
            dd("IMPLEMENT getXsltFromXml");
            return $xsltString;
        }        
    }

}

?>