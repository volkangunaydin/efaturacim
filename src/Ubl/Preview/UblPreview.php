<?php 
namespace Efaturacim\Util\Ubl\Preview;

class UblPreview{
    public static function getHtmlFromXml($xmlString,$xsltString,$options=null){
        return XsltUtil::showXmlFileWithXslt($xmlString,$xsltString,$options);
    }
}

?>