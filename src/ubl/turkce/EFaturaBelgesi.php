<?php
namespace Efaturacim\Util\Ubl\Turkce;

use Efaturacim\Util\PreviewUtil;
use Efaturacim\Util\Ubl\InvoiceDocument;

class EFaturaBelgesi{
    /**
     * Summary of ubl
     * @var InvoiceDocument
     */
    public $ubl = null;
    public function __construct($faturaNo=null){
        $this->ubl = new InvoiceDocument();
        if(!is_null($faturaNo)){ $this->ubl->setId($faturaNo); }
        $this->ubl->setUuid();
        
    }
    public function setSaticiBilgileri($options=null,$clear=false){
        $this->ubl->accountingSupplierParty->loadFromOptions($options,$clear);
        return $this;
    }
    public function setAliciBilgileri($options=null,$clear=false){
        $this->ubl->accountingCustomerParty->loadFromOptions($options,$clear);
        return $this;
    }
    public function &getSatici(){
        return $this->ubl->accountingSupplierParty;
    }
    public function &getAlici(){
        return $this->ubl->accountingCustomerParty;
    }
    public function ekleSiparis($sipKodu=null,$tarih=null){
        if(is_null($tarih)){ $tarih = date('Y-m-d'); }
        $this->ubl->addToOrderList($sipKodu,$tarih);
        return $this;
    }
    public function ekleIrsaliye($irsKodu=null,$tarih=null){
        if(is_null($tarih)){ $tarih = date('Y-m-d'); }        
        $this->ubl->addToDespatchList($irsKodu,$tarih);
        return $this;
    }
    
    public function showAsXml($showOutput=true){
        $xmlString = $this->ubl->toXml();        
        return PreviewUtil::previewXml($xmlString,showOutput: $showOutput);        
    }
    public function showAsJson($showOutput=true){
        $xmlString = $this->ubl->toJson();        
        return PreviewUtil::previewJson($xmlString,showOutput: $showOutput);        
    }    
    public function showAsArray($showOutput=true){
        $arr = $this->ubl->toArrayOrObject();        
        return PreviewUtil::previewPhpVar($arr,showOutput: $showOutput);        
    }
    public static function fromJson($jsonString){                
        return self::smart($jsonString,"json");        
    }    
    public static function smart($strOrObject,$type=null){        
        $a = new static();
        $a->ubl->loadSmart($strOrObject,$type);
        return $a;        
    }    
}
?>