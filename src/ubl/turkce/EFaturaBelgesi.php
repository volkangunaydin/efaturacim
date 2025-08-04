<?php
namespace Efaturacim\Util\Ubl\Turkce;

use Efaturacim\Util\PreviewUtil;
use Efaturacim\Util\Ubl\InvoiceDocument;

class EFaturaBelgesi extends EBelge{
    /**
     * Summary of ubl
     * @var InvoiceDocument
     */
    public $ubl = null;
    public function __construct($faturaNo=null){
        parent::__construct("invoice");                
    }
    public function getFaturaNo(){
        return $this->ubl->getId();
    }
    public static function fromXmlFile($xmlFile){
        $a =  new EFaturaBelgesi();
        if($xmlFile && strlen("".$xmlFile)>0 && file_exists($xmlFile) && is_readable($xmlFile)){
            $a->ubl->loadFromXml(file_get_contents($xmlFile));            
        }        
        return $a;
    }
}
?>